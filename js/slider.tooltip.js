function sliderIniTooltip(slider) {
  $(slider).each(function(i, el) {
    var values = $(el).slider("option", "values");
    $(el).find(".ui-slider-handle").each(function(index, element) {
      this.tooltip = $('<div class="slider-tooltip" />').text(values[index]);
      $(element).append(this.tooltip);
    });
  });
  return;
}

function sliderMoveTooltip(ui) {
  $(ui.handle.tooltip).text(ui.value);
  return;
}


