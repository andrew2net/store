<?php
Yii::import('application.modules.catalog.models.Brand');
$brands = Brand::model()->withImage()->findAll();
?>
<span class="blue cufon bold" style="font-size: 20pt; position: relative;
     margin: 0 0 -15px 20px; background: white; width: 100px; z-index: 1;
     padding: 0 8px; top: 15px">Бренды</span>
<div style="border: #3399cc solid 4px; border-radius: 4px; position: relative;">
  <div class="brands" style="margin: auto 60px">
    <ul>
      <?php foreach ($brands as $value) { ?>
        <li style="padding: 0 10px; height: 150px; width: 200px; position: relative">
          <a href="<?php echo $this->createUrl('brand', array('id'=>$value->id)) ?>">
            <img style="position: absolute; margin: auto; top: 0; bottom: 0; 
                 right: 0; left: 0; max-height: 130px; max-width: 180px" 
                 src="<?php echo $value->img; ?>" />
          </a>
        </li>
      <?php } ?>
    </ul>
  </div>
    <a class="brands-prev" href="#"></a>
    <a class="brands-next" href="#"></a>
</div>
