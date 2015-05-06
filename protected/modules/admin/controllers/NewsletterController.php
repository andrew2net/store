<?php

class NewsletterController extends Controller {

  /**
   * @return array action filters
   */
  public function filters() {
    return array(
      array('auth.filters.AuthFilter'),
      'postOnly + delete', // we only allow deletion via POST request
    );
  }

  /**
   * Creates a new model.
   * If creation is successful, the browser will be redirected to the 'index' page.
   */
  public function actionCreate() {
    $model = new Newsletter;
    $blocks = [new NewsletterBlock];

    // Uncomment the following line if AJAX validation is needed
    // $this->performAjaxValidation($model);

    if (isset($_POST['Newsletter'])) {
      $this->saveNewsLetter($model, $blocks);
    }

    $this->render('create', array(
      'model' => $model,
      'blocks' => $blocks,
    ));
  }

  /**
   * Create a copy of existing model.
   * If copying is successful, the browser will be redirected tj the 'index' page.
   * @param integer $id the ID of the model to be copied
   */
  public function actionCopy($id){
    $model = $this->loadModel($id);
    $newsletter = new Newsletter;
    $newsletter->subject = $model->subject;
    $newsletter->send_price = $model->send_price;
    $blocks = [];
    foreach ($model->newsletterBlocks as $block){
      $newBlock = new NewsletterBlock;
      $newBlock->text = $block->text;
      $newBlock->image = $block->image;
      $blocks[] = $newBlock;
    }
    
    if (isset($_POST['Newsletter'])){
      $this->saveNewsLetter($newsletter, $blocks);
    }
    
    $this->render('create', ['model' => $newsletter, 'blocks' => $blocks]);
  }

  /**
   * Updates a particular model.
   * If update is successful, the browser will be redirected to the 'index' page.
   * @param integer $id the ID of the model to be updated
   */
  public function actionUpdate($id) {
    $model = $this->loadModel($id);

    if ($model->is_sent) {
      $this->redirect('/admin/newsletter');
    }

    $blocks = [];
    foreach ($model->newsletterBlocks as $value) {
      $blocks[$value->id] = $value;
    }

    // Uncomment the following line if AJAX validation is needed
    // $this->performAjaxValidation($model);

    if (isset($_POST['Newsletter'])) {
      $this->saveNewsLetter($model, $blocks);
    }

    $this->render('update', array(
      'model' => $model,
      'blocks' => $blocks,
    ));
  }

  /**
   * 
   * @param Newsletter $model
   * @param NewsletterBlock[] $blocks 
   * @throws Exception
   */
  private function saveNewsLetter($model, &$blocks) {
    $model->attributes = $_POST['Newsletter'];
    $valid = $model->validate();

    $oldBlocks = $blocks;
    $blocks = [];
    if (isset($_POST['NewsletterBlock'])) {
      foreach ($_POST['NewsletterBlock'] as $key => $value) {
        $blocks[$key] = NewsletterBlock::model()->findByAttributes(['newsletter_id' => $model->id, 'id' => $key]);
        if (!$blocks[$key]) {
          $blocks[$key] = new NewsletterBlock;
        }
        $blocks[$key]->text = $value['text'];
        if (isset($oldBlocks[$key])){
          $blocks[$key]->image = $oldBlocks[$key]->image;
        }
        $valid = $blocks[$key]->validate(['text']) && $valid;
      }
      if ($valid) {
        $tr = Yii::app()->db->beginTransaction();
        try {
          $model->save();
//          $model->getErrors();
          $keys = [];
          $uploaddir = $this->getImageDir();
          foreach ($blocks as $key => $value) {
            $value->newsletter_id = $model->id;
            $value->save();
            $keys[] = $value->id;
            if ($_POST['NewsletterBlock'][$key]['image'] == 'd') {
              if ($value->image && file_exists($uploaddir . $value->image)) {
                unlink($uploaddir . $value->image);
              }
              $value->image = '';
              $value->update('image');
              continue;
            }
            $files = glob($uploaddir . 'u' . Yii::app()->user->id . '_' . $key . '.*');
            foreach ($files as $file) {
              $fileInfo = new SplFileInfo(strtolower($file));
              $filename = $model->id . '_' . $value->id . '.' . $fileInfo->getExtension();
              $newname = $fileInfo->getPath() . '/' . $filename;
              rename($file, $newname);
              $value->image = $filename;
              $value->update('image');
            }
            if ($this->action->id == 'copy' && !empty($value->image) && !preg_match("/^$model->id/", $value->image)){
              $fileInfo = new SplFileInfo($value->image);
              $path = $this->getImageDir();
              $filename = preg_replace("/^\d+_\d+/", "{$model->id}_{$value->id}", $value->image);
              copy($path . $value->image, $path . $filename);
              $value->image = $filename;
              $value->update('image');
            }
          }
          $ids = implode(',', $keys);
          $this->delBlocks($model->id, $ids);
          $tr->commit();
          $this->redirect('/admin/newsletter');
        } catch (Exception $e) {
          $tr->rollback();
          throw $e;
        }
      }
    } else if ($model->save()) {
      $this->delBlocks($model->id);
      $this->redirect('/admin/newsletter');
    }
  }

