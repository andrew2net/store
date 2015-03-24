<?php
/* @var $this NewsletterController */
/* @var $model Newsletter */


$this->breadcrumbs = array(
  'Рассылки',
);
?>

<h3>Рассылки</h3>

<div class="btn-toolbar">
    <?php
    echo TbHtml::linkButton(
      'Добавить рассылку', array(
      'color' => TbHtml::BUTTON_COLOR_PRIMARY,
      'url' => array('create'),
      )
    );
    ?>
</div>

<?php
$this->widget('bootstrap.widgets.TbGridView', array(
  'id' => 'newsletter-grid',
  'dataProvider' => $model->search(),
  'filter' => $model,
  'columns' => array(
    'subject',
    array(
      'name' => 'is_sent',
      'value' => '$data->is_sent ? "&#10003;" : ""',
      'type' => 'html',
      'filter' => array(0 => 'Нет', 1 => 'Да'),
    ),
    'time',
    array(
      'class' => 'bootstrap.widgets.TbButtonColumn',
      'buttons' => [
        'send' => [
          'label' => '<i class="icon-envelope"></i>',
          'url' => 'Yii::app()->createUrl("/admin/newsletter/send", ["id" => $data->id])',
          'options' => ['title' => 'Разослать', 'class' => 'send-newsletter'],
          'visible' => '!$data->is_sent',
        ],
        'update' => ['visible' => '!$data->is_sent'],
        'delete' => ['visible' => '!$data->is_sent'],
      ],
      'template' => '{send}{update}{delete}',
    ),
  ),
));
?>
<script type="text/javascript">
  $(function () {
      $(document).on('click', '#newsletter-grid a.send-newsletter', function () {
        if (!confirm('Вы уверенны, что хотите отправить рассылку?')) return false;
        var th = this, afterSend = function (){};
        $('#newsletter-grid').yiiGridView('update', {
          type: 'POST',
          url: $(this).attr('href'),
          success: function (data) {
            $('#newsletter-grid').yiiGridView('update');
            afterSend(th, true, data);
          },
          error: function (XHR){
            return afterSend(th, false, XHR);
          }
        });
        return false;
      });
  });
</script>