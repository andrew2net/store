<div id="wb<?php echo isset($suff) ? $suff : ''; ?>" class="woodblock" style="box-shadow: 0px 0px 20px">
  <div class="container">
    <div class="inline-blocks">
      <div style="width: 365px; text-align: center; margin: 30px 10px">
        <div class="cufon bold" style="font-size: 18pt">Укажите возраст вашего</div>
        <div class="cufon bold" style="font-size: 18pt; margin-top: 10px">ребенка и получите скидку</div>
        <div class="cufon red bold" style="font-size: 140pt; height: 144px; text-shadow: white 2px 2px 2px">400</div>
        <div class="cufon red bold" style="font-size: 72pt">рублей</div>
        <div class="cufon bold" style="font-size: 22pt; margin: 10px 0 0">на первую покупку</div>
      </div>
      <div style="vertical-align: top; font-size: 9pt; position: relative; margin: 10px 0 40px">
        <div style="margin: 10px 0">
          <span class="cufon red" style="font-size: 24pt">осталось </span>
          <span class="clock red cufon bold" style="font-size: 32pt" date="<?php echo date('d.m.Y', strtotime("+1 days")); ?>"></span></div>
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
            $params = array(
              'children' => $children,
              'popup_form' => $popup_form,
            );
            if (isset($suff))
              $params['suff'] = $suff;
            $this->renderPartial('//site/_popupForm', $params);
            ?>
          </div>
          <span>Мы обещаем не слать спам, а только сообщать об интересных Вам акциях :)</span>
        </div>
        <div style="position: relative; bottom: -20px">
          <div class="land-page-redbutton bold submit-form">ПОЛУЧИТЬ 400 РУБЛЕЙ</div>
          <img class="submit-process" style="display: none; margin: 0 auto" src="/images/process.gif">
        </div>
      </div>
    </div>
  </div>
</div>
