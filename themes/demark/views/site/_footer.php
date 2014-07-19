<?php
$groups = Category::model()->roots()->findAll();
?>
<div id="footer">
  <div class="container">
    <div class="table">
      <div class="table-cell" style="width: 250px">
        <a href="/"><img width="200" height="51" alt="DeMARK" src="/themes/<?php echo Yii::app()->theme->name; ?>/img/logo.png"></a>
        <div class="gray" style="margin-top: 20px">
          2014. Все права защищены.<br>DeMARK - инструменты и<br>оборудование.<br>Все торговые марки являются<br>собственностью их<br>правообладателей.
        </div>
        <div style="margin-top: 4px; display: block">Разработка сайта<br><a style="text-decoration-line: initial; -moz-text-decoration-line: none" href="mailto:andriano@ngs.ru?subject=Разработка сайта">andriano@ngs.ru</a></div>
      </div>

      <div class="table-cell" style="vertical-align: middle">
        <div class="gray bold lager" style="margin: 10px 0">
          DeMARK - силовая техника, садовая техника, электроинструмент, электростанции
        </div>
        <div>
          <div class="table">
            <div class="table-cell footer-menu">
              <div class="bold">DeMARK</div>
              <?php
              $items = Yii::app()->db->createCommand()
                      ->select("title AS label, CONCAT('/info/', url) AS url")
                      ->from('{{page}}')
                      ->where('menu_show>0')
                      ->order('menu_show')->queryAll();

//              $this->widget('zii.widgets.CMenu', array(
//                'items' => $items,
//              ));
              foreach ($items as $item) {
                ?>
                <div><a href="<?php echo $item['url']; ?>"><?php echo $item['label']; ?></a></div>
              <?php } ?>
              <!--              <div><a href="/about">О компании</a></div>
                            <div><a href="/deliver">Доставка</a></div>
                            <div><a href="/payment">Оплата</a></div>
                            <div><a href="/guarantee">Гарантии и обмен</a></div>-->
              <!--<div><a href="/contact">Контакты</a></div>-->
              <!--<div><a href="#">Наши преимущества</a></div>-->
            </div>
            <div class="table-cell footer-menu">
              <div class="bold">Товар</div>
              <?php
              foreach ($groups as $group) {
                ?>
                <div><?php
                  echo CHtml::link($group->name
                      , $this->createUrl('group', array('id' => $group->id)));
                  ?></div>
              <?php } ?>
            </div>
            <div class="table-cell">
              <div class="bold" style="height: 2.5em">Звоните</div>
              <div class="gray bold x-lage" style="height: 1.3em">(383) 375-03-22</div>
              <div class="gray bold lager" style="height: 1.5em">г. Новосибирск</div>
            </div>
          </div>
        </div>
      </div>
    </div>    
  </div>
</div>
<div id="popup-window" style="display: none"></div>
<div id="bottom-bar">УКАЖИТЕ ВОЗРАСТ ВАШЕГО РЕБЕНКА И ПОЛУЧИТЕ СКИДКУ <span class="red">400 РУБЛЕЙ</span> НА ПЕРВУЮ ПОКУПКУ!<span id="get-discount" class="red" style="margin-left: 50px; text-decoration: underline; -moz-text-decoration-line: underline; cursor: pointer">ПОЛУЧИТЬ СКИДКУ</span></div>
<?php // Yii::app()->clientScript->registerScriptFile('http://vk.com/js/api/share.js?90', CClientScript::POS_HEAD); ?>
<script type="text/javascript">
  $(function() {

    $('#popup-window').dialog({
      modal: true,
      resizable: false,
      autoOpen: false,
      draggable: false,
      create: function(event, ui) {
        $(event.target).parent().css('position', 'fixed');
      }
    });

    $('#popup-window').on('click', '.popup-close', function() {
      clearTimeout(timeout_id);
      $('#popup-window').dialog('close');
      if ($.cookie('popup') !== '2') {
        $.cookie('popup', '1', {expires: 2592000, path: '/'});
        showBottomBar();
      }
    });

    $('#get-discount').click(function() {
      $('#bottom-bar').hide();
      $('#footer').css('margin-bottom', '0');
      if ($('#popup-window').dialog('option', 'width') < 900)
        loadPopup();
      $('#popup-window').dialog('open');
    });
    var popup = $.cookie('popup');
    switch (popup) {
      case null:
        $.cookie('popup', '0', {expires: 2592000, path: '/'});
      case '0':
//        loadPopup();
//        $('#popup-window').dialog('open');
        break;
      case '1':
        loadPopup();
        showBottomBar();
    }
  });

  var timeout_id;

  function showBottomBar() {
    $('#bottom-bar').show();
    $('#footer').css('margin-bottom', '44px');
  }

  function loadPopup() {
    $('#popup-window').dialog('option', 'height', 500);
    $('#popup-window').dialog('option', 'width', 930);
    $('#popup-window').dialog('option', 'dialogClass', 'popup-window');
    $('#popup-window').load('/popupWindow', function() {
      Cufon.replace('#popup-window .cufon');
    });
  }

  $('#popup-window').on('click', '#popup-submit', function() {
    $(this).hide();
    $('#popup-process').show();
    var children = [];
    $('.child').each(function() {
      var name = $(this).find('.name').val();
      var date = $(this).find('.date').val();
      var gender = $(this).find('input[type=radio]:checked').val();
      children.push({name: name, birthday: date, gender_id: gender});
    });
    var accept = $('#PopupForm_accept').prop('checked') ? 1 : 0;
    var email = $('#PopupForm_email').val();
    $.post('/popupWindow', {
      children: children,
      PopupForm: {accept: accept, email: email}
    }, function(data) {
      $('#popup-process').hide();
      var result = JSON && JSON.parse(data) || $.parseJSON(data);
      switch (result.result) {
        case 'error':
          $('#popup-submit').show();
          $('#popup-form').html(result.html);
          $('input[type="radio"][class~="error"], input[type="checkbox"][class~="error"]')
                  .parent()
                  .css('border', '1px solid #cc3333')
                  .css('border-radius', '5px')
                  .css('padding', '4px');
          break;
        case 'exist':
          $('#popup-window').dialog('close');
          $('#popup-window').dialog('option', 'height', 120);
          $('#popup-window').dialog('option', 'width', 310);
          $('#popup-window').dialog('option', 'dialogClass', 'popup-email-exist');
          $('#popup-window').html(result.html);
          $('#popup-window').dialog('open');
          timeout_id = setTimeout(function() {
            $('#popup-window').dialog('close');
          }, 5000);
          break;
        case 'register':
          $('#popup-body').html(result.html);
          Cufon.replace('#popup-window .cufon');
          $.cookie('popup', '2', {expires: 2592000, path: '/'});
          $('#login-menu').hide();
          $('#profile-menu').show();
//          yaCounter23309737.reachGoal('discount');
          break;
      }
    });
  });
</script>