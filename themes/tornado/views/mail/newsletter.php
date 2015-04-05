<!DOCTYPE html>
<?php
/* @var $profile Profile */
/* @var $imageIds Array */
/* @var $newsletter Newsletter */
/* @var $this CController */

$email = Yii::app()->params['enterprise']['email'];
?>
<html>
    <head>
        <style>
            body{background-color: #FCF6D5}
            div.container{width: 80%; margin: 0px auto}
            img{max-height: 400px; max-width: 400px;}
            table.blocks{width: 100%; background-color: #FFFFFF}
            table.title td{vertical-align: middle}
            td.img{text-align: center}
            p{margin: 20px 0px 10px}
            div.title{
                color: #1135BF; 
                font-style: italic; 
                font-size: 16pt; 
                margin-left: 10px; 
                display: inline-block;
                position: relative;
                bottom: 25px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <table class="title">
                <tr>
                    <td>
                        <a href="<?php echo Yii::app()->createAbsoluteUrl('') ?>">
                            <img src="<?php echo $imageIds['logo']; ?>" /><div class="title">оптовая компания</div>
                        </a>
                    </td>
                </tr>
            </table>
            <table class="blocks">
                <tr>
                    <td><h3>Здравствуйте, <?php echo $profile->first_name . ' ' . $profile->last_name; ?>!</h3></td>
                </tr>
                <?php foreach ($newsletter->newsletterBlocks as $key => $block) { ?>
                  <tr>
                      <td><p><?php echo $block->text; ?></p></td>
                  </tr>
                  <?php if (isset($imageIds['blocks'][$key])) { ?>
                    <tr>
                        <td class="img"><img src="<?php echo $imageIds['blocks'][$key]; ?>" /></td>
                    </tr>
                  <?php } ?>
                <?php } ?>
            </table>
            <?php $this->renderInternal(dirname(__FILE__) . '/_footer.php'); ?>
            <p style="font-size: 8pt">Если Вы не желаете получать данную рассылку, Вы можете отключить опцию "Получать новости" в <a href="<?php echo Yii::app()->createAbsoluteUrl('profile'); ?>">личном кабинете</a>,
                или прислать <a href="mailto:<?php echo key($email) ?>?subject=Отказ от рассылки">письмо</a> с отказом от рассылки.</p>
        </div>
    </body>
</html>
