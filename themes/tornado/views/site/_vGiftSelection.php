<?php
/* @var $giftSelection GiftSelection */
/* @var $form CActiveForm */
?>
<?php
$form = $this->beginWidget('CActiveForm', array(
  'id' => 'giftSelect',
  'action' => '/sort',
  'method' => 'get',
    ));
?>
<div class="blue cufon bold" style="font-size: 16pt; position: relative;
     margin: 0px 0px 10px 0px">Подбор товара</div>
<div style="border: #3399cc solid 4px; border-radius: 4px; position: relative">
  <div style="margin: 5px 15px">
    <?php
    echo $form->label($giftSelection, 'gender', array(
      'class' => 'bold',
      'style' => 'font-size: 11pt; line-height: 2; display: block; margin-bottom: 7px; border-bottom: 1px solid #666666'
    ));
    ?>
    <div class="radio">
      <?php
      echo $form->radioButtonList($giftSelection, 'gender'
          , Product::model()->genders, array(
        'style' => 'color: #3399cc',
        'labelOptions' => array('style' => 'line-height: 2')
      ));
      ?>
    </div>
    <?php
    echo CHtml::label('Возраст', 'ageFrom', array(
      'class' => 'bold',
      'style' => 'display: block; font-size: 11pt; line-height: 2.5; margin: 10px 0; border-bottom: 1px solid #666666'
    ));
    ?>
    <?php
    echo CHtml::label('От', 'ageFrom', array('class' => 'bold'));
    echo CHtml::activeNumberField($giftSelection, 'ageFrom', array(
      'style' => 'width: 2em; margin: 0 8px 0 5px',
    ));
    echo CHtml::label('До', 'ageTo', array('class' => 'bold'));
    echo CHtml::activeNumberField($giftSelection, 'ageTo', array(
      'style' => 'width: 2em; margin: 0 5px'
    ));
    ?>
    <div style="border-bottom: 1px solid #666666; margin-bottom: 10px; padding-bottom: 5px">
      <?php
      echo CHtml::label('Цена', 'priceFrom', array(
        'class' => 'bold',
        'style' => 'display: block; font-size: 11pt; line-height: 2.5; margin: 10px 0; border-bottom: 1px solid #666666'
      ));
      ?>
      <div style="margin: 5px 0">
        <?php
//        echo CHtml::label('От', 'priceFrom', array('class' => 'bold'));
        echo CHtml::activeNumberField($giftSelection, 'priceFrom', array(
          'style' => 'width: 3em; margin: 0 8px 0 5px',
        ));
        echo CHtml::label('- ', 'priceTo', array('class' => 'bold'));
        echo CHtml::activeNumberField($giftSelection, 'priceTo', array(
          'style' => 'width: 3em; margin: 0 5px'
        ));
//      echo CHtml::checkBoxList('price', '', array(
//        '300 Р',
//        '300-500 Р',
//        '500-900 Р',
//        '900-1500 Р',
//        'дороже 1500 Р'), array(
//        'separator' => '',
//        'labelOptions' => array('style' => 'display: block; line-height: 1.5')
//      ));
        ?>
      </div>
    </div>
    <?php echo CHtml::checkBox('categoryOnly', TRUE, array('id' => 'categoryOnly')); ?>
    <?php
    echo CHtml::Label('<div style="display: inline-block">Только в этой категории</div>'
        , 'categoryOnly');
    ?>
    <div>
      <?php
      echo CHtml::activeHiddenField($giftSelection, 'category', array('id'=>'GiftCategory'));
      ?>
    </div>
  </div>
  <div>
    <a id="aSubmit" href="#">
      <div style="position: relative; margin: 10px auto; display: block" class="green-item-bt inline-blocks">
        <div class="left"></div>
        <div class="center">ПОДОБРАТЬ</div>
        <div class="right"></div>
      </div>
    </a>
  </div>
</div>
<?php $this->endWidget(); ?>