  public function actionUpload() {
    if (!isset($_FILES['NewsletterBlock']['name'])) {
      Yii::app()->end();
    }
    $uploaddir = $this->getImageDir();
    foreach ($_FILES['NewsletterBlock']['name'] as $key => $value) {
      $tmp_file = $_FILES['NewsletterBlock']['tmp_name'][$key]['image'];
      $fileInfo = new SplFileInfo(strtolower($_FILES['NewsletterBlock']['name'][$key]['image']));
      $filename = 'u' . Yii::app()->user->id . '_' . $key . '.' . $fileInfo->getExtension();
      $uploadfile = $uploaddir . $filename;
      move_uploaded_file($tmp_file, $uploadfile);
    }
  }

  private function delBlocks($model_id, $blocks_ids = '0') {
    /* @var $blocks NewsletterBlock */
    $blocks = NewsletterBlock::model()->findAllByAttributes(['newsletter_id' => $model_id], "id NOT IN ($blocks_ids)");
    $imageDir = $this->getImageDir();
    foreach ($blocks as $value) {
      if ($value->image && file_exists($imageDir . $value->image)) {
        unlink($imageDir . $value->image);
      }
      $value->delete();
    }
  }

  private function getImageDir() {
    $img_storage = Yii::app()->params['img_storage'];
    return $uploaddir = Yii::getPathOfAlias('webroot') . DIRECTORY_SEPARATOR . 'images'
      . DIRECTORY_SEPARATOR . $img_storage . DIRECTORY_SEPARATOR . 'newsletter' . DIRECTORY_SEPARATOR;
  }

  /**
   * Deletes a particular model.
   * If deletion is successful, the browser will be redirected to the 'admin' page.
   * @param integer $id the ID of the model to be deleted
   */
  public function actionDelete($id) {
    if (Yii::app()->request->isPostRequest) {
      // we only allow deletion via POST request
      $this->delBlocks($id);
      $this->loadModel($id)->delete();

      // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
      if (!isset($_GET['ajax'])) {
        $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
      }
    } else {
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }
  }

  public function actionSend($id) {
    if (!Yii::app()->request->isPostRequest) {
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    $newsletter = $this->loadModel($id);

    $users = User::model()->findAll([
      'with' => 'profile',
      'condition' => 'profile.newsletter=1',
    ]);

    foreach ($users as $user) {
      /* @var $user User */
      $mail = new Mail;
      $mail->uid = $user->id;
      $mail->type_id = Mail::TYPE_SEND_NEWSLETTER;
      $f = $mail->save();

      $mailNewsletter = new MailNewsletter;
      $mailNewsletter->mail_id = $mail->id;
      $mailNewsletter->newsletter_id = $newsletter->id;
      $mailNewsletter->save();

      $newsletter->is_sent = 1;
      $f = $newsletter->save();
    }
  }

  /**
   * Manages all models.
   */
  public function actionIndex() {
    $model = new Newsletter('search');
    $model->unsetAttributes();  // clear any default values
    if (isset($_GET['Newsletter'])) {
      $model->attributes = $_GET['Newsletter'];
      Yii::app()->user->setState('Newsletter', $_GET['Newsletter']);
    }
    elseif (Yii::app()->user->hasState('Newsletter')) {
      $model->attributes = Yii::app()->user->getState('Newsletter');
    }
    if (isset($_GET['Newsletter_page']))
      Yii::app()->user->setState('Newsletter_page', $_GET['Newsletter_page']);
    elseif (isset($_GET['ajax']))
      Yii::app()->user->setState('Newsletter_page', NULL);
    elseif (Yii::app()->user->hasState('Newsletter_page'))
      $_GET['Newsletter_page'] = (int) Yii::app()->user->getState('Newsletter_page');

    if (isset($_GET['Newsletter_sort']))
      Yii::app()->user->setState('Newsletter_sort', $_GET['Newsletter_sort']);
    elseif (Yii::app()->user->hasState('Newsletter_sort'))
      $_GET['Newsletter_sort'] = Yii::app()->user->getState('Newsletter_sort');

    $this->render('admin', array(
      'model' => $model,
    ));
  }
  
  /**
   * Returns the data model based on the primary key given in the GET variable.
   * If the data model is not found, an HTTP exception will be raised.
   * @param integer $id the ID of the model to be loaded
   * @return Newsletter the loaded model
   * @throws CHttpException
   */
  public function loadModel($id) {
    $model = Newsletter::model()->findByPk($id);
    if ($model === null) {
      throw new CHttpException(404, 'The requested page does not exist.');
    }
    return $model;
  }

  /**
   * Performs the AJAX validation.
   * @param Newsletter $model the model to be validated
   */
  protected function performAjaxValidation($model) {
    if (isset($_POST['ajax']) && $_POST['ajax'] === 'newsletter-form') {
      echo CActiveForm::validate($model);
      Yii::app()->end();
    }
  }

}
