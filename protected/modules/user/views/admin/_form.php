<?php
/* @var $customer_profile CustomerProfile */
/* @var $profile Profile */

Yii::import('application.modules.catalog.models.Price');
?>
<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
      'id' => 'user-form',
      'enableAjaxValidation' => true,
      'htmlOptions' => array('enctype' => 'multipart/form-data', 'autocomplete' => 'off'),
    ));
    /* @var $form CActiveForm */
    ?>

    <p class="note"><?php echo UserModule::t('Fields with <span class="required">*</span> are required.'); ?></p>

    <?php echo $form->errorSummary(array($model, $profile, $customer_profile)); ?>

    <div class="inline-blocks">
        <div style="vertical-align: top">
            <div class="row">
                <?php echo $form->labelEx($model, 'username'); ?>
                <?php echo $form->textField($model, 'username', array('size' => 20, 'maxlength' => 20)); ?>
                <?php echo $form->error($model, 'username'); ?>
            </div>

            <div class="row">
                <?php echo $form->labelEx($model, 'password'); ?>
                <?php echo $form->passwordField($model, 'password', array('size' => 60, 'maxlength' => 128)); ?>
                <?php echo $form->error($model, 'password'); ?>
            </div>

            <div class="row">
                <?php echo $form->labelEx($model, 'email'); ?>
                <?php echo $form->textField($model, 'email', array('size' => 60, 'maxlength' => 128)); ?>
                <?php echo $form->error($model, 'email'); ?>
            </div>

            <div class="row">
                <?php echo $form->labelEx($model, 'superuser'); ?>
                <?php echo $form->dropDownList($model, 'superuser', User::itemAlias('AdminStatus')); ?>
                <?php echo $form->error($model, 'superuser'); ?>
            </div>

            <div class="row">
                <?php echo $form->labelEx($model, 'status'); ?>
                <?php echo $form->dropDownList($model, 'status', User::itemAlias('UserStatus')); ?>
                <?php echo $form->error($model, 'status'); ?>
            </div>
        </div>
        <div style="vertical-align: top">
            <?php
            $profileFields = $profile->getFields();
            if ($profileFields) {
              foreach ($profileFields as $field) {
                ?>
                <div class="row">
                    <?php echo $form->labelEx($profile, $field->varname); ?>
                    <?php
                    if ($widgetEdit = $field->widgetEdit($profile)) {
                      echo $widgetEdit;
                    } elseif ($field->range) {
                      echo $form->dropDownList($profile, $field->varname, Profile::range($field->range), array('prompt' => 'Нет'));
                    } elseif ($field->field_type == "TEXT") {
                      echo CHtml::activeTextArea($profile, $field->varname, array('rows' => 6, 'cols' => 50));
                    } elseif ($field->field_type == 'BOOL') {
                      echo $form->checkBox($profile, $field->varname);
                    } else {
                      echo $form->textField($profile, $field->varname, array('size' => 60, 'maxlength' => (($field->field_size) ? $field->field_size : 255)));
                    }
                    ?>
                    <?php echo $form->error($profile, $field->varname); ?>
                </div>
                <?php
              }
            }
            ?>
        </div>
        <div style="vertical-align: top">
            <div class="row">
                <?php echo $form->labelEx($customer_profile, 'entity_id'); ?>
                <?php
                echo $form->dropDownList($customer_profile, 'entity_id'
                  , $customer_profile->getEntities());
                ?>
                <?php echo $form->error($customer_profile, 'entity_id'); ?>
            </div>
            <div class="row">
                <?php echo $form->labelEx($customer_profile, 'price_id'); ?>
                <?php
                echo $form->dropDownList($customer_profile, 'price_id'
                  , CHtml::listData(Price::model()->findAll(), 'id', 'name')
                  , array('prompt' => 'Прайс не выбран'));
                ?>
                <?php echo $form->error($customer_profile, 'price_id'); ?>
            </div>
        </div>
    </div>
    <div class="row buttons">
        <?php
        echo TbHtml::formActions(array(
          TbHtml::linkButton('Закрыть', array('url' => '/admin/user')),
          TBHtml::submitButton('Сохранить', array('color' => TbHtml::BUTTON_COLOR_PRIMARY)),
        ));
        ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->