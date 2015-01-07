<?php
/* @var $delivery array */
/* @var $delivery_id string */
/* @var $form TbActiveForm */
?>
<div class="inline-blocks">
  <div>
    <table class="table-striped" style="width: 440px; margin: 5px 0 0 20px">
      <!--<col width="110"><col width="190"><col width="100">-->
      <thead>
        <tr style="display: block">
          <th style="width: 110px">Вес (кг)</th><th style="width: 150px">тариф (<?php echo $delivery['currency']; ?>)</th>
          <th style="width: 100px">
            <?php echo TbHtml::button('Добавить', array('color' => TbHtml::BUTTON_COLOR_PRIMARY, 'class' => 'add-rate')); ?>
          </th>
        </tr>
      </thead>
      <tbody style="height: 400px; overflow-y: auto; display: block; width: 440px">
        <tr style="display: none">
          <td style="width: 110px">
            <?php
            echo TbHtml::numberField("DeliveryRate[$delivery_id][n][weight]", ''
                , array('span' => 1, 'step' => 0.01, 'min' => 0.01, 'disabled' => true));
            ?>
          </td>
          <td style="width: 150px">
            <?php
            echo TbHtml::numberField("DeliveryRate[$delivery_id][n][price]", ''
                , array('span' => 1, 'step' => 0.01, 'min' => 0, 'disabled' => true));
            ?>
          </td>
          <td style="width: 100px; text-align: center"><?php
            echo TbHtml::tooltip(TbHtml::icon(TbHtml::ICON_TRASH, array('style' => 'cursor:pointer')), '#', 'Удалить');
            ?></td>
        </tr>
        <?php
        foreach ($delivery['data'] as $id => $value) {
          ?>
          <tr>
            <td style="width: 110px">
              <?php
              echo $form->numberField($value, "[$delivery_id][$id]weight"
                  , array('span' => 1, 'step' => 0.01, 'min' => 0.01));
              ?>
            </td>
            <td style="width: 150px">
              <?php
              echo $form->numberField($value, "[$delivery_id][$id]price"
                  , array('span' => 1, 'step' => 0.01, 'min' => 0));
              ?>
            </td>
            <td style="width: 100px; text-align: center"><?php
              echo TbHtml::tooltip(TbHtml::icon(TbHtml::ICON_TRASH, array('style' => 'cursor:pointer')), '#', 'Удалить');
              ?></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>

  <div style="margin-left: 40px; vertical-align: top">
    <div>
      <?php echo $form->labelEx($delivery['regionDelivery'], "[$delivery_id]zone"); ?>
      <div class="select-zone">
        <?php
        echo $form->DropDownList($delivery['regionDelivery'], "[$delivery_id]zone", $delivery['zones']
            , array('prompt' => '--', 'span' => 1));
        ?>
      </div>
    </div>
    <div>
      <?php echo TbHtml::label("Доп.тариф (" . $delivery['currency'] . '/кг)', "RegionDelivery_{$delivery_id}_weight_rate"); ?>
      <?php
      echo $form->numberField($delivery['regionDelivery'], "[$delivery_id]weight_rate"
          , array('span' => 1, 'min' => 0));
      ?>
    </div>
  </div>
</div>