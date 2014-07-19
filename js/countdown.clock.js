function updateClock( )
{
  $('.clock').each(function(index, clock) {
    var date = moment($(clock).attr('date'), 'DD-MM-YYYY').endOf('day');
    var currentTime = moment();
    var daysLeft = date.diff(currentTime, 'days');
    if (daysLeft < 0)
      daysLeft = 0;
    currentTime = currentTime.add('days', daysLeft);
    var hoursLeft = date.diff(currentTime, 'hours');
    if (hoursLeft < 0)
      hoursLeft = 0;
    currentTime = currentTime.add('hours', hoursLeft);
    var minutesLeft = date.diff(currentTime, 'minutes');
    if (minutesLeft < 0)
      minutesLeft = 0;
    currentTime = currentTime.add('minutes', minutesLeft);
    var secondsLeft = date.diff(currentTime, 'seconds');
    if (secondsLeft < 0)
      secondsLeft = 0;
    var pad = '00';
    minutesLeft = (pad + minutesLeft).slice(-pad.length);
    secondsLeft = (pad + secondsLeft).slice(-pad.length);
    var strTimeLeft = '';
    if (daysLeft > 0) {
      var den = [1, 21, 31, 41];
      var dnj = [2, 3, 4, 22, 23, 24, 32, 33, 34, 42, 43, 44];
      var daySuffix = ' дней ';
      if ($.inArray(daysLeft, den) > -1) {
        daySuffix = ' день ';
      } else if ($.inArray(daysLeft, dnj) > -1) {
        daySuffix = ' дня ';
      }
      strTimeLeft += daysLeft + daySuffix;
    }
    strTimeLeft += hoursLeft + ':' + minutesLeft + ':' + secondsLeft;
    $(clock).html(strTimeLeft);
  });
//  Cufon.replace(".clock");
}

$(document).ready(function() {
  updateClock();
  setInterval(updateClock, 1000);
});