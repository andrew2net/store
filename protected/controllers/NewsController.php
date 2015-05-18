<?php

class NewsController extends Controller
{

	public function actionItem($id)
	{
      $model = News::model()->findByPk($id);
		$this->render('item', ['model' => $model]);
	}

}