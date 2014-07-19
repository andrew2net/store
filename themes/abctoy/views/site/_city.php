<div id="city" class="gray" style="font-size: 14pt; font-weight: normal; margin-top: 3px">
  <?php
  Yii::import('application.controllers.SiteController');
  Yii::import('application.models.CustomerProfile');
  $profile = SiteController::getProfile();
  $value = empty($profile->city) ? 'Новосибирск' : $profile->city;
  echo $value;
  ?>
</div>
<div id="change-city" class="gray underline-dashed" style="cursor: pointer; font-weight: normal">я в другом городе</div>
<div id="input-city" style="display: none; width: 125px">
  <?php
  $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
    'id' => 'suggest-city',
    'name' => 'input-city',
    'value' => $value,
    'sourceUrl' => '/site/suggestcity',
    'htmlOptions' => array('class' => 'input-text', 'style' => 'width:170px; margin: 10px 0 0'),
  ));
  ?>
</div>
<script type="text/javascript">
  var esc = false;

  $('#change-city').click(function() {
    $('#city, #change-city').hide();
    $('#input-city').show();
    var input = $('#suggest-city').get(0);
    input.value = $.trim($('#city').html());
    var length = input.value.length;
    input.selectionStart = length;
    input.selectionEnd = length;
    input.focus();
  });

  $('#suggest-city').on('keypress', function(e) {
    var code = e.keyCode || e.which;
    if (code == 13)
      changeCity();
    else if (code == 27) {
      esc = true;
      closeEdit();
    }
  });

  $('#suggest-city').focusout(function() {
    if (!esc) {
      var city = $.trim(this.value);
      changeCity(city);
    }
    esc = false;
  });

  $('#suggest-city').on('autocompleteselect', function(event, elem) {
    changeCity(elem.item.value);
  });

  function changeCity(city) {
    if (city.length) {
      $('#city').html(city);
      $.post('/site/savecity', {city: city});
    }
    closeEdit()
  }
  function closeEdit() {
    $('#city, #change-city').show();
    $('#input-city').hide();
  }
</script>