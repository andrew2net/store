<?php
/* @var $profile CustomerProfile */
?>
<div class="table" style="padding-bottom: 10px">
  <div class="table-cell valign-middle" style="width: 200px;">
    <a href="/"><img width="173" height="57" alt="ABC toy" src="/themes/abctoy/css/logo_shadow.png"></a>
    <!--<div><a class="gray" href="#">Отзывы о нас</a></div>-->
  </div>

  <div class="table-cell valign-middle" style="width: 200px">
    <div class="cufon gray bold">Интернет магазин<br>детских товаров и игрушек</div>
  </div>
  <div class="table-cell bold" style="width: 180px">
    <div class="cufon lager" style="padding-bottom: 0.4em">Телефон для справок</div>
    <div class="cufon x-lage" style="padding-bottom: 0.2em">
      <span class="red">(383)</span><span class="blue"> 375</span><span>-</span><span class="green">03</span><span>-</span><span class="yellow">22</span>
    </div>
    <div style="text-align: right">
      <div class="cufon gray" style="font-size: medium; padding-bottom: 0.5em">(10:00 - 18:00 пн.-пт.)</div>
    </div>
    <div class="gray lager"><?php echo $profile->city; ?></div>
  </div>
  <div style="position: relative; top: 35px">
    <div class="cufon yellow bold" style="font-size: 24pt; text-align: right">Личный кабинет</div>
    <div style="text-align: right"><a href="/">Продолжить покупки</a></div>
  </div>
</div>
