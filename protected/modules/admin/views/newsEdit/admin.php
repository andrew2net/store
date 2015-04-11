<?php
/* @var $this NewsController */
/* @var $model News */


$this->breadcrumbs = array(
  'Новости',
);
?>

<h3>Новости</h3>

<div class="btn-toolbar">
    <?php
    echo TbHtml::linkButton(
      'Добавить новость', array(
      'color' => TbHtml::BUTTON_COLOR_PRIMARY,
      'url' => array('create'),
      )
    );
    ?>
</div>

<?php
$this->widget('bootstrap.widgets.TbGridView', array(
  'id' => 'news-grid',
  'dataProvider' => $model->search(),
  'filter' => $model,
  'columns' => array(
    'title',
    'date',
    [
      'name' => 'active',
      'value' => '$data->active ? "&#10003;" : ""',
      'type' => 'html',
      'filter' => array(0 => 'Нет', 1 => 'Да'),
    ],
    array(
      'class' => 'bootstrap.widgets.TbButtonColumn',
    ),
  ),
));
?>