<?php
/* @var $data Product */
/* @var $currency Currency */
/* @var $trade_price Price */
?>
<div class="table-header">
  <div class="item-inline-img">Фото</div>
  <div class="item-inline-art">Артикул</div>
  <div class="item-inline-name">Наименование</div>
  <div class="item-inline-rest">Наличие</div>
  <div class="item-inline-price">Розница</div>
  <div class="item-inline-price" id="price-header" title="Ваша цена &quot;<?php echo $trade_price->name; ?>&quot;"><?php echo $trade_price->name; ?></div>
  <div class="item-inline-add">Количество</div>
</div>