<?php

class NewsController extends CController
{

	public function actionItem($id)
	{
      $model = News::model()->findByPk($id);
		$this->render('item', ['model' => $model]);
	}

}