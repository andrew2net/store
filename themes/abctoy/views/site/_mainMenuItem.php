<?php
/* @var $group Category */
?>
<div class="mainmenuitem">
  <div>
    <a href="/group/<?php echo $group->id; ?>">
      <div><img alt="<?php echo $group->name; ?>" src="<?php echo $group->url; ?>"></div>
      <?php echo $group->name; ?>
    </a>
  </div>
</div>