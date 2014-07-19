<?php
$cs = Yii::app()->getClientScript();
$cs->registerCssFile('/themes/abctoy/css/landpage.css');
$cs->registerScriptFile('/js/countdown.clock.js', CClientScript::POS_END);
$cs->registerScriptFile('/js/moment.min.js', CClientScript::POS_HEAD);
$cs->registerScriptFile($cs->getCoreScriptUrl() . '/jui/js/jquery-ui-i18n.min.js', CClientScript::POS_HEAD);
$this->pageTitle = Yii::app()->name . ' Детские товары и игрушки купить по низкой цене в интернет-магазине игрушек';
?>
<div class="container" style="margin-top: 5px">
  <div class="table" style="padding-bottom: 10px">
    <div class="table-cell valign-middle" style="width: 20%">
      <a href="/"><img width="173" height="57" alt="ABC toy" src="/themes/abctoy/css/logo_shadow.png"></a>
    </div>

    <div class="table-cell valign-middle" style="width: 44%">
      <div class="gray bold" style="position: relative; bottom: 3px; font-size: 14pt">
        <div class="cufon">Интернет-магазин</div>
        <div class="cufon" style="margin: 5px 0">детских товаров и игрушек</div>
        <div class="cufon"><span class="green">Бесплатная доставка</span> по всей России.</div>
      </div>
    </div>
    <div class="table-cell" style="width: 23%; text-align: right">
      <div class="cufon lager" style="padding-bottom: 0.4em">Есть вопросы? Звоните!</div>
      <div class="cufon bold" style="padding-bottom: 0.2em; font-size: 23pt">
        <span class="red">(383)</span><span class="blue"> 375</span><span>-</span><span class="green">03</span><span>-</span><span class="yellow">22</span>
      </div>
      <div>
        <div class="cufon gray" style="font-size: 10pt; padding-bottom: 0.5em"> Мы на связи с 9:00 до 18:00 (GTM +4)</div>
      </div>
      <div class="gray lager">Новосибирск</div>
    </div>
  </div>
</div>
<div id="greenblock">
  <div class="cufon bold">Интернет-магазин <span class="red">игрушек</span> для РАЗУМНЫХ родителей!</div>
</div>
<div id="wb0" class="woodblock">
  <div class="container">
    <div class="inline-blocks">
      <div style="width: 365px; text-align: center; margin: 0 10px">
        <div class="red cufon bold" style="font-size: 32pt;text-align: center; margin: 20px 0">АКЦИЯ!!!</div>
        <div class="cufon bold" style="font-size: 18pt">Укажите возраст вашего</div>
        <div class="cufon bold" style="font-size: 18pt; margin-top: 10px">ребенка и получите скидку</div>
        <div class="cufon red bold" style="font-size: 140pt; height: 144px; text-shadow: white 2px 2px 2px">400</div>
        <div class="cufon red bold" style="font-size: 72pt">рублей</div>
        <div class="cufon bold" style="font-size: 18pt; margin: 10px 0">на первую покупку</div>
        <div class="cufon red" style="margin-bottom: 20px">Так же мы будем рекомендовать только те игрушки которые будут интересны вашему ребенку.</div>
      </div>
      <div style="vertical-align: top; font-size: 9pt; position: relative; margin-top: 10px; min-height: 460px">
        <div>
          <span class="cufon red" style="font-size: 24pt;margin: 10px 0">осталось </span>
          <span class="clock red cufon bold" style="font-size: 32pt" date="<?php echo date('d.m.Y', strtotime("+1 days")); ?>"></span></div>
        <div class="cufon bold" style="font-size: 12pt; margin: 10px 0">получите купон на скидку сейчас, а воспользуйтесь им потом</div>
        <div style="background: white; padding: 10px; border-radius: 5px">
          <div class="inline-blocks">
            <div style="vertical-align: bottom; margin-left: 27px; width: 120px">
              <?php
              echo CHtml::activeLabel($children[0], 'name');
              ?>
            </div>
            <div style="vertical-align: bottom; margin: 0 10px">
              <?php
              echo CHtml::activeLabel($children[0], 'birthday');
              ?>
            </div>
          </div>
          <div class="regform">
            <?php
            $this->renderPartial('//site/_popupForm', array(
              'children' => $children,
              'popup_form' => $popup_form,
            ));
            ?>
          </div>
          <span>Мы обещаем не слать спам, а только сообщать об интересных Вам акциях :)</span>
        </div>
        <div style="position: relativ; margin: 40px">
          <div class="land-page-redbutton bold submit-form">ПОЛУЧИТЬ 400 РУБЛЕЙ</div>
          <img class="submit-process" style="display: none; margin: 0 auto" src="/images/process.gif">
        </div>
      </div>
    </div>
  </div>
