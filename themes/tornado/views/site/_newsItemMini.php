<?php
/* $data News */
?>
<?php echo $data->date; ?>
<p<?php echo (isset($news_id) && $data->id == $news_id ? ' class="bold"' : ''); ?>>
    <a href="<?php echo $this->createUrl('news/item', ['id' => $data->id]); ?>"><?php echo $data->title; ?></a>
</p>