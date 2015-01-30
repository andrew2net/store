<?php
/* @var $this SiteController */
/* @var $popup_form PopupForm */
?>
<?php $this->renderPartial('_popupTopBlock'); ?>
<div id="popup-body">
    <div class="inline-blocks">
        <div style="width: 365px; text-align: center; margin: 0 10px">
            <div class="cufon bold" style="font-size: 18pt">Зарегистрируйтесь на нашем</div>
            <div class="cufon bold" style="font-size: 18pt; margin-top: 10px">сайте и получите скидку</div>
            <div class="cufon red bold" style="font-size: 120pt"><?php echo Yii::app()->params['popupWindow']['discount']; ?>%</div>
            <!--<div class="cufon red bold" style="font-size: 72pt">рублей</div>-->
            <div class="cufon bold" style="font-size: 18pt">на первую покупку</div>
            <!--<div class="cufon gray">Так же мы будем рекомендовать только те игрушки которые будут интересны вашему ребенку.</div>-->
        </div>
        <div style="vertical-align: top; position: relative">
            <div style="height: 240px">
            <div id="popup-form">
                <?php
                $this->renderPartial('_popupForm', array(
                    'popup_form' => $popup_form,
                ));
                ?>
            </div>
            <div style="margin-top: 10px; font-size: 8pt">* мы не шлем спам, а только сообщаем об интересных акциях</div>
            <div style="margin-top: 10px; font-size: 8pt">** купон на скидку будет выслан на Вашу электронную почту</div>
            </div>
            <div style="position: relative">
                <div id="popup-submit" class="main-submit">
                    <div class="center">Получить скидку</div>
                </div>
                <div style="text-align: center">
                    <img id="popup-process" style="display: none" src="/images/load.gif">
                </div>
            </div>
        </div>
    </div>
</div>