</div>
<div id="grayblock">
  <div class="container">
    <div class="inline-blocks">
      <div class="icon-delivery-medium"></div>
      <div style="width: 190px; margin-top: 15px">
        <div class="bold">Бесплатная доставка</div>
        <div>на следующий день</div>
      </div>
      <div class="icon-sclad-medium"></div>
      <div style="width: 190px; margin-top: 15px">
        <div><span class="bold">50 000</span> товаров</div>
        <div>на складе</div>
      </div>
      <div class="icon-guarantee-medium"></div>
      <div style="width: 190px; margin-top: 15px">
        <div>Гарантия на товар</div>
        <div>в течении года</div>
      </div>
    </div>
    <div class="inline-blocks">
      <div class="grayblock-line"></div>
      <div class="grayblock-line"></div>
      <div class="grayblock-line"></div>
    </div>
    <div class="inline-blocks">
      <div class="icon-payment-medium"></div>
      <div style="width: 190px; margin-bottom: 15px">
        <div>Удобнные способы</div>
        <div>оплаты</div>
      </div>
      <div class="icon-brands-medium"></div>
      <div style="width: 190px; margin-bottom: 15px">
        <div>Более 25</div>
        <div>производителей</div>
        <div>сотрудничают с нами</div>
      </div>
      <div class="icon-5yeasr-medium"></div>
      <div style="width: 190px; margin-bottom: 15px">
        <div class="bold">Более 5 лет</div>
        <div>на рынке</div>
        <div>детских игрушек</div>
      </div>
    </div>
  </div>
</div>
<div id="tovar">
  <div class="cufon bold" style="font-size: 26pt; padding: 20px 0 10px">ТОЛЬКО <span class="red">САМЫЕ НИЗКИЕ</span> ЦЕНЫ</div>
  <div style="font-size: 11pt">Мы подобрали специально для вас самые выгодные предложения</div>
  <div>
    <?php
    Yii::import('application.modules.catalog.models.Product');
    $product = Product::model()->findAll(array(
      'condition' => "t.article IN ('Д033', 'Д117', 'Т1405', 'ГД7030', 'Д116')",
      'order' => 't.price'
        )
    );
    foreach ($product as $item)
      $this->renderPartial('_product', array('product' => $item));
    ?>
  </div>
  <div class="cufon bold" style="font-size: 22pt">У нас есть еще <span class="red" style="font-size: 32pt">49 995</span> игрушек по таким же детским ценам!</div>
  <div style="padding: 20px 0 30px">
    <a class="catalog-link" href="/"><div class="land-page-redbutton bold">Смотреть все игрушки</div></a>
  </div>
