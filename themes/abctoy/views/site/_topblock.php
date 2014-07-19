<div class="container">
  <div class="table" style="padding-bottom: 10px">
    <div class="table-cell valign-middle" style="width: 20%">
      <a href="/"><img width="173" height="57" alt="ABC toy" src="/themes/abctoy/css/logo_shadow.png"></a>
      <div><a class="gray" target="_blank" href="http://market.yandex.ru/shop/208455/reviews">Отзывы о нас</a></div>
    </div>

    <div class="table-cell valign-middle" style="width: 20%">
      <div class="cufon gray bold" style="position: relative; bottom: 5px">Интернет-магазин<br>детских товаров и игрушек<br>с Быстрой доставкой<br>по всей России</div>
    </div>
    <div class="table-cell valign-middle" style="width: 90px; padding-left: 20px">
      <a href="/about">
        <div class="icon-sklad"></div>
      </a>
    </div>
    <div class="table-cell bold blue valign-middle" style="width: 23%">
      <a href="/about">
        <div class="cufon" style="font-size: xx-large;">50 000</div>
        <div class="cufon">детских товаров<br>на собственном складе</div>
      </a>
    </div>
    <div class="table-cell bold" style="width: 130px; height: 107px">
      <!--<a href="/contact">-->
      <div class="cufon lager">Есть вопросы? Звоните!</div>
      <div class="cufon x-lage" style="padding: 5px 0 0">
        <span class="red">(383)</span><span class="blue"> 375</span><span>-</span><span class="green">03</span><span>-</span><span class="yellow">22</span>
      </div>
      <div style="text-align: right">
        <div id="callback-link" class="blue underline-dashed" style="font-size: medium; font-weight: normal; cursor: pointer">Перезвонить Вам?</div>
      </div>
      <?php $this->renderPartial('//site/_city'); ?>
    </div>
  </div>
</div>
<div id="callback-overlay" style="position: fixed; left: 0; top: 0; width: 100%; height: 100%; display: none; background: rgba(102,102, 102, 0.4); z-index: 100">
  <div id="callback-box" style="width: 100%; height: 100%; display: none">
    <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; margin: auto; width: 350px; height: 290px; border: 4px solid #3399cc; border-radius: 5px; background: white; z-index: 102">
      <div style="margin: 10px 15px; font-size: 12pt">
        <div style="font-size: 14pt; text-align: center; margin-bottom: 10px" class="bold">Заказ звонка</div>
        <div id="callback-form">
          <table>
            <tr>
              <td style="width: 78px"><?php echo CHtml::label('Телефон <span class="red">*</span>', 'callback-tel'); ?></td>
              <td><?php echo CHtml::telField('callback-tel', '', array('class' => 'input-text', 'style' => 'width: 100%')); ?></td>
            </tr>
            <tr>
              <td><?php echo CHtml::label('Имя <span class="red">*</span>', 'callback-name'); ?></td>
              <td><?php echo CHtml::textField('callback-name', '', array('class' => 'input-text', 'style' => 'width: 100%')); ?></td>
            </tr>
          </table>
          <div><?php echo CHtml::label('Комментарий', 'callback-note'); ?></div>
          <div><?php echo CHtml::textArea('callback-note', '', array('class' => 'input-text', 'style' => 'width: 310px')); ?></div>
        </div>
        <div class="blue" style="cursor: pointer; position: absolute; right: 15px; bottom: 15px" id="callback-cancel">Отмена</div>
        <div class="blue" style="cursor: pointer; position: absolute; left: 15px; bottom: 15px" id="callback-submit">Заказать звонок</div>
        <div id="callback-process" class="loading" style="display: none">&nbsp;</div>
        <div id="callback-result" class="red bold" style="display: none; margin-top: 70px; text-align: center"></div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  var from;
  $('#callback-link').click(function() {
    from = $(this).position();
    $('#callback-overlay').show();
    $('#callback-box').show('scale', {origin: [from.top, from.left + 60]});
  });

  function closeCallbackWindow() {
    $('#callback-box').hide('scale', {origin: [from.top, from.left + 60]}, function() {
      $('#callback-overlay').hide();
      $('#callback-tel, #callback-name').css('border', 'none');
    });
  }

  $('#callback-cancel').click(function() {
    closeCallbackWindow();
  });

  $('#callback-submit').click(function() {
    $('#callback-tel, #callback-name').css('border', 'none');
    var phone = $('#callback-tel').val();
    var name = $('#callback-name').val();
    var valid = true;
    if (phone.length == 0) {
      $('#callback-tel').css('border', '#cc3333 solid 1px');
      valid = false;
    }
    if (name.length == 0) {
      $('#callback-name').css('border', '#cc3333 solid 1px');
      valid = false;
    }
    if (valid) {
      $('#callback-submit').hide();
      $('#callback-form').hide();
      $('#callback-process').show();
      var note = $('#callback-note').val();
      $.post('/site/callback', {phone: phone, name: name, note: note}, function(data) {
        $('#callback-process').hide();
        if (data == 'ok') {
          $('#callback-result').html('Завявка успешно отправлена.<br>В ближайшее время Вам перезвонят.');
        } else {
          $('#callback-result').html('Не удалось отправить заявку.');
        }
        $('#callback-result').show();
        setTimeout(function() {
          closeCallbackWindow();
        }, 5000);
      });
    }
  });
</script>