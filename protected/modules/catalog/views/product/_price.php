<?php
/* @var $this ProductController */
/* @var $form TbActiveForm */
/* @var $prices array */
?>

<table class="table-striped">
  <?php foreach ($prices as $key => $value) { ?>
    <tr>
      <td><?php echo $value['name']; ?></td>
      <td><?php echo $form->numberField($value['price'], "[$key]price"); ?></td>
    </tr>
  <?php } ?>
</table>