</div>
<?php
$this->renderPartial('_regform', array(
  'children' => $children,
  'popup_form' => $popup_form,
  'suff' => '1',
));
?>
<div style="text-align: center; margin: 50px 0 5px">
  <div class="cufon bold" style="font-size: 22pt; margin-bottom: 5px">ОТЗЫВЫ О НАШЕЙ РАБОТЕ</div>
  <div style="margin-bottom: 20px">Только реальные отзывы от реальных клиентов</div>
  <div class="container inline-blocks">
    <div style="margin: 0 15px">
      <div class="review-img"><img src="/images/1.jpg"></div>
      <div>Марина Звонова</div>
      <div>дочка, 5 лет</div>
      <div class="gray" style="font-size: 9pt">marina1536@gmail.com</div>
      <div class="note-img"></div>
      <div class="note-txt" style="text-align: left">
        <p>Делала неоднократно заказ в этом магазине, и всегда оставалась очень довольна скоростью обслуживания, начиная от операторов заканчивая доставкой.</p>
        <p>Весь персонал очень вежливый, вещи хорошего качества по достойной цене.</p>
      </div>
    </div>
    <div style="margin: 0 15px">
      <div class="review-img"><img src="/images/2.jpg"></div>
      <div>Светлана Артемьева</div>
      <div>сын и дочка, 5 лет и 1 год</div>
      <div class="gray" style="font-size: 9pt">atr-sveta@mail.ru</div>
      <div class="note-img"></div>
      <div class="note-txt" style="text-align: left">
        <p>Очень понравился магазин, получила скидку на 400 руб. и сразу оформила заказ.</p>
        <p>Была приятно удивлена, когда заказав товар с утра получила его уже вечером в этот же день. Однозначно буду продолжать заказывать игрушки в этом интернет-магазине.</p>
      </div>
    </div>
    <div style="margin: 0 15px">
      <div class="review-img"><img src="/images/3.jpg"></div>
      <div>Екатерина Маркова</div>
      <div>сын, 4 года</div>
      <div class="gray" style="font-size: 9pt">ekaterinamar@mail.ru</div>
      <div class="note-img"></div>
      <div class="note-txt" style="text-align: left">
        <p>Заказывала сыну подарок на новый год, но того что изначально хотела не было в наличии. Мне сразу предложили замену на другой товар и дали скидочку за неудобства:) Заказ в Москву мне доставили на третий день.</p>
        <p>Порадовало отсутствие предоплаты при заказе из другого города.</p>
      </div>
    </div>
    <div style="margin: 0 15px">
      <div class="review-img"><img src="/images/4.jpg"></div>
      <div>Людмила Аршинова</div>
      <div>внук, 2 года</div>
      <div class="gray" style="font-size: 9pt">89529429575@mail.ru</div>
      <div class="note-img"></div>
      <div class="note-txt" style="text-align: left">
        <p>Впервые покупала игрушки через интернет. Заказала игрушки с доставкой на дом. С утра мне позвонили и уточнили информацию о доставке, а в обед нам уже доставили заказ.</p>
        <p>Это был мой первый опыт покупки через интернет и в дальнейшем с удовольствием, приобрету здесь что-нибудь еще!</p>
      </div>
    </div>
  </div>
  <a class="catalog-link" href="/"><div class="land-page-redbutton bold" style="margin: 50px auto">ПОСМОТРЕТЬ КАТАЛОГ</div></a>
