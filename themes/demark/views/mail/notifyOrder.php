<?php
/* @var $order Order */
/* @var $this CController */
?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title></title>
  </head>
  <body>
    <?php
    $params = array(
      'order' => $order,
    );
    $this->renderInternal(dirname(__FILE__) . '/_order.php', $params);
//    $this->renderInternal(dirname(__FILE__) . '/_footer.php');
    ?>
  </body>
</html>
