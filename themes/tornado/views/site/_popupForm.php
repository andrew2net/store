<?php
/* @var $this SiteController */
/* @var $popup_form PopupForm */

/* @var $form CActiveForm */
$form = $this->beginWidget('CActiveForm', ['id' => 'popupForm']);
?>
<div style="margin: 10px 0">
    <div style="margin-bottom: 10px">
        <?php echo $form->label($popup_form, 'email', ['style' => 'font-size: 14pt']); ?>
    </div>
    <?php
    echo $form->emailField($popup_form, 'email', array(
      'placeholder' => 'Укажите Вашу электронную почту',
      'class' => 'input-text',
      'style' => 'width: 298px; font-size: 10pt; padding: 6px',
    ));
    echo $form->error($popup_form, 'email', ['class' => 'red', 'style' => 'font-size: 9pt; margin-top: 5px']);
    ?>
</div>
<div style="padding: 4px; margin-top: 10px">
    <?php
    $id = 'PopupForm_accept';
    echo $form->checkBox($popup_form, 'accept');
    echo $form->label($popup_form, 'accept', ['style' => 'font-size:10pt']);
    ?>
</div>
<?php
echo $form->error($popup_form, 'accept', ['class' => 'red', 'style' => 'font-size: 9pt; margin-top: 5px']);
$this->endWidget();
?>