</div>
<div class="triangle"></div>
<div class="container">
  <div class="cufon bold" style="font-size: 26pt; color: #666666; margin: 20px 0; text-align: center;"><span style="color: #f00;">САМЫЕ РАСПРОСТРАНЕННЫЕ ПРОБЛЕМЫ</span>,<br /> С КОТОРЫМИ СТАЛКИВАЮТСЯ РОДИТЕЛИ ПРИ ПОКУПКЕ ДЕТСКИХ ТОВАРОВ И ИГРУШЕК</div>
  <table style="width: 960px;" border="0">
    <tbody>
      <tr style="vertical-align: top;">
        <td style="vertical-align: top;" width="40" height="171"><img src="/uploads/sad.png" alt="" width="32" height="32" /></td>
        <td style="vertical-align: baseline; padding-top: 10px;" width="440">
          <div class="cufon " style="color: #000; font-size: 18px; font-weight: bold;"><span style="color: #993333; font-weight: bold;">Проблема 1.</span>&nbsp;Отсутствие доверия к интернет-магазинам.</div>
          <br />
          <p style="font-size: 14px; line-height: 1.5;">Можно ли доверять этому интернет-магазину? Откуда мне знать, получу ли я свой заказ?&nbsp;Этот и многие другие вопросы возникают у большинства покупателей.</p>
        </td>
        <td style="vertical-align: top;" width="40"><img src="/uploads/good.png" alt="" width="32" height="32" /></td>
        <td style="vertical-align: baseline; padding-top: 10px;" width="440">
          <div class="cufon " style="color: #000; font-size: 18px; font-weight: bold;"><span style="color: #669933; font-weight: bold;">Наше решение:</span>&nbsp;Крупная и надежная компания, которой можно доверять.</div>
          <br />
          <p style="font-size: 14px; line-height: 1.5;">За пять лет своего существования мы смогли доказать, что являемся надежным продавцом качественных товаров. А лучшее доказательство тому стало наше сотрудничество с более чем 700 компаниями, располагающимися в разных уголках России.&nbsp;</p>
        </td>
      </tr>
      <tr style="vertical-align: top;">
        <td style="vertical-align: top;" width="40" height="171"><img src="/uploads/sad.png" alt="" width="32" height="32" /></td>
        <td style="vertical-align: baseline; padding-top: 10px;" width="440">
          <div class="cufon " style="color: #000; font-size: 18px; font-weight: bold;"><span style="color: #993333; font-weight: bold;">Проблема 2.</span>&nbsp;<strong>Несвоевременная и дорогая доставка в интернет-магазинах.</strong></div>
          <br />
          <p style="font-size: 14px; line-height: 1.5;">Этот стереотип появился из за того, что многие интернет-магазины осуществляют доставку с помощью "Почты России", которая может доставлять Ваш заказ очень долго.</p>
        </td>
        <td style="vertical-align: top;" width="40"><img src="/uploads/good.png" alt="" width="32" height="32" /></td>
        <td style="vertical-align: baseline; padding-top: 10px;" width="440">
          <div class="cufon " style="color: #000; font-size: 18px; font-weight: bold;"><span style="color: #669933; font-weight: bold;">Наше решение:</span>&nbsp;Сотрудничество с профессионалами&nbsp;в сфере доставки</div>
          <br />
          <p style="font-size: 14px; line-height: 1.5;">Благодаря сотрудничеству с известной логистической компанией &laquo;CDEK&raquo; скорость и цена доставки заказа всегда радует наших клиентов. После осуществления покупки в нашем магазине пройдет всего 1-3 дня как заказ будет доставлен в любой город России.</p>
        </td>
      </tr>
      <tr style="vertical-align: top;">
        <td style="vertical-align: top;" width="40" height="171"><img src="/uploads/sad.png" alt="" width="32" height="32" /></td>
        <td style="vertical-align: baseline; padding-top: 10px;" width="440">
          <div class="cufon " style="color: #000; font-size: 18px; font-weight: bold;"><span style="color: #993333; font-weight: bold;">Проблема 3.</span>&nbsp;<strong>Отсутствие необходимого товара в интернет-магазине.&nbsp;</strong></div>
          <br />
          <p style="font-size: 14px; line-height: 1.5;">Так как многие интернет-магазины не имеют собственных складов с продукцией, время поставки товаров часто задерживается на очень длительный срок.&nbsp;</p>
        </td>
        <td style="vertical-align: top;" width="40"><img src="/uploads/good.png" alt="" width="32" height="32" /></td>
        <td style="vertical-align: baseline; padding-top: 10px;" width="440">
          <div class="cufon " style="color: #000; font-size: 18px; font-weight: bold;"><span style="color: #669933; font-weight: bold;">Наше решение:</span> Собственный большой склад</div>
          <br />
          <p style="font-size: 14px; line-height: 1.5;">Мы имеем в своем распоряжении большой склад, расположенный в Новосибирске. У нас всегда в наличии есть более 50 000 товаров, представленных на нашем сайте. Поэтому выбранный товар сразу же отправляется заказчику.</p>
        </td>
      </tr>
      <tr style="vertical-align: top;">
        <td style="vertical-align: top;" width="40" height="171"><img src="/uploads/sad.png" alt="" width="32" height="32" /></td>
        <td style="vertical-align: baseline; padding-top: 10px;" width="440">
          <div class="cufon " style="color: #000; font-size: 18px; font-weight: bold;"><span style="color: #993333; font-weight: bold;">Проблема 4.</span>&nbsp;<strong>Сложность в выборе идеальной игрушки для Вашего ребенка.</strong></div>
          <br />
          <p style="font-size: 14px; line-height: 1.5;">Часто при выборе игрушки для своего ребенка родители теряются среди разнообразия товаров.&nbsp;</p>
        </td>
        <td style="vertical-align: top;" width="40"><img src="/uploads/good.png" alt="" width="32" height="32" /></td>
        <td style="vertical-align: baseline; padding-top: 10px;" width="440">
          <div class="cufon " style="color: #000; font-size: 18px; font-weight: bold;"><span style="color: #669933; font-weight: bold;">Наше решение:</span>&nbsp;Интеллектуальная система поиска товара.</div>
          <br />
          <p style="font-size: 14px; line-height: 1.5;">Мы создали удобную систему фильтрации предлагаемых вам игрушек. Всего в несколько кликов мыши вы сможете легко выбрать подходящую категорию, возрастной промежуток и сумму, которую будете готовы потратить на свое чадо.</p>
        </td>
      </tr>
    </tbody>
  </table>
