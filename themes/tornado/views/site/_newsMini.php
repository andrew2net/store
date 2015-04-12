<?php
$newsData = new CActiveDataProvider('News', [
  'criteria' => ['condition' => 'active=1', 'order' => 'date DESC'],
  'pagination' => ['pageSize' => 5]
  ]);
if ($newsData->getTotalItemCount() > 0) {
  ?>
  <div id="news-cont-mini">
      <h4 class="left-menu-item"><span>Новости:</span></h4>
      <?php
      $this->widget('zii.widgets.CListView', [
      'id' => 'news-list',
      'dataProvider' => $newsData,
      'itemView' => '//site/_newsItemMini',
      'viewData' => isset($news_id) ? ['news_id' => $news_id]:[],
      'template' => '{items}{pager}',
      'htmlOptions' => ['style' => 'margin: 015px'],
      'pager' => array(
      'firstPageLabel' => '<<',
      'prevPageLabel' => '<',
      'nextPageLabel' => '>',
      'lastPageLabel' => '>>',
      'maxButtonCount' => '3',
      'header' => '',
      ),]);
      ?>
      <!--<h5><a href="">Посмотреть все новости</a></h5>-->
  </div>
<?php } ?>
