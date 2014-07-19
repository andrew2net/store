<?php
/* @var $this ProductController */
/* @var $model Product */
/* @var $form TbActiveForm */
?>
<div id="category-tree">
  <?php echo $model->getCategoryTree(isset($_POST['Categories']) ? $_POST['Categories'] : NULL); ?>
</div>
<?php
$jstree_data = <<<EOD
$("#category-tree").jstree({
    "core" : {"load_open" : true},
    "checkbox" : {
      "two_state" : true,
      "real_checkboxes" : true,
      "real_checkboxes_names" : function(n){
        var id = n[0].id.replace(/node_/, "");
        return ["Categories[" + id +"]"];
          }
        },
    "plugins" : ["themes", "html_data", "checkbox"]
    
 }).bind("loaded.jstree", function(event, data){
   $(this).jstree("open_all");
     })
  .bind("change_state.jstree", function (e, d){
      var category = [];
      var d = /\d+/;
      $('li.jstree-checked').each(function(i, e) {
        var id = d.exec(e.id)[0];
        category[i] = id;
      });
      var res = /\?id=(\d+)/.exec(location.search);
      var pid = res ? res[1] : null;
      $('#feature').load('featureValues', {category: category.toString(), id: pid});
  });
EOD;
$dir = Yii::getPathOfAlias('ext.jstree') . DIRECTORY_SEPARATOR . 'assets';
$assets = Yii::app()->getAssetManager()->publish($dir);
$cs = Yii::app()->getClientScript();
$cs->registerScript(__CLASS__ . 'jstree_category', $jstree_data, CClientScript::POS_END);
$cs->registerCssFile($assets . '/themes/default/style.css');
$cs->registerScriptFile($assets . '/jquery.jstree.js', CClientScript::POS_END);
?>
