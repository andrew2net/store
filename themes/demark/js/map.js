$(function () {
    var storeMap, place;
    var places = {kz1: [51.16454429, 71.4732065], kz2: [43.2418193, 76.8451685], kz3: [43.3003128, 76.9013665]};
    $.ajax('http://api-maps.yandex.ru/2.1/?lang=ru_RU', {dataType: 'script', cache: true}).done(function () {
        ymaps.ready(function () {
            storeMap = new ymaps.Map('contact_map', {center: places[$('input:checked[type="radio"][name="place"]').val()], zoom: 15});
            $.each(places, function () {
                place = new ymaps.Placemark(this, {}, {preset: 'islands#blueDotIcon'});
                storeMap.geoObjects.add(place);
            });
        });
    });
    $('input[type="radio"][name="place"]').change(function () {
        storeMap.panTo(places[this.value], {duration: 1000});
    });
//    $.ajax('http://api-maps.yandex.ru/services/constructor/1.0/js/?sid=JTaMlWDTIGTosMZmEsohKC6HxXisyfFK&amp;width=600&amp;height=450;id=contact_map', {dataType: 'script', cache: true})
});