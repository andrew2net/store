<?php
/* $data News */
?>
<?php echo $data->date; ?>
<h5><a href="<?php echo $this->createUrl('news/item', ['id' => $data->id]); ?>"><?php echo $data->title; ?></a></h5>