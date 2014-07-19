<?php

/**
 * Description of LandPageController
 *
 * @author 
 */
class LandpageController extends Controller {

  public function actionIndex() {
    $children = array();
    $popup_form = new PopupForm();
    $children[] = new Child('popup');
    $this->render('landPage', array(
      'children' => $children,
      'popup_form' => $popup_form,
    ));
  }

}
