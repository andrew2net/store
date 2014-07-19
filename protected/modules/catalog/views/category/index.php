<?php
/* @var $this Controller */

$this->breadcrumbs = array(
  'Категории',
);
?>
<?php $this->beginContent('/catalog/menu'); ?>
<h3>Категории</h3>

<?php
//$this->widget('CTreeView', array(
//  'data' => $tree_data,
//));
$this->widget('application.widgets.JsTreeWidget', array(
  'modelClassName' => 'Category',
  'jstree_container_ID' => 'Category-wrapper',
  'themes' => array('theme' => 'default', 'dots' => true, 'icons' => true),
  'plugins' => array('themes', 'html_data', 'contextmenu', 'crrm', 'dnd', 'cookies', 'ui'),
));
?>
<?php
$this->endContent()?>