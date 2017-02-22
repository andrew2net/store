<?php
Yii::import('application.modules.catalog.models.Category');
$groups = Category::model()->roots()->findAll();
?>
<div id="footer">
    <div class="container">
        <div class="table">
            <div class="table-cell" style="width: 250px; padding-right: 80px">
                <a href="/"><img width="200" height="51" alt="Tornado" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/logo.png"></a>
                <div class="gray" style="margin-top: 20px">
                    2014. Все права защищены.<br>Торнадо - аксессуары для<br>мобильных устройств. Все торговые марки являются собственностью их правообладателей.
                </div>
                <div style="margin-top: 4px; display: block">
                    <a href="http://sitscan.com">Разработка сайта</a><br>
                    <a class="devem" style="text-decoration-line: initial; -moz-text-decoration-line: none" href="#">Andriano</a></div>
            </div>

            <div class="table-cell" style="vertical-align: middle">
                <div class="gray bold lager" style="margin: 10px 0; font-size: 12pt">
                    Торнадо - аксессуары для мобильных устройств
                </div>
                <div>
                    <div class="table">
                        <div class="table-cell footer-menu">
                            <div class="bold">Торнадо</div>
                            <?php
                            $items = Yii::app()->db->createCommand()
                                ->select("title AS label, CONCAT('/info/', url) AS url")
                                ->from('{{page}}')
                                ->where('menu_show>0')
                                ->order('menu_show')->queryAll();

                            foreach ($items as $item) {
                              ?>
                              <div><a href="<?php echo $item['url']; ?>"><?php echo $item['label']; ?></a></div>
                            <?php } ?>
                        </div>
                        <div class="table-cell footer-menu" style="width: 450px">
                            <div class="bold fmenu-items">Товар</div>
                            <ul>
                                <?php
                                foreach ($groups as $group) {
                                  ?>
                                  <li><?php
                                      echo CHtml::link($group->name
                                        , Yii::app()->createUrl('group', array('id' => $group->id)));
                                      ?></li>
                                <?php } ?>
                            </ul>
                        </div>
                        <div class="table-cell">
                            <div class="bold" style="height: 2.5em; font-size: 11pt">Звоните</div>
                            <div>
                                <?php
                                foreach (Yii::app()->params['enterprise']['phone'] as $phone) {
                                  if (is_array($phone)) {
                                    ?>
                                    <div>
                                        <span style="vertical-align: middle; font-size: 12pt"><?php echo $phone['cod']; ?></span>
                                        <span class="gray bold" style="font-size: 18pt; vertical-align: middle"><?php echo $phone['num']; ?></span>
                                    </div>
                                    <?php
                                  } else {
                                    ?>
                                    <div><span class="gray" style="font-size: 16pt; vertical-align: middle"><?php echo $phone; ?></span></div>
                                    <?php
                                  }
                                }
                                ?>
                            </div>
                            <div class="gray bold lager" style="height: 1.5em"><?php echo Yii::app()->params['city']; ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>    
    </div>
</div>
<div id="popup-window" style="display: none"></div>
<div id="bottom-bar">ПОЛУЧИТЕ СКИДКУ <span class="red"><?php echo Yii::app()->params['popupWindow']['discount']; ?>%</span> НА ПЕРВУЮ ПОКУПКУ!<span id="get-discount" class="red" style="margin-left: 50px; text-decoration: underline; -moz-text-decoration-line: underline; cursor: pointer">ПОЛУЧИТЬ СКИДКУ</span></div>
<script type="text/javascript">
  $(function () {
      var popupWindow = $('#popup-window');

      popupWindow.dialog({
          modal: true,
          resizable: false,
          autoOpen: false,
          draggable: false,
          create: function (event, ui) {
              $(event.target).parent().css('position', 'fixed');
          }
      });

      popupWindow.on('click', '.popup-close', function () {
          clearTimeout(timeout_id);
          popupWindow.dialog('close');
          if ($.cookie('popup') !== '2') {
              $.cookie('popup', '1', {expires: 2592000, path: '/'});
              showBottomBar();
          }
      });

      popupWindow.on('submit', 'form#popupForm', function (event) {
          $('#popup-submit').hide();
          event.preventDefault();
          submitForm();
      });

      $('#get-discount').click(function () {
          $('#bottom-bar').hide();
          $('#footer').css('margin-bottom', '0');
          if (popupWindow.dialog('option', 'width') < 900)
              loadPopup();
          popupWindow.dialog('open');
      });
      var popup = $.cookie('popup');
      switch (popup) {
          case null:
              $.cookie('popup', '0', {expires: 2592000, path: '/'});
          case '0':
              loadPopup();
              popupWindow.dialog('open');
              break;
          case '1':
              loadPopup();
              showBottomBar();
      }

      var timeout_id;

      function showBottomBar() {
          $('#bottom-bar').show();
          $('#footer').css('margin-bottom', '44px');
      }

      function loadPopup() {
          popupWindow.dialog('option', 'height', 460);
          popupWindow.dialog('option', 'width', 800);
          popupWindow.dialog('option', 'dialogClass', 'popup-window');
          popupWindow.load('/site/popupWindow', function () {
              Cufon.replace('#popup-window .cufon');
          });
      }

      popupWindow.on('click', '#popup-submit', function () {
          $(this).hide();
          submitForm();
      });

      function submitForm() {
          $('#popup-process').show();
          var accept = $('#PopupForm_accept').prop('checked') ? 1 : 0;
          var email = $('#PopupForm_email').val();
          $.post('/site/popupWindow', {
              PopupForm: {accept: accept, email: email}
          }, function (data) {
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
                      popupWindow.dialog('close');
                      popupWindow.dialog('option', 'height', 120);
                      popupWindow.dialog('option', 'width', 310);
                      popupWindow.dialog('option', 'dialogClass', 'popup-email-exist');
                      popupWindow.html(result.html);
                      popupWindow.dialog('open');
                      timeout_id = setTimeout(function () {
                          popupWindow.dialog('close');
                      }, 5000);
                      break;
                  case 'register':
                      $('#popup-body').html(result.html);
                      popupWindow.dialog('option', 'height', 360);
                      popupWindow.dialog('option', 'width', 600);
                      Cufon.replace('#popup-window .cufon');
                      $.cookie('popup', '2', {expires: 2592000, path: '/'});
                      $('#open-login').hide();
                      $('#profile-link').show();
//          yaCounter23309737.reachGoal('discount');
                      break;
              }
          });
      }

      $('.devem').click(function (event) {
          var l = $(this);
          if (l.attr('href') !== '#')
              return;
          event.preventDefault();
          var em = 'andriano';
          em += String.fromCharCode(64);
          em += 'ngs.ru';
          l.attr('href', 'mailto:' + em + '?subject=Разработка сайта');
          l.html(em);
      });
  });
</script>
