<?php
/* @var $giftSelection GiftSelection */
/* @var $form CActiveForm */
?>
<div>
  <?php
  $form = $this->beginWidget('CActiveForm', array(
    'id' => 'giftSelect',
    'action' => 'sort',
    'method' => 'get',
  ));
  ?>
  <div class="blue cufon bold" style="font-size: 20pt; position: relative;
       margin: 0 0 -15px 20px; background: white; width: 200px; z-index: 1;
       padding: 0 8px">Подбор товара</div>
  <div class="inline-blocks" style="border: #3399cc solid 4px; border-radius: 4px; position: relative">
    <div style="margin: 20px; vertical-align: inherit">
      <?php
      echo $form->label($giftSelection, 'gender', array(
        'class' => 'bold',
        'style' => 'font-size: 12pt; line-height: 2.5; display: block; margin-bottom: 7px'
      ));
      ?>
      <div class="radio">
        <?php
        echo $form->radioButtonList($giftSelection, 'gender'
            , Product::model()->genders, array(
          'separator' => '',
          'style' => 'color: #3399cc'
        ));
        ?>
      </div>
      <?php
      echo $form->label($giftSelection, 'category', array(
        'class' => 'bold',
        'style' => 'font-size: 12pt; line-height: 2.5; display: block; margin-top: 10px'
      ));
      ?>
      <div class="styled-select">
        <?php
        echo $form->dropDownList($giftSelection, 'category', CHtml::listData($groups, 'id', 'name')
            , array(
          'prompt' => 'Все категории',
        ));
        ?>
      </div>
    </div>
    <div style="top: -10px; position: relative; height: 150px; vertical-align: inherit">
      <?php
      echo $form->label($giftSelection, 'ageFrom', array(
        'class' => 'bold',
        'style' => 'font-size: 12pt; line-height: 2.5'
      ));
      ?><br>
      <div style="
           /*background: whitesmoke; border-radius: 15px; box-shadow: 0px 0px 3px 0px #999999 inset;*/ 
           padding: 10px 10px;">
           <?php
           echo $form->hiddenField($giftSelection, 'ageFrom');
           echo $form->hiddenField($giftSelection, 'ageTo');
           ?>
           <?php
           $this->widget('zii.widgets.jui.CJuiSlider', array(
             'id' => 'age_slider',
             'options' => array(
               'animate' => 'slow',
               'range' => TRUE,
               'min' => 0,
               'max' => 10,
               'values' => "js:[{$giftSelection->ageFrom}, {$giftSelection->ageTo}]",
               'create' => 'js:function(event, ui){sliderIniTooltip(this);}',
               'slide' => 'js:function(event, ui){sliderMoveTooltip(ui); $("#GiftSelection_ageFrom").val(ui.values[0]); $("#GiftSelection_ageTo").val(ui.values[1]);}',
             ),
           ));
           ?>
      </div>
      <?php echo $form->checkBox($giftSelection, 'availableOnly'); ?>
      <?php
      echo $form->label($giftSelection, 'availableOnly', array(
        'style' => 'line-height: 9'
      ));
      ?>
    </div>
    <div style="margin: 20px 0 0 50px; position: absolute; height: 150px; vertical-align: inherit">
      <?php
      echo $form->label($giftSelection, 'priceFrom', array(
        'class' => 'bold',
        'style' => 'font-size: 12pt; line-height: 2.5; display: block'
      ));
      ?>
      <div style="
           /*background: whitesmoke; border-radius: 15px; box-shadow: 0px 0px 3px 0px #999999 inset;*/ 
           padding: 10px 10px;">
           <?php
           echo $form->hiddenField($giftSelection, 'priceFrom');
           echo $form->hiddenField($giftSelection, 'priceTo');
           ?>
           <?php
           $this->widget('zii.widgets.jui.CJuiSlider', array(
             'id' => 'price_slider',
             'options' => array(
               'animate' => 'slow',
               'range' => TRUE,
               'min' => 0,
               'max' => 5000,
               'step' => 50,
               'values' => "js:[{$giftSelection->priceFrom}, {$giftSelection->priceTo}]",
               'create' => 'js:function(event, ui){sliderIniTooltip(this);}',
               'slide' => 'js:function(event, ui){sliderMoveTooltip(ui); $("#GiftSelection_priceFrom").val(ui.values[0]); $("#GiftSelection_priceTo").val(ui.values[1]);}',
             ),
           ));
           ?>
      </div>
      <div style="position: relative; top: 42px; right: -130px">
        <a id="aSubmit" href="#">
          <div class="greenbutton inline-blocks">
            <div class="left"></div>
            <div class="center">ПОДОБРАТЬ</div>
            <div class="right"></div>
          </div>
        </a>
      </div>
    </div>
  </div>
  <?php $this->endWidget(); ?>
</div>
