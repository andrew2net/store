<p><a href="<?php echo Yii::app()->createAbsoluteUrl(''); ?>">Перейти на сайт и начать покупки</a></p>
<p>Спасибо, что выбрали нас!</p><br>
<?php
echo CHtml::tag('p', array('style' => 'margin-top:1em'), 'Это письмо сформированно автоматически. Пожалуйста не отвечайте на него.');
echo CHtml::tag('p', array('style' => 'margin-top:1em'), 'Телефон в Новосибирске +7 (383) 375-03-22.');
echo CHtml::tag('a', array('href' => Yii::app()->createAbsoluteUrl('')), Yii::app()->createAbsoluteUrl(''));
?>
