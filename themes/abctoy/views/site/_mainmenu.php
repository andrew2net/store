<?php
/* @var $search Search  */
/* @var $groups Category[] */
?>
<div class="mainmenuarea">
  <div id="mainmenu">
    <div style="display: table">
      <?php
      foreach ($groups as $group) {
        $this->renderPartial('_mainMenuItem', array('group' => $group));
      }
      ?>
    </div>
    <div style="height: 25px; margin-top: 10px">
      <div style="float: right">
        <?php echo CHtml::beginForm('/search', 'get', array('id'=>'search-form')); ?>
        <?php
        echo CHtml::activeTextField($search, 'text', array(
          'style' => 'border-radius: 4px 0 0 4px; border: none; width: 10em; height: 23px; padding-left: 10px; float: left; background:whitesmoke; box-shadow: 0 0 1px inset',
          'placeholder' => 'Поиск'
        ));
        ?>
        <?php
        echo CHtml::submitButton('', array(
          'style' => 'margin: 0 0 0 -4px; border: none; border-radius: 0 4px 4px 0; float: left; box-shadow: 0 0 1px inset',
          'class' => 'icon-search'
        ));
        ;
        ?>
        <?php echo CHtml::endForm(); ?>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  $('.icon-search').click(function (event){
    event.preventDefault();
    if($('#Search_text').val())
      $('#search-form').submit();
  });
</script>