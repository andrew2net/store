<?php
/* @var $product Product */
?>

<table class="striped">
  <?php
  Yii::import('application.modules.catalog.models.Feature');
  Yii::import('application.modules.catalog.models.FeatureValue');
  Yii::import('application.modules.catalog.models.ProductFeature');
  Yii::import('application.modules.catalog.models.ProductFeatureRange');
  Yii::import('application.modules.catalog.models.ProductFeatureValue');
  foreach ($product->feature_value as $value) {
    ?>
    <tr><td><?php echo $value->values->feature->name . ' ' . $value->values->feature->unit; ?></td><td><?php echo $value->values->value; ?></td></tr>
    <?php
  }
  foreach ($product->feature as $value) {
    ?>
    <tr><td><?php echo $value->feature->name . ' ' . $value->feature->unit; ?></td><td><?php echo $value->value; ?></td></tr>
  <?php
  }
  foreach ($product->feature_range as $value) {
    ?>
    <tr><td><?php echo $value->feature->name . ' ' . $value->feature->unit; ?></td><td><?php echo $value->from . 
        (empty($value->to) ? '' : ' - ' . $value->to); ?></td></tr>
<?php } ?>
</table>