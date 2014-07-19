<div id="topmenu">
  <div class="container">
    <?php
    $this->widget('zii.widgets.CMenu', array(
      'encodeLabel'=>FALSE,
      'items' => array(
        array('label' => 'О компании', 'url' => array('/about')),
        array('label' => 'Доставка', 'url' => array('/deliver')),
        array('label' => 'Оплата', 'url' => array('/payment')),
        array('label' => 'Гарантии и обмен', 'url' => array('/guarantee')),
        array('label' => 'Скидки для детских садов', 'url' => array('/site/page/url/kindergarten')),
        array(
          'label' => $this->cartLabel(),
          'url' => array('/cart'),
          'linkOptions' => array('id' => 'shoppingCart'),
          'itemOptions' => array('class' => 'align-right icon-cart'),
        ),
        array(
          'label' => 'Войти',
          'url' => array('#'),
//          'visible' => Yii::app()->user->isGuest,
          'itemOptions' => array(
            'id' => 'login-menu',
            'class' => 'align-right',
            'style' => 'padding-right: 50px',
            'style' => 'display:' . (Yii::app()->user->isGuest ? 'inherit' : 'none')
          ),
        ),
        array(
          'label' => 'Личный кабинет',
          'url' => array('/profile'),
//          'visible' => !Yii::app()->user->isGuest,
          'itemOptions' => array(
            'id' => 'profile-menu',
            'class' => 'align-right',
            'style' => 'display:' . (Yii::app()->user->isGuest ? 'none' : 'inherit')
          ),
        ),
      ),
//      'htmlOptions'=>array('style'=>'line-height: 2em')
    ))
    ?>
  </div>
</div>
<div id="login-dialog" style="display: none">
  <div id="close-login-dialog" class="blue" style="text-align: right; cursor: pointer;font-size: 8pt">Закрыть окно</div>
  <div id="login-block" class="inline-blocks">
    <div style="display: inline-block; vertical-align: top">
      <div><?php echo CHtml::label('Имя или Email', 'login'); ?></div>
      <div><?php echo CHtml::textField('login', '', array('style' => 'width: 150px')); ?></div>
    </div>
    <div style="display: inline-block; vertical-align: top; margin-left: 3px">
      <div><?php echo CHtml::label('Пароль', 'password'); ?></div>
      <div><?php echo CHtml::passwordField('password', '', array('style' => 'width: 150px')); ?></div>
    </div>
  </div>
  <div id="registr-block" style="display: none">
    <div style="display: inline-block; vertical-align: top">
      <div><?php echo CHtml::label('Ваш Email', 'email'); ?></div>
      <div><?php echo CHtml::textField('email', '', array('style' => 'width: 300px')); ?></div>
    </div>
  </div>
  <div style="height: 10px; font-size: 8pt">
    <span id="error-msg" style="display: none; margin-right: 15px" class="red passw-err"></span>
    <span id="recover-password" class="blue passw-err" style="cursor: pointer; display: none">Восстановить доступ</span>
  </div>
  <div class="right blue" style="cursor: pointer;margin: 5px 0 5px" id="submit-password">Вход</div>
  <div  style="margin-top: 5px">
    <div id="registr" class="blue" style="cursor: pointer">Зарегистрироваться</div>
    <div style="cursor: pointer;margin-bottom: 10px; display: none" id="submit-registr" class="blue">Регистрация</div>
    <img src="/images/process.gif" style="display: none; margin: 5px 35px" id="loading-dialog" />
    <div id="sent-mail-recovery" style="font-size: 9pt; display: none; cursor: pointer"></div>
  </div>
</div>
<script type="text/javascript">

  $(function() {
    $("#login-dialog").dialog({
      autoOpen: false,
      minHeight: 80,
      draggable: false,
      resizable: false,
      width: 350,
      position: {
        at: 'right-40 top+90',
        of: '#login-menu'
      },
      dialogClass: "login-dialog",
      create: function(event, ui) {
        $(event.target).parent().css('position', 'fixed');
      },
      close: function() {
        $('.passw-err').css('display', 'none');
//        $('#recover-password').css('display', 'none');
        $('#loading-dialog').css('display', 'none');
        $('#sent-mail-recovery').css('display', 'none');
        $('#registr').css('display', 'inherit');
        $('#login-block').css('display', 'inherit');
        $('#submit-password').css('display', 'inherit');
//        $('#recover-password').css('display', 'none');
        $('#registr-block').css('display', 'none');
        $('#submit-registr').css('display', 'none');
      },
      show: {
        effect: "blind",
        duration: 500
      },
      hide: {
        effect: "blind",
        duration: 500
      }
    });
  });

  $('#submit-password').click(function() {
    var login = $('#login').val();
    var passw = $('#password').val();
    $.post('/login', {
      login: login,
      passw: passw
    }, function(data) {
      $('#sent-mail-recovery').css('display', 'none');
      var result = JSON && JSON.parse(data) || $.parseJSON(data);
      if (result.result) {
        $("#login-dialog").dialog('close');
        $('#login-menu').css('display', 'none');
        $('#profile-menu').css('display', 'inherit');
        $('#shoppingCart').html(result.cart);
        $('#bottom-bar').css('display', 'none');
        $('#footer').css('margin-bottom', '0');
      } else {
        $('#error-msg').html('Неверное имя или пароль');
        $('.passw-err').css('display', 'inline');
//        $('#recover-password').css('display', 'inline');
      }
    });
  });

  $('#recover-password').click(function() {
    var login = $('#login').val();
    $('.passw-err').css('display', 'none');
    $('#registr').css('display', 'none');
    $('#loading-dialog').css('display', 'inline');
    $.post('/user/recovery/passwrecover', {
      login: login
    }, function(data) {
      $('#loading-dialog').css('display', 'none');
      var result = JSON && JSON.parse(data) || $.parseJSON(data);
      if (result.result) {
        $('#sent-mail-recovery').html('Инструкции для восстановления пароля высланы на Email ' + result.email).css('color', '#99cc33').css('margin-top', '0');
        $('#sent-mail-recovery').css('display', 'inherit');
      } else {
        $('#sent-mail-recovery').html('Не найден пользователь или Email').css('color', '#cc3333').css('margin-top', '5px');
        $('#sent-mail-recovery').css('display', 'inherit');
      }
    });
  });

  $('#submit-registr').click(function() {
    var email = $('#email').val();
    $(this).hide();
    $('#loading-dialog').show();
    $.post('/registr', {email: email}, function(data) {
      $('#loading-dialog').hide();
      var result = JSON && JSON.parse(data) || $.parseJSON(data);
      if (result.result) {
        $("#login-dialog").dialog('close');
        window.location.href = '/profile';
      } else {
        $('#error-msg').html(result.msg);
        $('#error-msg').show();
        $('#submit-registr').show();
      }
    });
  });

  $('#sent-mail-recovery').click(function() {
    $(this).css('display', 'none');
    $('#recover-password').css('display', 'inline');
    $('#registr').css('display', 'inherit');
  });

  $('#registr').click(function() {
    $(this).css('display', 'none');
    $('#login-block').css('display', 'none');
    $('#submit-password').css('display', 'none');
    $('.passw-err').css('display', 'none');
    $('#registr-block').css('display', 'inherit');
    $('#submit-registr').css('display', 'inline');
  });

  $('#login-menu a').click(function(event) {
    event.preventDefault();
    if ($('#login-dialog').dialog('isOpen'))
      $("#login-dialog").dialog('close');
    else
      $('#login-dialog').dialog('open');
  });

  $('#close-login-dialog').click(function() {
    $("#login-dialog").dialog('close');
  });

</script>
