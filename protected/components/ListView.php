<?php
/**
 * ListView is the customized CListView class.
 * PHP version 5
 * 
 */

Yii::import('zii.widgets.CListView');

class ListView extends CListView {

  /**
   * File name of the view for header
   * @var string 
   */
  public $headerView;

  /**
   * Sizer is used to change number of items in list.
   */
  public function renderSizer() {
    if ($this->dataProvider->getTotalItemCount() > 0) {
      $links = array();
      foreach ($this->viewData['sizes'] as $count) {
        $params = array_replace($_GET, array('size' => $count));
        if (isset($params['page']))
          unset($params['page']);

        $links[] = CHtml::link($count, Yii::app()->controller->createUrl('', $params));
      }
      $params['size'] = $this->dataProvider->getTotalItemCount();
      $links[] = CHtml::link('весь товар', Yii::app()->controller->createUrl('', $params));
      echo '<div class="sizer">Показывать по: ' . implode(', ', $links) . '</div>';
    }
  }

  /*
   * Header is used for a list in a table.
   */
  public function renderHeader() {
    if ($this->dataProvider->getTotalItemCount() > 0) {
      $view_file = $this->getOwner()->getViewFile($this->headerView);
      $this->renderFile($view_file, $this->viewData);
    }
  }

}