$(function() {
  $('.jcarousel')
          .jcarousel({
    wrap: 'circular'
  }).jcarouselAutoscroll({
    interval: 5000,
    create: $('.jcarousel').hover(function() {
      $(this).jcarouselAutoscroll('stop');
    },
            function() {
              $(this).jcarouselAutoscroll('start');
            })
  });
});

$(function (){
  $('#coin-slider').coinslider({width:1200, height: 270, links: false, navigation: true, effect: rain});
});

$(function() {
  $('.weekcarousel')
          .jcarousel({
    wrap: 'circular'
  }).jcarouselAutoscroll({
    target: '+=1',
    interval: 7000,
    create: $('.weekcarousel').hover(function() {
      $(this).jcarouselAutoscroll('stop');
    },
            function() {
              $(this).jcarouselAutoscroll('start');
            })
  });
  $('.weekcarousel-prev').jcarouselControl({
    target: '-=4'
  });

  $('.weekcarousel-next').jcarouselControl({
    target: '+=4'
  });
});

$(function() {
  $('.top10carousel')
          .jcarousel({
    wrap: 'circular',
  }).jcarouselAutoscroll({
    target: '+=1',
    interval: 7000,
    create: $('.top10carousel').hover(function() {
      $(this).jcarouselAutoscroll('stop');
    },
            function() {
              $(this).jcarouselAutoscroll('start');
            })
  });
  $('.top10carousel-prev').jcarouselControl({
    target: '-=4'
  });

  $('.top10carousel-next').jcarouselControl({
    target: '+=4'
  });
});

$(function() {
  $('.brands')
          .jcarousel({
    wrap: 'circular'
  }).jcarouselAutoscroll({
    target: '+=1',
    interval: 7000,
    create: $('.brands').hover(function() {
      $(this).jcarouselAutoscroll('stop');
    },
            function() {
              $(this).jcarouselAutoscroll('start');
            })
  });
  $('.brands-prev').jcarouselControl({
    target: '-=4'
  });

  $('.brands-next').jcarouselControl({
    target: '+=4'
  });
});
