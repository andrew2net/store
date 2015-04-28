<?php 
/* @var $customerProfile CustomerProfile */
?>
<p><a href="<?php echo Yii::app()->createAbsoluteUrl('profile'); ?>">Перейти в личный кабинет</a></p>
<p>Спасибо, что выбрали нас!</p>
<?php
$phones = array();
foreach (Yii::app()->params['phones'][$customerProfile->price_country] as $phone) {
  if (is_array($phone)) {
    $phones[] = $phone['cod'] . $phone['num']; 
  }
  else {
    $phones[] = $phone;
  }
}
echo CHtml::tag('p', array(), 'Это письмо сформированно автоматически. Пожалуйста не отвечайте на него.');
echo CHtml::tag('p', array('style' => 'margin-top:1em'), 'Тел. ' . implode(', ', $phones));
echo CHtml::tag('a', array('href' => Yii::app()->createAbsoluteUrl('')), Yii::app()->createAbsoluteUrl(''));
?>
