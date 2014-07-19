<?php
$this->pageTitle = Yii::app()->name . ' - ' . 'Восстановить пароль';
//$this->breadcrumbs = array(
//  UserModule::t("Login") => array('/user/login'),
//  UserModule::t("Restore"),
//);
?>

<div class="container" id="page">

<?php $this->renderPartial('//site/_topblock'); ?>

  <h1 style="margin-top: 2em" class="cufon blue bold">Восстановить пароль</h1>

    <?php if (Yii::app()->user->hasFlash('recoveryMessage')): ?>
    <div class="success">
    <?php echo Yii::app()->user->getFlash('recoveryMessage'); ?>
    </div>
<?php else: ?>

    <div class="form">
      <?php echo CHtml::beginForm(); ?>

  <?php echo CHtml::errorSummary($form); ?>

      <div class="row">
        <?php echo CHtml::activeLabel($form, 'login_or_email'); ?>
  <?php echo CHtml::activeTextField($form, 'login_or_email') ?>
        <p class="hint"><?php echo UserModule::t("Please enter your login or email addres."); ?></p>
      </div>

      <div class="row submit">
  <?php echo CHtml::submitButton(UserModule::t("Restore")); ?>
      </div>

    <?php echo CHtml::endForm(); ?>
    </div><!-- form -->
<?php endif; ?>
</div>