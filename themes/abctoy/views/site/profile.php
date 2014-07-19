<?php
/* @var $profile CustomerProfile */
/* @var $order Order */
/* @var $new_passw NewPassword */
/* @var $child Child */
?>
<?php
$this->pageTitle = Yii::app()->name . ' - Личный кабинет';
$cs = Yii::app()->getClientScript();
$coreScriptUrl = $cs->getCoreScriptUrl();
$cs->registerScriptFile($coreScriptUrl . '/jui/js/jquery-ui-i18n.min.js', CClientScript::POS_HEAD);
?>
<div class="container" id="page" style="margin-top: 0">
  <?php $this->renderPartial('_profileTopBlock', array('profile' => $profile)); ?>
  <div style="text-align: right; margin: 40px 0 10px"><a href="/logout">Выйти</a></div>
  <div class="inline-blocks">
    <div style="vertical-align: top">
      <?php $form = $this->beginWidget('CActiveForm', array('id' => 'profile')) ?>
      <div class="cufon bold" style="font-size: 14pt; width: 400px">Контактная информация</div>
      <!--<div style="height: 20px; margin: 10px 0">-->
      <?php
      foreach (Yii::app()->user->flashes as $key => $flash) {
//        if (Yii::app()->user->hasFlash('newPassw')) { 
        ?>
        <div style="height: 20px; margin: 10px 0"><span class="cufon red bold" style="font-size: 12pt; margin-bottom: 10px"><?php echo $flash; ?></span></div>
      <?php } ?>
      <!--</div>-->
      <div style="display: inline-block">
        <div><?php
          echo CHtml::label('Ваше имя и фамилия<span class="red">*</span>'
              , 'CustomerProfile_fio', array(
            'class' => 'cufon gray bold',
            'style' => 'font-size: 12pt'
          ));
          ?>
        </div>
        <div style="margin-bottom: 1em"><?php
          echo $form->textField($profile, 'fio'
              , array('class' => 'input-text'));
          ?>
          <?php echo CHtml::error($profile, 'fio', array('style' => 'font-size:10pt', 'class' => 'red')); ?>
        </div>
        <div><?php
          echo CHtml::label('E-mail<span class="red">*</span>', 'CustomerProfile_email'
              , array(
            'class' => 'cufon gray bold',
            'style' => 'font-size: 12pt'
          ));
          ?>
        </div>
        <div style="margin-bottom: 1em"><?php
          echo CHtml::activeEmailField($profile, 'email'
              , array('class' => 'input-text'));
          ?>
          <?php echo CHtml::error($profile, 'email', array('style' => 'font-size:10pt', 'class' => 'red')); ?>
        </div>
        <div><?php
          echo CHtml::activeLabel($new_passw
              , 'passw1', array(
            'class' => 'cufon gray bold',
            'style' => 'font-size: 12pt'
          ));
          ?>
        </div>
        <div style="margin-bottom: 1em"><?php
          echo CHtml::activePasswordField($new_passw, 'passw1'
              , array('class' => 'input-text'));
          ?>
          <?php echo CHtml::error($new_passw, 'passw1', array('style' => 'font-size:10pt', 'class' => 'red')); ?>
        </div>
        <div><?php
          echo CHtml::activeLabel($new_passw
              , 'passw2', array(
            'class' => 'cufon gray bold',
            'style' => 'font-size: 12pt'
          ));
          ?>
        </div>
        <div style="margin-bottom: 1em"><?php
          echo CHtml::activePasswordField($new_passw, 'passw2'
              , array('class' => 'input-text'));
          ?>
          <?php echo CHtml::error($new_passw, 'passw2', array('style' => 'font-size:10pt', 'class' => 'red')); ?>
        </div>
        <div><?php
          echo CHtml::label('Телефон<span class="red">*</span>', 'CustomerProfile_phone'
              , array(
            'class' => 'cufon gray bold',
            'style' => 'font-size: 12pt'
          ));
          ?>
        </div>
        <div style="margin-bottom: 1em"><?php
          echo CHtml::activeTelField($profile, 'phone'
              , array('class' => 'input-text'));
          ?>
          <?php echo CHtml::error($profile, 'phone', array('style' => 'font-size:10pt', 'class' => 'red')); ?>
        </div>
        <div><?php
          echo CHtml::label('Город<span class="red">*</span>', 'CustomerProfile_city'
              , array(
            'class' => 'cufon gray bold',
            'style' => 'font-size: 12pt'
          ));
          ?>
        </div>
        <div style="margin-bottom: 1em"><?php
          $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
            'id' => 'cart-city',
            'model' => $profile,
            'attribute' => 'city',
            'sourceUrl' => '/site/suggestcity',
            'htmlOptions' => array('class' => 'input-text')
          ));
          ?>
          <?php echo CHtml::error($profile, 'city', array('style' => 'font-size:10pt', 'class' => 'red')); ?>
        </div>
        <div><?php
          echo CHtml::activeLabel($profile, 'address'
              , array(
            'class' => 'cufon gray bold',
            'style' => 'font-size: 12pt'
          ));
          ?>
        </div>
        <div style="margin-bottom: 1em"><?php
          echo CHtml::activeTextField($profile, 'address'
              , array('class' => 'input-text'));
          ?></div>
        <div style="margin: 20px auto; width: 168px">
          <a class="submit" href="#">
            <div class="greenbutton inline-blocks">
              <div class="left"></div>
              <div class="center">ИЗМЕНИТЬ</div>
              <div class="right"></div>
            </div>
          </a>
        </div>
      </div>
      <?php $this->endWidget(); ?>
    </div>
    <div style="vertical-align: top">
      <div class="cufon green bold" style="font-size: 22pt; position: relative;
           background: white; width: 90px; text-align: center; left: 50px; 
           z-index: 10">Дети</div>
      <div id="children-block" style="border: #99cc33 solid 4px; border-radius: 4px; position: relative; 
           width: 538px; min-height: 250px; top: -15px" class="inline-blocks">
           <?php $this->renderPartial('_child', array('child' => $child)); ?>
      </div>
      <div class="cufon bold" style="font-size: 16pt; margin: 20px 0 0px">Мои заказы</div>
      <?php
      $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'profile-grid-order',
        'dataProvider' => $order,
        'template' => '{items}{summary}{pager}',
        'columns' => array(
          array(
            'name' => '№',
            'value' => '$data->id',
          ),
          array(
            'name' => 'time',
            'value' => 'Yii::app()->dateFormatter->format("dd.MM.yyyy", $data->time)'
          ),
          array(
            'name' => 'summ',
            'value' => 'number_format($data->summ - $data->couponDiscount + $data->delivery_summ, 0, ".", " ") . " руб"'
          ),
          array(
            'name' => 'status_id',
            'type' => 'html',
            'value' => '$data->status_id==3 && $data->payment->type_id!=0 && $data->productSumm>$data->paySumm ? CHtml::link($data->status, Yii::app()->createUrl("pay/order", array("id"=>$data->id))) : $data->status',
          ),
        )
      ));
      ?>
    </div>
  </div>
