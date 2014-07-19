<?php
Yii::import('application.modules.discount.models.Action');
Yii::import('application.modules.discount.models.Advert');
Yii::import('application.modules.catalog.models.Product');
$adds = Action::model()->published()->findAll();
?>
<div class="jcarousel" style="margin-top: 15px; background-color: whitesmoke">
  <ul>
    <?php foreach ($adds as $value) { ?>
      <li style="width: 950px">
        <div class="inline-blocks">
          <div style="height: 270px">
            <?php if ($value->type_id) { ?>
              <div class="cufon red" style="height: 35px; padding-top: 15px; 
                   padding-left: 40px; font-size: 22pt;">
                <span style="font-weight: bold">Осталось</span>
                <span class="clock" date="<?php echo $value->advert->date; ?>"></span>
              </div>
              <img height="220" width="420" src="<?php echo $value->img; ?>">
            </div>
            <div style="vertical-align: top; width: 480px; 
                 font-weight: bold;
                 padding: 15px 0">
              <div class="cufon gray" style="text-align: right; font-size: 25pt; padding-bottom: 10px">
                <?php echo $value->advert->text; ?>
              </div>
              <div class="inline-blocks">
                <div style="width: 45%; text-align: center; position: relative; top: -40px">
                  <div class="cufon gray" style="font-size: 14pt">Старая цена</div>
                  <div class="cufon gray strike" style="font-size: 18pt; display: inline-block;
                       font-weight: bold; margin: 10px auto">
                    <?php echo number_format($value->product->price, 0, '.', ''); ?>.-</div>
                </div>
                <div style="width: 49%">
                  <div class="cufon red" style="font-size: 25pt; font-weight: bold; padding-bottom: 10px">новая цена</div>
                  <div class="cufon red" style="font-size: 60pt">
                    <?php echo number_format($value->advert->price, 0, '.', ''); ?>.-
                  </div>
                  <a href="<?php echo $this->createUrl('product', array('id' => $value->product->id)); ?>">
                    <div class="redbutton inline-blocks">
                      <div class="left"></div>
                      <div class="center">Подробнее</div>
                      <div class="right"></div>
                    </div>
                  </a>
                </div>
              </div>
            </div>
            <?php
          }
          else {
            ?>
            <?php echo $value->url ? '<a href="' . $value->url . '">' : ''; ?>
            <img height="270" width="950" src="<?php echo $value->img; ?>">
            <?php echo $value->url ? '</a>' : ''; ?>
          <?php } ?>
        </div>
      </li>
    <?php } ?>
  </ul>
</div>
