<?php
/* @var $this DefaultController */
/* @var $model Order */
/* @var $order_product OrderProduct[] */
/* @var $product Product[] */
?>
  <table>
    <tr>
      <th>
        <?php echo TbHtml::label('Артикул', 'product_articles'); ?>
      </th>
      <th>
        <?php echo TbHtml::label('Наименование', 'product_name'); ?>
      </th>
      <th style="width: 4em">
        <?php echo TbHtml::label('Количество', 'product_quantity'); ?>
      </th>
      <th style="width: 4em">
        <?php echo TbHtml::label('Цена', 'product_price'); ?>
      </th>
      <th></th>
    </tr>
    <?php
    $total = 0;
    $n = 0;
    foreach ($order_product as $key => $item) {
      $n++;
      $total += $item->quantity * $item->price;
      ?>
      <tr id="product-<?php echo $n; ?>" class="row-product">
        <td>
          <?php
          echo TbHtml::activeTextField($product[$key], "[$n]article", array(
            'readonly' => TRUE,
            'class' => 'row-article',
          ));
          ?>
        </td>
        <td style="width: 100%">
          <?php
          echo TbHtml::activeTextField($product[$key], "[$n]name", array(
            'class' => 'row-name',
            'block' => TRUE,
          ));
          ?>
        </td>
        <td>
          <?php
          echo TbHtml::activeNumberField($item, "[$n]quantity"
              , array('class' => 'row-quantity'));
          ?>
        </td>
        <td>
          <?php
          echo TbHtml::activeNumberField($item, "[$n]price"
              , array('class' => 'row-price', 'disc' => $item->discount));
          ?>
        </td>
        <td><?php
          echo TbHtml::icon(TbHtml::ICON_TRASH, array(
            'class' => 'row-del',
            'style' => 'cursor:pointer',
            'rel' => 'tooltip',
            'title' => 'Удалить',
          ));
          ?></td>
      </tr>
    <?php } ?>
    <tr>
      <td colspan="3"><div style="text-align: right">Стоимость доставки: </div></td>
      <td>
        <?php
        echo TbHtml::activeNumberField($model, 'delivery_summ', array(
          'id' => 'order-delivery-summ',
        ));
        ?>
      </td>
    </tr>
    <tr>
      <td colspan="3"><div style="text-align: right">Скидка по купону: </div></td>
      <td>
        <?php
        echo TbHtml::tag('div', array(
          'name' => 'order_coupon_discount',
          'class' => 'display-field',
//          'style' => 'width:11.5em',
          'id' => 'order-coupon-discount'));
        echo $model->delivery_summ;
        echo TbHtml::closeTag('div');
        ?>
      </td>
    </tr>
    <tr>
      <td>
        <?php
        echo TbHtml::linkButton('Добавить', array(
          'id' => 'add-product',
          'color' => TbHtml::BUTTON_COLOR_PRIMARY,
        ));
        ?>
      </td><td colspan="2"><div style="text-align: right">Итого: </div></td>
      <td>
        <?php
        $total += $model->delivery_summ;
        echo TbHtml::tag('div', array(
          'name' => 'order_total',
          'class' => 'display-field',
//          'style' => 'width:11.5em',
          'id' => 'order-total'));
        echo $total;
        echo TbHtml::closeTag('div');
        ?>
      </td>
    </tr>
  </table>
