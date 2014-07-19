<!--
 /**
  * treewidget view file
  *
  * Date: 1/29/13
  * Time: 12:00 PM
  *
  * @author: Spiros Kabasakalis <kabasakalis@gmail.com>
  * @link http://iws.kabasakalis.gr/
  * @link http://www.reverbnation.com/spiroskabasakalis
  * @copyright Copyright &copy; Spiros Kabasakalis 2013
  * @license http://opensource.org/licenses/MIT  The MIT License (MIT)
  * @version 1.0.0
  */
-->
<div class="span12">
  <!--    <h1 class="page-header">CActiveRecord with NestedSet behavior Administration with jstree </h1>
      <div class="row well">
          <ul>
              <li>If tree is empty,start by creating one or more root nodes.</li>
              <li>Right Click on a node to see available operations.</li>
              <li>Move nodes with Drag And Drop.You can move a non-root node to root position and vice versa.</li>
              <li>Root nodes cannot be reordered.Their order is fixed by id.</li>
          </ul>
      </div>-->
  <div id="tree-bar" style="background: white">
    <div class="row btn-toolbar">
      <input id="reload" type="button" value="Обновить" class="btn btn-primary pull-left">
      <input id="add_root" type="button" value="Новая группа" class="btn btn-primary pull-left">
    </div>
  </div>
  <div class="row">
    <!--The tree will be rendered in this div-->
    <div class="well row span7 inline" style="margin-top: 20px; display: inline-block" id="<?php echo $this->jstree_container_ID; ?>"></div>
    <div id="category-form" class="span4" style="margin-top: 5px; display: inline-block"></div>
  </div>
</div>

<script type="text/javascript">
  $().ready(function ($){
    var nav = $('#tree-bar');
    var offset = nav.offset();
    var form = $('#category-form');
    $(window).scroll(function (){
      if ($(this).scrollTop() > offset.top){
        nav.addClass('f-nav');
        form.addClass('f-form');
      }
      else{
        nav.removeClass('f-nav');
        form.removeClass('f-form');
      }
    });
  });
</script>