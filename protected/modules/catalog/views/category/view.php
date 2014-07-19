<!--
/**
 * Details view  for JsTreeBehavior model.
 *
 * Date: 1/29/13
 * Time: 12:00 PM
 *
 * @author: Spiros Kabasakalis <kabasakalis@gmail.com>
 * @link http://iws.kabasakalis.gr/
 * @link http://www.reverbnation.com/spiroskabasakalis
 * @copyright Copyright &copy; Spiros Kabasakalis 2013
 * @license http://opensource.org/licenses/MIT  The MIT License (MIT)
 */
-->
<div class="span7">
  <div class="page-header">
    <h3><?php echo $model->name; ?>    </h3>
    <h3><?php echo Yii::t('global', 'Details') ?></h3>
  </div>
  <?php
  $this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'htmlOptions' => array('class' => 'table table-striped table-bordered table-condensed'),
    //modify attributes according to your model
    'attributes' => array(
//      'id',
//      'root',
//      'lft',
//      'rgt',
//      'level',
      'name',
      'url',
    ),
  ));
  ?>
</div>
