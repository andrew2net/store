<?php
/* @var $this SiteController */
/* @var $children Child[] */
/* @var $popup_form PopupForm */
?>
<div id="children<?php echo (isset($suff) ? '-' . $suff : ''); ?>" class="children">
  <?php foreach ($children as $key => $child) { ?>
    <div id="child-<?php echo (isset($suff) ? $suff : '') . $key; ?>" class="inline-blocks child">
      <div style="width: 20px; vertical-align: initial">
        <span class="icon-remove-child-small" title="Удалить" style="display: <?php echo $key == 0 ? 'none' : 'inherit'; ?>"></span>
      </div>
      <div>
        <?php
        if (isset($suff))
          $key = $suff . $key;
        echo CHtml::activeTextField($child, "[$key]name", array(
          'class' => 'input-text name',
          'style' => 'width:120px',
        ));
        ?>
      </div>
      <div>
        <?php
        echo CHtml::activeTextField($child, "[$key]birthday", array(
          'class' => 'input-text date',
          'style' => 'width:100px',
        ));
        ?>
      </div>
      <div>
        <?php
        echo CHtml::activeRadioButtonList($child, "[$key]gender_id", $child->genders, array(
          'separator' => '',
        ));
        ?>
      </div>
    </div>
  <?php } ?>
</div>
<div style="margin: 10px 0 10px" class="add-block">
  <span class="icon-add-child-small add-child" title="Добавить"></span>
  <span class="blue add-child" title="Добавить" style="text-decoration: underline;
        text-decoration-style: dashed; -moz-text-decoration-style: dashed;
        cursor: pointer; position: relative">У меня есть еще ребенок</span>
</div>
<div style="padding: 4px; margin-top: 10px">
  <?php
  if (isset($suff)) {
    $id = 'PopupForm_accept' . $suff;
    $name = 'PopupForm' . $suff . '[accept]';
  }
  else {
    $id = 'PopupForm_accept';
    $name = 'PopupForm[accept]';
  }
  echo CHtml::activeCheckBox($popup_form, 'accept', array('name' => $name, 'id' => $id));
  echo CHtml::label('Я ознакомлен с <a style="color:rgb(51, 153, 204);text-decoration-style:dashed;-moz-text-decoration-style:dashed" href="/site/page/url/offer" target="_blank">офертой</a> и принимю ее условия, а так же соглашаюсь', $id);
  ?><br>получать новости</div>
<div style="margin: 10px 0">
  <?php
  if (isset($suff)) {
    $id = 'PopupForm_email' . $suff;
    $name = 'PopupForm' . $suff . '[email]';
  }
  else {
    $id = 'PopupForm_email';
    $name = 'PopupForm[accept]';
  }
  echo CHtml::activeEmailField($popup_form, 'email', array(
    'id' => $id,
    'name' => $name,
    'placeholder' => 'Электронная почта',
    'class' => 'input-text',
  ));
  ?>
</div>

