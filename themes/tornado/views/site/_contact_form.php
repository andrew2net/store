<?php
/* @var $form CActiveForm */
/* @var $profile Profile */
/* @var $customer_profile CustomerProfile */
/* @var $user User */
/* @var $order Order */
?>
<fieldset>
  <legend><span class="bold blue page-title">Контактная информация</span></legend>
  <div style="margin-bottom: 10px"><?php echo $form->errorSummary(array($profile, $customer_profile, $user)); ?></div>
  <?php
  foreach (Yii::app()->user->flashes as $key => $flash) {
    ?>
    <div style="height: 20px; margin-bottom: 10px">
      <span class="red" style="margin-bottom: 10px"><?php echo $flash; ?></span>
    </div>
  <?php } ?>
  <div style="margin-bottom: 20px"><?php
    echo $form->radioButtonList($customer_profile, 'entity_id', CustomerProfile::getEntities()
        , array('separator' => ' ', 'id' => 'entity_id'));
    ?></div>
  <div class="legal-entity" style="display: <?php echo ($customer_profile->entity_id == 1 ? 'inherit' : 'none'); ?>">
    <div class="inline-blocks" style="margin-bottom: 20px">
      <div style="width: 70px">
        <?php
        $field = ProfileField::model()->findByAttributes(array('varname' => 'legal_form'));
        echo $form->labelEx($profile, $field->varname, array(
          'style' => 'display:block; width:65px',
          'title' => 'Организационно-правовая форма',
        ));
        echo $form->dropDownList($profile, $field->varname, Profile::range($field->range), array('style'=>'width:65px', 'title' => 'Организационно-правовая форма'))
        ?>
      </div>
      <div style="width: 175px">
        <?php
        $field = ProfileField::model()->findByAttributes(array('varname' => 'inn'));
        echo $form->labelEx($profile, $field->varname, array('style' => 'display:block'));
        echo $form->telField($profile, $field->varname, array('style' => 'width:155px'));
        echo $form->error($profile, $field->varname);
        ?>
      </div>
      <div style="width: 250px">
        <?php
        $field = ProfileField::model()->findByAttributes(array('varname' => 'entity_name'));
        echo $form->labelEx($profile, $field->varname, array('style' => 'display:block'));
        echo $form->telField($profile, $field->varname, array('style' => 'width:230px'));
        echo $form->error($profile, $field->varname);
        ?>
      </div>
      <div style="width: 345px">
        <?php
        $field = ProfileField::model()->findByAttributes(array('varname' => 'legal_address'));
        echo $form->labelEx($profile, $field->varname, array('style' => 'display:block'));
        echo $form->telField($profile, $field->varname, array('style' => 'width:325px'));
        echo $form->error($profile, $field->varname);
        ?>
      </div>
    </div>
    <div class="bold">Контактное лицо</div>
  </div>
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
  <div class="inline-blocks" style="margin-bottom: 20px">
    <!--      <div style="width: 250px">
            <div><?php // echo $form->labelEx($customer_profile, 'post_code');                        ?></div>
            <div>
    <?php // echo $form->textField($customer_profile, 'post_code', array('style' => 'width:120px'));   ?>
            </div>
    <?php // echo $form->error($customer_profile, 'post_code', array('class' => 'red'));   ?>
          </div>-->
    <div style="width: 250px">
      <div><?php echo $form->labelEx($customer_profile, 'city'); ?></div>
      <div><?php
        $city_options = array('empty' => 'Выберите населенный пункт');
        $other_options = array('class' => 'input-text');
        if ($customer_profile->other_city)
          $city_options['disabled'] = TRUE;
        else
          $other_options['disabled'] = TRUE;

        echo $form->dropDownList($customer_profile, 'city_l'
            , CHtml::listData(NrjLocation::model()->findAll(array('order' => 'name')), 'name', 'name')
            , $city_options);
        ?></div>
    </div>
    <div style="width: 425px">
      <div><?php echo $form->labelEx($customer_profile, 'address'); ?></div>
      <div><?php echo $form->textField($customer_profile, 'address', array('style' => 'width:405px')); ?></div>
      <?php echo $form->error($customer_profile, 'address', array('class' => 'red')); ?>
    </div>
  </div>
  <div class="inline-blocks" style="margin-bottom: 10px">
    <div>
      <?php echo $form->checkBox($customer_profile, 'other_city'); ?>
      <?php echo $form->label($customer_profile, 'other_city'); ?>
    </div>
  </div>
  <div class="inline-blocks" style="margin-bottom: 20px">
    <div style="width: 250px">
      <div><?php
        $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
          'id' => 'cart-city',
          'model' => $customer_profile,
          'attribute' => 'city',
          'sourceUrl' => '/site/suggestcity',
          'htmlOptions' => $other_options,
        ));
        ?>
      </div>
      <?php echo $form->error($customer_profile, 'city', array('style' => 'width:230px', 'class' => 'red')); ?>
    </div>
  </div>
  <?php if (isset($order)) { ?>
    <div style="margin-bottom: 1em">
      <?php echo $form->labelEx($order, 'description'); ?>
      <div>
        <?php
        echo $form->textArea($order, 'description', array(
//          'class' => 'input-text',
//        'cols' => 81,
          'rows' => 4,
          'style' => 'width:558px'
        ));
        ?>
      </div>
    </div>
    <p class="gray" style="font-size: 10pt"><span class="red">*</span> - поля обязательные для заполнения</p>
    <?php
  }
  else {
    ?>
    <div style="margin: 15px 0"><?php echo CHtml::submitButton('Сохранить'); ?></div>
  <?php } ?>
</fieldset>
<script type="text/javascript">
  $(document).ready(function() {
    var entity = $('#CustomerProfile_entity_id');
    var legalEntity = $('.legal-entity');
    var cartSubmit = $('#cart-submit');

    $('#CustomerProfile_entity_id > input').change(function() {
      var id = entity.find('input:checked').val();
      switch (id) {
        case '1':
          legalEntity.show();
          break;
        default:
          legalEntity.hide();
      }

    });
    var city = $('#CustomerProfile_city_l');
    var other = $('#cart-city');
    $('#CustomerProfile_other_city').change(function() {
      cartSubmit.hide();
      if ($('input#CustomerProfile_other_city:checkbox:checked').val()) {
        city.prop('disabled', true);
        other.prop('disabled', false);
        other.focus();
      } else {
        city.prop('disabled', false);
        other.prop('disabled', true);
        city.focus();
      }
    });
  });
</script>