</div>
<script type="text/javascript">
  function showAddForm() {
    $('#add-child-link').css('display', 'none');
    $('#child-add').css('display', 'inline-block');
    $('input[name="Child[gender_id]"]:checked').attr('checked', false);
    $('#Child_birthday').val('');
    $('#Child_name').val('');
    $('#error-block').html('');
  }

  function hideAddForm() {
    $('#add-child-link').css('display', 'inline-block');
    $('#child-add').css('display', 'none');
  }

  function setChildrenView() {
    $('.icon-boy, .icon-girl').parent().find('.child-update').css('display', 'none');
    $('.icon-boy, .icon-girl').parent().find('.child-view').css('display', 'inherit');
  }

  $('#children-block').on('click', '#add-child-link', function() {
    setChildrenView();
    showAddForm();
  });

  $('#children-block').on('click', '.icon-add-child', function() {
    if ($('#child-add').css('display') === 'none') {
      setChildrenView();
      showAddForm();
    } else {
      hideAddForm();
    }
  });

  $('#children-block').on('click', '#save-child', function() {
    var gender = $('input[name="Child[gender_id]"]:checked').val();
    var birthday = $('#Child_birthday').val();
    var name = $('#Child_name').val();
    $.post('/updatechild', {
      id: 0,
      gender: gender,
      birthday: birthday,
      name: name
    }, function(data) {
      var result = JSON && JSON.parse(data) || $.parseJSON(data);
      if (result.result) {
        $(result.html).insertBefore($('#child-add').parent());
        hideAddForm();
      } else {
        $('#child-add').html(result.html);
      }
    });
  });

  $('#children-block').on('focus', '.date', function() {
    $(this).datepicker($.extend({
      changeMonth: true,
      changeYear: true,
      yearRange: "-30:+00"
    }, $.datepicker.regional['ru']));
  });

  $('#children-block').on('click', '.icon-boy, .icon-girl', function() {
    var view = $(this).parent().find('.child-view');
    if (view.css('display') === 'none') {
      view.css('display', 'inherit');
      $(this).parent().find('.child-update').css('display', 'none');
      $(this).parent().find('.child-error').empty();
    } else {
      hideAddForm();
      setChildrenView();
      view.css('display', 'none');
      $(this).parent().find('.child-update').css('display', 'inherit');
    }
  });

  $('#children-block').on('click', '.delete', function() {
    var parent = $(this).parents('.child-block')[0];
    var id = /[0-9]+/.exec(parent.id)[0];
    $.post('/delChild', {
      id: id
    }, function(data) {
      if (data)
        $(parent).remove();
    });
  });

  $('#children-block').on('click', '.update', function() {
    var parent = $(this).parents('.child-block')[0];
    var id = /[0-9]+/.exec(parent.id)[0];
    var name = $(parent).find('.name').val();
    var birthday = $(parent).find('.date').val();
    $.post('/updatechild', {
      id: id,
      birthday: birthday,
      name: name
    }, function(data) {
      var result = JSON && JSON.parse(data) || $.parseJSON(data);
      $(result.html).replaceAll(parent);
      if (!result.result) {
        $('#child-' + id).find('.child-view').css('display', 'none');
        $('#child-' + id).find('.child-update').css('display', 'inherit');
      }
    });
  });
</script>