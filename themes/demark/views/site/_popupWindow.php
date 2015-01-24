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
            <div class="cufon red bold" style="font-size: 120pt">5%</div>
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
<script type="text/javascript">
    $('#popup-window').on('focus', '.date', function () {
        $('#popup-window .date').datepicker($.extend({
            changeMonth: true,
            changeYear: true,
            yearRange: "-30:+00",
        }, $.datepicker.regional['ru']));
    });

    var child_n = 0;
    var n = 1;
    $('#popup-window').on('click', '.add-child', function () {
        if (n > 3)
            return;
        child_n++;
        n++;
        $('#popup-window .date').datepicker('destroy');
        var child = $('#child-0').clone(true);
        var id = child[0].id.replace('-0', '-' + child_n);
        child[0].id = id;
        child.find('.icon-remove-child-small').css('display', 'inherit');
        var chname = child.find('#Child_0_name');
        id = chname[0].id.replace('_0_', '_' + child_n + '_');
        chname[0].id = id;
        var name = chname[0].name.replace('[0]', '[' + child_n + ']');
        chname[0].name = name;
        chname[0].value = '';
        var chbday = child.find('#Child_0_birthday');
        var idbd = chbday[0].id.replace('_0_', '_' + child_n + '_');
        chbday[0].id = idbd;
        name = chbday[0].name.replace('[0]', '[' + child_n + ']');
        chbday[0].name = name;
        chbday[0].value = '';
        var gender = child.find('#ytChild_0_gender_id');
        id = gender[0].id.replace('_0_', '_' + child_n + '_');
        gender[0].id = id;
        name = gender[0].name.replace('[0]', '[' + child_n + ']');
        gender[0].name = name;
        var gender0 = child.find('#Child_0_gender_id_0');
        id = gender0[0].id.replace('_0_', '_' + child_n + '_');
        gender0[0].id = id;
        gender0[0].name = name;
        gender0.prop('checked', false);
        var gender0l = child.find('label[for=Child_0_gender_id_0]');
        var lfor = $(gender0l[0]).attr('for').replace('_0_', '_' + child_n + '_');
        $(gender0l[0]).attr('for', lfor);
        var gender1 = child.find('#Child_0_gender_id_1');
        id = gender1[0].id.replace('_0_', '_' + child_n + '_');
        gender1[0].id = id;
        gender1[0].name = name;
        gender1.prop('checked', false);
        var gender1l = child.find('label[for=Child_0_gender_id_1]');
        lfor = $(gender1l[0]).attr('for').replace('_0_', '_' + child_n + '_');
        $(gender1l[0]).attr('for', lfor);
        child.appendTo('#children');
    });

    $('#popup-window').on('click', '.icon-remove-child-small', function () {
        $(this).parent().parent().remove();
        n--;
    });

</script>