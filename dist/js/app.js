//    new WOW().init(); 
document.addEventListener('DOMContentLoaded', () => {
    
    /* input mask
    ====================================*/
    $('.js-mask').inputmask("+7 (999) 999-99-99");
    
    $('.js-minus').click(function () {
        let $input = $('#output');
        let count = parseInt($input.val()) - 1;
        count = count < 1 ? 1 : count;
        $input.val(count);
        $input.change();
        return false;
    });
    $('.js-plus').click(function () {
        let $input = $('#output');
        $input.val(parseInt($input.val()) + 1);
        $input.change();
        return false;
    });
    
    /* to top
    ====================================*/
    $(window).scroll(function() {

        if($(this).scrollTop() >= 600) {
            $('#to-top').fadeIn();
        }
        else {
            $('#to-top').fadeOut();
        }
    });

    $('#to-top').click(function() {
        $('body,html').animate({scrollTop:0},800);
    })
    
  
    var $range = $(".js-range-slider"),
        $from = $(".js-from"),
        $to = $(".js-to"),
        range,
        min = 0,
        max = 20000,
        from,
        to;

    var updateValues = function () {
        $from.prop("value", from);
        $to.prop("value", to);
    };

    $range.ionRangeSlider({
        type: "double",
        min: min,
        max: max,
        hide_min_max:true,
        prettify_enabled: false,
        grid: false,
        grid_num: 10,
        onChange: function (data) {
            from = data.from;
            to = data.to;

            updateValues();
        }
    });

    range = $range.data("ionRangeSlider");

    var updateRange = function () {
        range.update({
            from: from,
            to: to
        });
    };

    $from.on("change", function () {
        from = +$(this).prop("value");
        if (from < min) {
            from = min;
        }
        if (from > to) {
            from = to;
        }

        updateValues();
        updateRange();
    });

    $to.on("change", function () {
        to = +$(this).prop("value");
        if (to > max) {
            to = max;
        }
        if (to < from) {
            to = from;
        }

        updateValues();
        updateRange();
    });
    
    /* chartjs
    ====================================*/
    const chart1 = document.getElementById('myChart');
    /*
    new Chart(chart1, {
        type: 'line',
        data: {
            labels: ['Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь'],
            datasets: [{
                label: '# of Votes',
                data: [12, 19, 9, 13, 8, 16],
                borderWidth: 1
            }]
        },
        
        options: {
            plugins: {
                legend: {
                    display: false,
                    labels: {
                        boxWidth: 0,
                    }
                },
            }
        }
    });*/
  
    
     
    /* slick
    ====================================*/
    
    $('.js-cert-slider').slick({
        arrows: true,
        //        autoplay: true,
        autoplaySpeed: 3000,
        dots: false,
        infinite: true,
        //        fade: true,
        slidesToShow: 5,
        slidesToScroll: 1,
        speed: 2000,
        prevArrow: "<button type='button' class='slick-prev'><svg><use xlink:href='#arrow-prev'></button>",
        nextArrow: "<button type='button' class='slick-next'><svg><use xlink:href='#arrow-next'></button>",
        responsive: [
            {
                breakpoint: 1300,
                settings: {
                    slidesToShow: 4,
                    slidesToScroll: 4,
                }
            },
            {
                breakpoint: 750,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3,
                }
            },
            {
                breakpoint: 550,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2,
                }
            }
        ]
    });
    
    $('.js-selection-slider').slick({
        arrows: true,
        //        autoplay: true,
        autoplaySpeed: 3000,
        dots: false,
        infinite: true,
        //        fade: true,
        slidesToShow: 5,
        slidesToScroll: 1,
        speed: 2000,
        prevArrow: "<button type='button' class='slick-prev'><svg><use xlink:href='#arrow-prev'></button>",
        nextArrow: "<button type='button' class='slick-next'><svg><use xlink:href='#arrow-next'></button>",
        responsive: [
            {
                breakpoint: 1300,
                settings: {
                    slidesToShow: 4,
                    slidesToScroll: 4,
                }
            },
            {
                breakpoint: 750,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3,
                }
            },
            {
                breakpoint: 550,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2,
                }
            }
        ]
    });
    
    $('.js-clients-slider').slick({
        arrows: true,
        //        autoplay: true,
        autoplaySpeed: 3000,
        dots: false,
        infinite: true,
        //        fade: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        speed: 2000,
        prevArrow: "<button type='button' class='slick-prev'><svg><use xlink:href='#arrow-prev'></button>",
        nextArrow: "<button type='button' class='slick-next'><svg><use xlink:href='#arrow-next'></button>",
    });
    
    $('.js-card-slider-big').slick({
        arrows: false,
//        autoplay: true,
        autoplaySpeed: 3000,
        dots: false,
        infinite: true,
//        fade: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        speed: 2000,
        asNavFor: '.js-card-slider-thumb'
        
    });
    
    $('.js-card-slider-thumb').slick({
        arrows: true,
        //        autoplay: true,
        autoplaySpeed: 3000,
        dots: false,
        //        fade: true,
        slidesToShow: 4,
        slidesToScroll: 1,
        speed: 2000,
        infinite: true,
        asNavFor: '.js-card-slider-big',
        focusOnSelect: true,
        prevArrow: $('#js-card-slider-prev'),
        nextArrow: $('#js-card-slider-next'),
        responsive: [
            {
                breakpoint: 1800,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1,
                }
            }
        ]

    });
   
    
    $('.js-slider-top').slick({
        arrows: true,
        //        autoplay: true,
        autoplaySpeed: 3000,
        dots: false,
        infinite: true,
        //        fade: true,
        slidesToShow: 4,
        slidesToScroll: 1,
        speed: 2000,
        prevArrow: "<button type='button' class='slick-prev'><svg><use xlink:href='#arrow-prev'></button>",
        nextArrow: "<button type='button' class='slick-next'><svg><use xlink:href='#arrow-next'></button>",
        responsive: [
            {
                breakpoint: 1320,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3,
                }
            },
            {
                breakpoint: 680,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2,
                }
            }
        ]
    });
    
    /* accordeon
    ====================================*/
    $('.accordeon__answear').hide();

    $('.accordeon__item:first-child .accordeon__answear').show();
    $('.accordeon__item:first-child .accordeon__icon').addClass('accordeon__icon--active');
    
    
    $('.js-accordeon').on('click', function() {
        $(this).find('.accordeon__icon').toggleClass('is-active');
        $(this).next('.accordeon__answear').slideToggle();
    });
    
    
    /* popup accordeon
    ====================================*/
    $('.popup__accordeon-answear').hide();

    $('.popup__accordeon-item:first-child .popup__accordeon-answear').show();
    $('.popup__accordeon-item:first-child .popup__accordeon-icon').addClass('is-active');


    $('.js-popup-accordeon').on('click', function() {
        $(this).find('.popup__accordeon-icon').toggleClass('is-active');
        
        $(this).next('.popup__accordeon-answear').slideToggle();
    });
    
    /* filters
    ====================================*/
    $('.js-title').on('click', function(e) {
        e.preventDefault();
        
        $(this).find('.filters__title-icon').toggleClass('is-active');
        
        $(this).next('.filters__content').slideToggle();
    });
    
    /* receipts
    ====================================*/
    $('.js-receipts-btn').click(function(e) {
        e.preventDefault();
        
        if( $(this).parent('.receipts').is('.receipts--active')) {
            $(this).children('span').text('Показать еще');
            $('.receipts').removeClass('receipts--active');
        } else {
            $(this).children('span').text('Скрыть');
            $('.receipts').addClass('receipts--active');
        }
    });

    $('.js-list-param').click(function(e) {
        e.preventDefault();

        if( $(this).prev('.list-param').is('.is-active')) {
            $(this).removeClass('is-active');
            $(this).prev('.list-param').removeClass('is-active');
        } else {
            $(this).addClass('is-active');
            $(this).prev('.list-param').addClass('is-active');
        }
    });
    
    
    
    /* menu
    ====================================*/
    
    let pull         = $('#pull'),
        menu         = $('.js-menu');

    $(pull).on('click', function() {
        $(this).toggleClass('on');
        //$('.header').toggleClass('header--active');
        menu.slideToggle();
        return false;
    });
    
    $(window).resize(function(){
        if(menu.is(':hidden')) {
            menu.removeAttr('style');
        }
    });
    
 

    /* табы
    ====================================*/
    
    $('.js-tabs li:first-child  a').addClass('is-active');
    let tab_elem =  $('.js_tabContent > div');
    tab_elem.css('display', 'none');
    tab_elem.first().show('fast');
    $('.js-tabs a').on('click', function(event) {
        event.preventDefault();
        $('.js-tabs a').removeClass('is-active');
        $(this).addClass('is-active');
        var tab_id = $(this).attr('href');
        tab_elem.hide('500');
        $(tab_id).show('500');
    });
    
    
    $('.js-tabs').each(function() {
        const $tabs = $(this);
        const $tabLinks = $tabs.find('a');
        const $tabContents = $tabs.next('.js_tabContent').find('> div');

        $tabLinks.eq(0).addClass('is-active');
        $tabContents.hide().eq(0).show('fast');

        $tabLinks.on('click', function(event) {
            event.preventDefault();

            const $clickedTab = $(this);
            const tab_id = $clickedTab.attr('href');

            $tabLinks.removeClass('is-active');
            $clickedTab.addClass('is-active');

            $tabContents.hide();
            $(tab_id).show('fast');
        });
    });

    
    /* popup
    ====================================*/
    
    $('.js-btn').on('click', function(e){
        e.preventDefault();
        var id = $(this).attr('data-link');
        $('#' + id).fadeIn(500);
        //        $('#' + id).fadeIn(500);
        $('body').addClass('hidden-scroll');
        $('.js-overlay').fadeIn(500);
        return false;

    });

    $(".js-overlay").on("click", function() {
        $('body').removeClass('hidden-scroll');
        $(".js-overlay").fadeOut(500);
        $('.js-popup').fadeOut(500);
    });

    $('.js-close').on('click', function() {
        $('body').removeClass('hidden-scroll');
        $(".js-overlay").fadeOut();
        $('.js-popup').fadeOut(500);
    });
    
    /* tippy
    ====================================*/
    tippy('#tippy-1', {
        content: '<strong>Bolded content</strong>',
        allowHTML: true,
    });
    
    
    /* fixed header
    ====================================*/
    $(window).on('scroll', function(){
        let sc = $(this).scrollTop();
        let $fixedElement = $('#for-fixed');
        if ($('#for-fixed').length && $fixedElement.length) {
            if (sc >= $('#for-fixed').offset().top) {
                $fixedElement.addClass('is-fixed');
            } else {
                $fixedElement.removeClass('is-fixed');
            }
        }

    });

});



