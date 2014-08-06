<p><a href="<?php echo Yii::app()->createAbsoluteUrl(''); ?>">Перейти на сайт и начать покупки</a></p>
<p>Спасибо, что выбрали нас!</p><br>
<?php
$phones = array();
foreach (Yii::app()->params['enterprise']['phone'] as $phone) {
  if (is_array($phone)) {
    $phones[] = $phone['cod'] . $phone['num']; 
  }
  else {
    $phones[] = $phone;
  }
}
echo CHtml::tag('p', array('style' => 'margin-top:1em'), 'Это письмо сформированно автоматически. Пожалуйста не отвечайте на него.');
echo CHtml::tag('p', array('style' => 'margin-top:1em'), 'Тел. ' . implode(',', $phones));
echo CHtml::tag('a', array('href' => Yii::app()->createAbsoluteUrl('')), Yii::app()->createAbsoluteUrl(''));
?>