</div>
<?php
$this->renderPartial('_regform', array(
  'children' => $children,
  'popup_form' => $popup_form,
  'suff' => '2',
));
?>
<div class="container" style="margin-top: 30px">
  <div class="table" style="padding-bottom: 10px">
    <div class="table-cell valign-middle" style="width: 20%">
      <a href="/"><img width="173" height="57" alt="ABC toy" src="/themes/abctoy/css/logo_shadow.png"></a>
      <div>2013. Все права защищены.</div>
      <div>ABC toy - продажа игрушек.</div>
    </div>
    <div class="table-cell" style="width: 23%; text-align: right">
      <div class="cufon lager" style="padding-bottom: 0.4em">Есть вопросы? Звоните!</div>
      <div class="cufon bold" style="padding-bottom: 0.2em; font-size: 23pt">
        <span class="red">(383)</span><span class="blue"> 375</span><span>-</span><span class="green">03</span><span>-</span><span class="yellow">22</span>
      </div>
      <div>
        <div class="cufon gray" style="font-size: 10pt; padding-bottom: 0.5em"> Мы на связи с 9:00 до 18:00 (GTM +4)</div>
      </div>
      <div class="gray lager">Новосибирск</div>
    </div>
  </div>
</div>
<?php $this->renderPartial('//site/_addProductModal'); ?>
<div id="popup-window" style="display: none"></div>
<?php Yii::app()->clientScript->registerScriptFile('http://vk.com/js/api/share.js?90', CClientScript::POS_HEAD); ?>
<script type="text/javascript">
  $('.woodblock').on('focus', '.date', function() {
    $('.woodblock .date').datepicker($.extend({
      changeMonth: true,
      changeYear: true,
      yearRange: "-30:+00",
    }, $.datepicker.regional['ru']));
  });

  var incAttr = function(match) {
    return parseInt(match) + 1;
  }

  $('.woodblock').on('click', '.add-child', function() {
    var count = $(this).parent().parent().find('.child').length;
    if (count>3)
      return;
    else if(count>2)
      $(this).parent().css('display', 'none');
    $('.woodblock .date').datepicker('destroy');
    var child = $(this).parent().parent().find('.child:last-child').clone(true);
    child[0].id = child[0].id.replace(/\d+/, incAttr);
    child.find('.icon-remove-child-small').css('display', 'inherit');
    var chname = child.find('.name');
    chname[0].id = chname[0].id.replace(/\d+/, incAttr);
    chname[0].name = chname[0].name.replace(/\d+/, incAttr);
    chname[0].value = '';
    var chbday = child.find('.date');
    chbday[0].id = chbday[0].id.replace(/\d+/, incAttr);
    chbday[0].name = chbday[0].name.replace(/\d+/, incAttr);
    chbday[0].value = '';
    var gender = child.find('input[type=hidden]');
    gender[0].id = gender[0].id.replace(/\d+/, incAttr);
    name = gender[0].name.replace(/\d+/, incAttr);
    gender[0].name = name;
    var gender0 = child.find('input[type=radio][value=1]');
    gender0[0].id = gender0[0].id.replace(/\d+/, incAttr);
    gender0[0].name = name;
    gender0.prop('checked', false);
    var gender0l = $(gender0).next();
    $(gender0l[0]).attr('for', $(gender0l[0]).attr('for').replace(/\d+/, incAttr));
    var gender1 = child.find('input[type=radio][value=2]');
    gender1[0].id = gender1[0].id.replace(/\d+/, incAttr);
    gender1[0].name = name;
    gender1.prop('checked', false);
    var gender1l = $(gender1).next();//child.find('label[for=Child_0_gender_id_1]');
    $(gender1l[0]).attr('for', $(gender1l[0]).attr('for').replace(/\d+/, incAttr));
    var children = $(this).parent().parent().find('.children');
    child.appendTo(children);
  });

  $('.woodblock').on('click', '.icon-remove-child-small', function() {
    $(this).parents('.regform').find('.add-block').css('display', 'inherit');
    $(this).parent().parent().remove();
  });

  $('.woodblock').on('click', '.submit-form', function() {
    $(this).css('display', 'none');
    $(this).next().css('display', 'inherit');
    var children = [];
    var wb = $(this).closest('.woodblock');
    var suff = wb[0].id.match(/\d+/)[0];
    $(wb).find('.child').each(function() {
      var name = $(this).find('.name').val();
      var date = $(this).find('.date').val();
      var gender = $(this).find('input[type=radio]:checked').val();
      children.push({name: name, birthday: date, gender_id: gender});
    });
    var accept = $(wb).find('input[type=checkbox]').prop('checked') ? 1 : 0;
    var email = $(wb).find('input[type=email]').val();
    $.post('/popupWindow', {
      children: children,
      PopupForm: {accept: accept, email: email},
      suff: suff
    }, function(data) {
      $(wb).find('.submit-process').css('display', 'none');
      $(wb).find('.submit-form').css('display', 'inherit');
      var result = JSON && JSON.parse(data) || $.parseJSON(data);
      switch (result.result) {
        case 'error':
          $(wb).find('.regform').html(result.html);
          $(wb).find('input[type="radio"][class~="error"], input[type="checkbox"][class~="error"]')
                  .parent()
                  .css('border', '1px solid #cc3333')
                  .css('border-radius', '5px')
                  .css('padding', '4px');
          break;
        case 'exist':
          $('#popup-window').dialog('option', 'height', 120);
          $('#popup-window').dialog('option', 'width', 310);
          $('#popup-window').dialog('option', 'dialogClass', 'popup-email-exist');
          $('#popup-window').html(result.html);
          $('#popup-window').dialog('open');
          setTimeout(function() {
            $('#popup-window').dialog('close');
          }, 5000);
          break;
        case 'register':
          $.cookie('popup', '2', {expires: 30});
          $('#popup-window').dialog('option', 'height', 500);
          $('#popup-window').dialog('option', 'width', 930);
          $('#popup-window').dialog('option', 'dialogClass', 'popup-window');
          $('#popup-window').load('/popupWindow', function() {
            $('#popup-body').html(result.html);
            Cufon.replace('#popup-window .cufon');
            $('#popup-window').on('dialogclose', function(event, ui) {
              window.location.href = '/';
            });
            $('#popup-window').dialog('open');
          });
          break;
      }
    });
  });

  $('#popup-window').dialog({
    modal: true,
    resizable: false,
    width: 930,
    height: 500,
    autoOpen: false,
    dialogClass: 'popup-window',
    draggable: false,
    create: function(event, ui) {
      $(event.target).parent().css('position', 'fixed');
    }
  });
  $('#popup-window').on('click', '.popup-close', function() {
    $('#popup-window').dialog('close');
  });
</script>