<?php
/* @var $customer_profile CustomerProfile */
/* @var $user User */
/* @var $profile Profile */
/* @var $order Order */
/* @var $new_passw NewPassword */

Yii::import('application.modules.payments.models.Currency');

$this->pageTitle = Yii::app()->name . ' - Личный кабинет';
?>
<div class="container" id="page">
  <?php $this->renderPartial('//site/_topblock', array('profile' => $profile)); ?>
  <?php $this->renderPartial('//site/_mainmenu'); ?>
  <?php
  $form = $this->beginWidget('CActiveForm', array('id' => 'profile'));
  /* @var $form CActiveForm */
  ?>
  <fieldset>
    <legend><span class="bold blue page-title">Контактная информация</span></legend>
    <div style="margin-bottom: 20px"><?php echo $form->errorSummary(array($profile, $customer_profile, $user)); ?></div>
    <?php
    foreach (Yii::app()->user->flashes as $key => $flash) {
      ?>
      <div style="height: 20px; margin: 10px 0">
        <span class="red" style="margin-bottom: 10px"><?php echo $flash; ?></span>
      </div>
    <?php } ?>
    <!--</div>-->
    <div class="inline-blocks" style="margin-bottom: 20px">
      <div style="width: 250px">
        <div><?php echo $form->labelEx($profile, 'first_name'); ?></div>
        <div><?php echo $form->textField($profile, 'first_name', array('style' => 'width:230px')); ?></div>
        <?php echo $form->error($profile, 'first_name', array('class' => 'red')); ?>
      </div>
      <div style="width: 250px">
        <div><?php echo $form->labelEx($profile, 'last_name'); ?></div>
        <div><?php echo $form->textField($profile, 'last_name', array('style' => 'width:230px')); ?></div>
        <?php echo $form->error($profile, 'last_name', array('class' => 'red')); ?>
      </div>
      <div style="width: 170px">
        <div><?php echo $form->labelEx($user, 'email'); ?></div>
        <div><?php echo $form->emailField($user, 'email', array('style' => 'width:150px')); ?></div>
        <?php echo $form->error($user, 'email', array('class' => 'red')); ?>
      </div>
      <div style="width: 160px">
        <div><?php echo $form->labelEx($customer_profile, 'phone'); ?></div>
        <div><?php echo $form->telField($customer_profile, 'phone', array('style' => 'width:150px')); ?></div>
        <?php echo $form->error($customer_profile, 'phone', array('class' => 'red')); ?>
      </div>
    </div>
    <div class="inline-blocks">
      <div style="width: 115px">
        <div><?php echo $form->labelEx($customer_profile, 'country_code'); ?></div>
        <div>
          <?php
          echo $form->dropDownList($customer_profile, 'country_code', ProfileController::getCountries()
              , array('style' => 'width:100px'));
          ?>
        </div>
        <?php echo $form->error($customer_profile, 'country_code', array('class' => 'red')); ?>
      </div>
      <div style="width: 130px">
        <div><?php echo $form->labelEx($customer_profile, 'post_code'); ?></div>
        <div>
          <?php echo $form->textField($customer_profile, 'post_code', array('style' => 'width:110px')); ?>
        </div>
        <?php echo $form->error($customer_profile, 'post_code', array('class' => 'red')); ?>
      </div>
      <div style="width: 250px">
        <div><?php echo $form->labelEx($customer_profile, 'city'); ?></div>
        <div><?php
          $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
            'id' => 'cart-city',
            'model' => $customer_profile,
            'attribute' => 'city',
//            'sourceUrl' => '/site/suggestcity',
            'source' => new CJavaScriptExpression('function (request, response){citySuggest(request, response);}'),
            'htmlOptions' => array('class' => 'input-text')
          ));
          ?>
        </div>
        <?php echo $form->error($customer_profile, 'city', array('class' => 'red')); ?>
      </div>
      <div style="width: 350px">
        <div><?php echo $form->labelEx($customer_profile, 'address'); ?></div>
        <div><?php echo $form->textField($customer_profile, 'address', array('style' => 'width:330px')); ?></div>
        <?php echo $form->error($customer_profile, 'address', array('class' => 'red')); ?>
      </div>
      <div style="margin: 20px 0 30px"><?php echo CHtml::submitButton('Сохранить'); ?></div>
    </div>
  </fieldset>
  <?php $this->endWidget(); ?>
  <fieldset>
    <legend><span class="page-title blue bold">Изменение пароля</span></legend>
    <div class="inline-blocks">
      <div>
        <div>
          <?php echo $form->labelEx($new_passw, 'passw1'); ?>
        </div>
        <div>
          <?php echo $form->passwordField($new_passw, 'passw1'); ?>
        </div>
      </div>
      <div style="margin: 0 20px">
        <div>
          <?php echo $form->labelEx($new_passw, 'passw2'); ?>
        </div>
        <div>
          <?php echo $form->passwordField($new_passw, 'passw2'); ?>
        </div>
      </div>
      <div style="vertical-align: bottom">
        <?php echo CHtml::button('Изменить', array('style' => 'width:100px', 'id' => 'change-passw', 'disabled' => true)); ?>
      </div>
    </div>
    <div id="passw-err" style="font-size:10pt; height: 16pt" class="red"></div>
  </fieldset>
  <fieldset>
    <legend><span class="page-title blue bold">Заказы</span></legend>
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
          'value' => 'number_format($data->summ - $data->couponSumm + $data->delivery_summ, 2, ".", " ")." ".$data->currency->class',
          'type' => 'html',
        ),
        array(
          'name' => 'status_id',
          'type' => 'html',
          'value' => 'CHtml::link($data->status, Yii::app()->createUrl("pay/order", array("id"=>$data->id)))',
        ),
      )
    ));
    ?>
  </fieldset>
</div>
<?php $this->renderPartial('//site/_footer'); ?>
<script type="text/javascript">

  var change_passw_bt = $('#change-passw');
  var passw1 = $('#NewPassword_passw1');
  var passw2 = $('#NewPassword_passw2');
  var passw_err_msg = $('#passw-err');

  $('input[type="password"]').typing({
    stop: function () {
      var p1 = passw1.val();
      var p2 = passw2.val();
      if (p1.length > 5)
        if (p1 === p2) {
          passw_err_msg.html('');
          change_passw_bt.prop('disabled', false);
        }
        else {
          change_passw_bt.prop('disabled', true);
          passw_err_msg.html('Пароль и подтверждение не совпадают');
        }
      else {
        change_passw_bt.prop('disabled', true);
        passw_err_msg.html('Длина пароля должна быть не менее 6 символов');
      }
    }
  });

  change_passw_bt.click(function () {
    change_passw_bt.prop('disabled', true);
    $.post('/profile/changepassw', {
      passw1: passw1.val(),
      passw2: passw2.val()
    }, function (data) {
      var result = $.parseJSON(data);
      passw_err_msg.html(result.msg);
      if (result.result) {
        passw1.val('');
        passw2.val('');
      } else
        change_passw_bt.prop('disabled', false);
    });
  });

  var country_code = $('#CustomerProfile_country_code');
  function citySuggest(request, response) {
    $.get("/site/suggestcity",
            {country: country_code.val(), term: request.term},
    function (data) {
      var result = $.parseJSON(data);
      response(result);
    });
  }

</script>