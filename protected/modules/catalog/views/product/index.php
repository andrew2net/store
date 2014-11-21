<?php
/* @var $this ProductController */
/* @var $model Product */
?>

<?php
$this->breadcrumbs = array(
  'Товары',
);
?>

<?php $this->beginContent('/catalog/menu'); ?>
<h3>Товары</h3>

<div class="btn-toolbar">
  <?php
  echo TbHtml::linkButton(
      'Добавить товар', array(
    'color' => TbHtml::BUTTON_COLOR_PRIMARY,
    'url' => array('/admin/catalog/product/create'),
      )
  );
  ?>
  <?php
//  echo TbHtml::beginFormTb(TbHtml::FORM_LAYOUT_INLINE
//      , '/admin/catalog/product/productUpload', 'post', array(
//    'class' => 'pull-right',
//    'enctype' => 'multipart/form-data',
//  ));
  ?>
  <?php
  echo TbHtml::fileField('files', '', array(
    'id' => 'priceupload',
    'style' => 'float:right',
    'data-url' => Yii::app()->createUrl('admin/catalog/product/priceUpload'), //'admin/catalog/priceUpload',
      )
  );
  echo TbHtml::label('Загрузка прайса', 'priceupload', array('style' => 'float:right; margin:6px'));
  ?>
  <?php
//  echo TbHtml::checkBox('upload_image', FALSE, array('id' => 'uploadImage',
//    'style' => 'vertical-align:baseline;margin-right:5px'
//  ));
//  echo TbHtml::label('Загружать изображения', 'upload_image');
  ?>
  <?php
//  echo TbHtml::endForm();
  ?>
</div>

<?php
$columns = array(
  array(
    'name' => 'name',
    'value' => 'CHtml::tag("span", array("title" => $data->name), mb_substr($data->name,0,55,"utf-8"))', //'mb_substr($data->name,0,40,"utf-8")',
    'type' => 'html',
  ),
  'article',
//    array(
//      'name' => 'brand_id',
//      'value' => '$data->brand->name',
//      'filter' => $model->getBrandOptions(),
//    ),
  array(
    'name' => 'remainder',
    'value' => '$data->remainder',
    'headerHtmlOptions' => array('style' => 'text-align: right; padding-right:27px'),
    'filterHtmlOptions' => array('style' => 'text-align: right'),
    'htmlOptions' => array('style' => 'text-align: right; padding-right:27px'),
  ),
  array(
    'header' => ' Цена  <span class="ruble" style="position:relative;display:inline-block"></span>',
    'value' => '$data->price',
    'headerHtmlOptions' => array('style' => 'text-align: right;'),
    'htmlOptions' => array('style' => 'text-align: right'),
  ),);
if (Yii::app()->params['mcurrency'])
  $columns = array_merge($columns, array(
    array(
      'header' => 'Цена ',
      'value' => '$data->price_tenge',
      'headerHtmlOptions' => array('class' => 'tenge', 'style' => 'text-align: right'),
      'htmlOptions' => array('style' => 'text-align: right'),
  )));
$columns = array_merge($columns, array(
  array(
    'name' => 'show_me',
    'value' => '$data->show_me ? "Да" : "Нет"',
    'filter' => array(0 => 'Нет', 1 => 'Да'),
    'htmlOptions' => array('style' => 'text-align: center'),
  ),
  array(
    'class' => 'bootstrap.widgets.TbButtonColumn',
    'template' => '{update}{delete}',
  ),
    ));
$this->widget('bootstrap.widgets.TbGridView', array(
  'id' => 'product-grid',
  'dataProvider' => $model->search(),
  'filter' => $model,
  'columns' => $columns,
    )
);
?>
<?php $this->endContent(); ?>
<?php
$this->widget('bootstrap.widgets.TbModal', array(
  'id' => 'productUploadModel',
  'header' => 'Загрузка товаров',
  'content' => TbHtml::animatedProgressBar(0, array('id' => 'uploadProgress')),
  'footer' => array(
//    TbHtml::button('Save Changes', array('data-dismiss' => 'modal', 'color' => TbHtml::BUTTON_COLOR_PRIMARY)),
    TbHtml::button('Отмена', array('id' => 'cancelUpload')),
  ),
));
?>
<?php
$cs = Yii::app()->clientScript;
//$cs->registerCoreScript('jquery.ui');
$cs->registerScriptFile('/js_plugins/jQueryFileUpload/js/vendor/jquery.ui.widget.js');
$cs->registerScriptFile('/js_plugins/jQueryFileUpload/js/jquery.iframe-transport.js');
$cs->registerScriptFile('/js_plugins/jQueryFileUpload/js/jquery.fileupload.js'); ?>
<script type="text/javascript">
  $(function () {
    $('#priceupload').fileupload({
      dataType: 'json',
      add: function (e, data){
        $(this).prop('disabled', true);
        data.submit();
      },
      done: function (e, data){
        alert('Прайс загружен!');
        $(this).prop('disabled', false);
      }
    });
    
    var i;
    var lines;
    function uploadData() {
      var n = 0;
      var postData = [];
      while (n < 20 && i < lines.length) {
        postData.push(lines[i]);
        n++;
        i++;
      }
      var uploadImage = $('#uploadImage').prop('checked');
      $.post('/admin/catalog/product/productUpload',
              {
                data: postData,
                uploadImage: uploadImage
              }, function (data) {
        if (data === 'ok') {
          uploadCallBack();
        } else {
          closeModal(data);
        }
      });
    }

    var uploadCallBack = function () {
      var percent = Math.round(i / lines.length * 100);
      var width = percent + '%';
      $('#uploadProgress .bar').css('width', width);
      if (i < lines.length)
        uploadData();
      else
        closeModal();
    }

    $('#fileToUpload').change(function (event) {
      var input = event.target;

      var reader = new FileReader();
      reader.onload = function (event) {
        var reader = event.target;
        var dataURL = reader.result;
        lines = dataURL.split(/\r\n/g);
        i = 1;
        $('#productUploadModel').modal('show');
        uploadData()
      }
      reader.readAsText(input.files[0]);
    });

//    function uploadProgress() {
//      $.get('/admin/catalog/product/uploadProgress', function (data) {
//        $('#uploadProgress .bar').css('width', data);
//      });
//    }

    function closeModal(data) {
      i = lines.length;
      $('#productUploadModel').modal('hide');
      $('#uploadProgress .bar').css('width', '0%');
      if (data)
        alert('Ошибка загрузки: ' + data);
      jQuery('#product-grid').yiiGridView('update', {
        type: 'POST',
        url: jQuery(this).attr('href')
      });
    }

    $('#cancelUpload, #productUploadModel .close').click(function () {
      closeModal();
    });
  });

</script>