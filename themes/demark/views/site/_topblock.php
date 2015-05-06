<div class="container">
    <div class="table" style="padding-bottom: 10px; margin: 0">
        <div class="table-cell valign-middle" style="width: 250px">
            <a href="/"><img width="248" height="47" alt="DeMARK" src="/themes/<?php echo Yii::app()->theme->name; ?>/img/logo.png"/></a>
        </div>
        <div class="table-cell" style="position: relative">
            <div style="position: absolute; top: 20px; left: 40px">
                <div class="bold blue">Звоните, заказывайте</div>
                <!--<div class="inline-blocks">-->
                <?php
                Yii::import('application.modules.admin.models.Page');
                Yii::import('application.controllers.ProfileController');
                $profile = ProfileController::getProfile();
                $locale = isset($_GET['language']) ? strtoupper($_GET['language']) : $profile->price_country;
                foreach (Yii::app()->params['phones'][$locale] as $phone) {
                  if (is_array($phone)) {
                    ?>
                    <!--<div>-->
                    <span class="blue" style="position: relative; top: 3px; font-size: 14pt"><?php echo $phone['cod']; ?></span>
                    <span class="blue bold" style="font-size: 20pt; vertical-align: middle"><?php echo $phone['num']; ?></span>
                    <!--</div>-->
                    <?php
                  } else {
                    ?>
                    <!--<div>-->
                    <span class="blue" style="font-size: 16pt; vertical-align: middle"><?php echo $phone; ?></span>
                    <!--</div>-->
                    <?php
                  }
                }
                ?>
                <!--</div>-->
            </div>
            <div class="inline-blocks" style="text-align: right; margin-bottom: 20px; position: relative">
                <div>
                    <?php
                    $items = Yii::app()->db->createCommand()
                        ->select("title AS label, CONCAT('/', LOWER(lang), '/info/', url) AS url")
                        ->from('{{page}}')
                        ->where('menu_show>0 AND url<>"/" AND lang=:lang', ['lang' => $locale])
                        ->order('menu_show')->queryAll();

                    $this->widget('zii.widgets.CMenu', array(
                      'items' => $items,
                      'id' => 'topmenu',
                      'htmlOptions' => array('style' => 'margin: 5px 20px 20px 0'),
                      'itemCssClass' => 'top-menu-item',
                      'firstItemCssClass' => 'top-menu-first',
                    ));
                    if (Yii::app()->user->isGuest) {
                      $login_display = '';
                      $logout_display = ' style="display:none"';
                      $profile_display = ' style="display:none"';
                    } else if ($this instanceof ProfileController) {
                      $login_display = ' style="display:none"';
                      $logout_display = '';
                      $profile_display = ' style="display:none"';
                    } else {
                      $login_display = ' style="display:none"';
                      $logout_display = ' style="display:none"';
                      $profile_display = '';
                    }
                    ?>
                </div>
                <div style="width: 150px; position: relative">
                    <a id="open-login" href="/login"<?php echo $login_display; ?>>ВХОД</a>
                    <a id="profile-link" href="/profile"<?php echo $profile_display; ?>>ЛИЧНЫЙ КАБИНЕТ</a>
                    <a id="logout-link" href="/logout"<?php echo $logout_display; ?>>ВЫХОД</a>
                    <div id="login-dialog">
                        <span class="close-dialog right" title="Закрыть диалог"></span>
                        <div style="margin-top: 10px"><?php echo CHtml::label('Имя или Email', 'login'); ?></div>
                        <div><?php echo CHtml::textField('login', ''); ?></div>
                        <div style="margin-top: 10px"><?php echo CHtml::label('Пароль', 'password'); ?></div>
                        <div><?php echo CHtml::passwordField('password', ''); ?></div>
                        <div style="text-align: right"><a href="/user/recovery">восстановить пароль</a></div>
                        <div style="text-align: center; height: 16px; margin: 8px auto 2px">
                            <span id="error-msg" class="red" style="display: none">неверное имя или пароль</span>
                        </div>
                        <div class="login-submit"><div>Войти</div></div>
                        <div style="text-align: center; margin: 10px auto 5px;"><a href="/user/registration">зарегистрироваться</a></div>
                    </div>
                </div>
            </div>
            <div style="float: right; margin: 4px 5px; position: relative">
                <?php
                echo CHtml::beginForm('/search', 'get', array('id' => 'search-form', 'style' => 'display:inline-block; margin: 0'));
                $search = new Search;
                echo CHtml::activeTextField($search, 'text', array(
                  'style' => 'border: none; width: 180px; height: 23px; padding: 0 0 2px 10px; float: left; background:whitesmoke; 
            box-shadow: 0 0 1px inset; border-radius: 0; margin: 0',
                  'placeholder' => 'Поиск'
                ));
                echo CHtml::submitButton('', array(
                  'style' => 'margin: 0 0 0 -4px; border: none; float: left; box-shadow: 0 0 1px inset',
                  'class' => 'iconsearch'
                ));
                switch ($locale) {
                  case 'KZ':
                    $country_code = $locale;
                    $flag = 'flag_kz';
                    break;
                  default :
                    $country_code = 'RU';
                    $flag = 'flag_ru';
                }
                echo CHtml::endForm();
                ?>
                <div style="display: inline-block; width: 48px; height: 24px; margin-left: 20px; position: relative; top: -8px">
                    <span id="country" class="wrapper-country <?php echo $flag ?>"><?php echo $country_code; ?></span>
                </div>
                <div id="country-select" style="display: none; position: absolute; top: 6px; right: -3px; width: 46px; height: 52px; border: solid 1px #BBB; padding-left: 4px; background-color: white; box-shadow: 1px 1px 2px -1px; z-index: 300">
                    <div id="select-ru" class="flag_ru" style="position: absolute; top: -7px">RU</div>
                    <div id="select-kz" class="flag_kz" style="position: absolute; top: 20px">KZ</div>
                </div>
            </div>
        </div>

        <?php // $this->renderPartial('//site/_city');       ?>
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
  var open_login = $('#open-login');
  var login_dialog = $('#login-dialog');
  var close_dialog = login_dialog.find('span.close-dialog');

  function closeLoginDialog() {
      var pos = getLoginPos();
      close_dialog.hide();
      login_dialog.hide('scale', {origin: [pos.top, pos.left]});
  }

  function getLoginPos() {
      var pos = open_login.position();
      return {top: pos.top - 20, left: pos.left + 150};
  }

  $(function () {
      var from;
      var requestMethod = $('#request-method');
      var reloadPost = $('#reload-post');

      open_login.click(function (event) {
          event.preventDefault();
          event.stopPropagation();
          if (login_dialog.css('display') == 'none') {
              var pos = getLoginPos();
              login_dialog.show('scale', {origin: [pos.top, pos.left]}, function () {
                  close_dialog.show();
              });
          }
          else {
              closeLoginDialog();
          }
      });

      close_dialog.click(function () {
          closeLoginDialog();
      });

      $('.login-submit div').click(function () {
          var login = $('#login').val();
          var passw = $('#password').val();
          $.post('/login', {
              login: login,
              passw: passw
          }, function (data) {
//      $('#sent-mail-recovery').css('display', 'none');
              var result = JSON && JSON.parse(data) || $.parseJSON(data);
              if (result.result) {
                  closeLoginDialog();
                  open_login.hide();
                  $('#profile-link').show();
                  $('#shoppingCart').html(result.cart);
                  window.location.reload();
//        $('#bottom-bar').css('display', 'none');
//        $('#footer').css('margin-bottom', '0');
              } else {
                  $('#error-msg').show();
//        $('.passw-err').css('display', 'inline');
//        $('#recover-password').css('display', 'inline');
              }
          });
      });

      $('#callback-link').click(function () {
          from = $(this).position();
          $('#callback-overlay').show();
          $('#callback-box').show('scale', {origin: [from.top, from.left + 60]});
      });

      function closeCallbackWindow() {
          $('#callback-box').hide('scale', {origin: [from.top, from.left + 60]}, function () {
              $('#callback-overlay').hide();
              $('#callback-tel, #callback-name').css('border', 'none');
          });
      }

      $('#callback-cancel').click(function () {
          closeCallbackWindow();
      });

      $('#callback-submit').click(function () {
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
              $.post('/site/callback', {phone: phone, name: name, note: note}, function (data) {
                  $('#callback-process').hide();
                  if (data == 'ok') {
                      $('#callback-result').html('Завявка успешно отправлена.<br>В ближайшее время Вам перезвонят.');
                  } else {
                      $('#callback-result').html('Не удалось отправить заявку.');
                  }
                  $('#callback-result').show();
                  setTimeout(function () {
                      closeCallbackWindow();
                  }, 5000);
              });
          }
      });

      $('.iconsearch').click(function (event) {
          event.preventDefault();
          if ($('#Search_text').val())
              $('#search-form').submit();
      });

      var country = $('#country');
      var country_select = $('#country-select');
      var select = $('#select-ru, #select-kz');

      country.click(function (event) {
          event.stopPropagation();
          country.hide();
          country_select.slideDown()();
      });
      $(document).click(function (event) {
          var contained = $.contains(login_dialog[0], event.target);
          if (event.target.id !== login_dialog.attr('id') && !contained)
              closeLoginDialog();

          country_select.slideUp(function () {
              country.show();
          });
      });
      select.click(function (event) {
          if (this.innerHTML !== country.html()) {
              event.stopPropagation();
              country_select.slideUp();
              $.post('/profile/savecountry', {country: this.innerHTML}, function () {
                  if (requestMethod.attr('content') === 'POST') {
                      reloadPost.val(1);
                      $('form').submit();
                  } else
                      window.location.reload();
              });
          }
      });
  });
</script>
