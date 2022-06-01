!(function($) {
    "use strict";

    var scrolltoOffset = $('#header').outerHeight() - 17;
    $(document).on('click', '.scrollto', function(e) {
        if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
            var target = $(this.hash);
            if (target.length) {
                e.preventDefault();
                var scrollto = target.offset().top - scrolltoOffset;
                if ($(this).attr("href") == '#header') {
                    scrollto = 0;
                }
                $('html, body').animate({
                    scrollTop: scrollto
                }, 1500, 'easeInOutExpo');

                return false;
            }
        }
    });

    $(document).ready(function() {
        if (window.location.hash) {
            var initial_nav = window.location.hash;
            if ($(initial_nav).length) {
                var scrollto = $(initial_nav).offset().top - scrolltoOffset;
                $('html, body').animate({
                    scrollTop: scrollto
                }, 1500, 'easeInOutExpo');
            }
        }
    });

    if ($('#header').find('.nav-menu').length) {
        var $mobile_nav = $('.nav-menu').clone().prop({
            class: 'mobile-nav-menu'
        });
        $('body .mobile-nav .mobile-nav-content').append($mobile_nav);
        $(document).on('click', '[data-toggle="mobile-nav"]', function(e) {
            if ($('.mobile-nav').hasClass('active')) {
                $('body').removeClass('mobile-nav-active');
                $('.mobile-nav').removeClass('active');
            } else {
                $('body').addClass('mobile-nav-active');
                $('.mobile-nav').addClass('active');
            }
        });
    }

    $(window).scroll(function() {
        if ($(this).scrollTop() > 100) {
            $('#header').addClass('header-scrolled');
        } else {
            $('#header').removeClass('header-scrolled');
        }
    });

    if ($(window).scrollTop() > 100) {
        $('#header').addClass('header-scrolled');
    }

    var heroCarousel = $("#heroCarousel");
    var heroCarouselIndicators = $("#hero-carousel-indicators");
    heroCarousel.find(".carousel-inner").children(".carousel-item").each(function(index) {
        (index === 0) ?
            heroCarouselIndicators.append("<li data-target='#heroCarousel' data-slide-to='" + index + "' class='active'></li>"):
            heroCarouselIndicators.append("<li data-target='#heroCarousel' data-slide-to='" + index + "'></li>");
    });

    heroCarousel.on('slid.bs.carousel', function(e) {
        $(this).find('h2').addClass('animate__animated animate__fadeInDown');
        $(this).find('p, .btn-get-started').addClass('animate__animated animate__fadeInUp');
    });

    $(window).scroll(function() {
        if ($(this).scrollTop() > 100) {
            $('.back-to-top').fadeIn('slow');
        } else {
            $('.back-to-top').fadeOut('slow');
        }
    });

    $('.back-to-top').click(function() {
        $('html, body').animate({
            scrollTop: 0
        }, 1500, 'easeInOutExpo');
        return false;
    });

    $(window).on('load', function() {
        var portfolioIsotope = $('.portfolio-container').isotope({
            itemSelector: '.portfolio-item'
        });

        $('#portfolio-flters li').on('click', function() {
            $("#portfolio-flters li").removeClass('filter-active');
            $(this).addClass('filter-active');

            portfolioIsotope.isotope({
                filter: $(this).data('filter')
            });
        });

        $(document).ready(function() {
            $('.venobox').venobox({
                share: ['facebook', 'twitter', 'linkedin', 'pinterest'],
                infinigall: true,
                spinner: 'three-bounce'
            });
        });
    });

    $('.skills-content').waypoint(function() {
        $('.progress .progress-bar').each(function() {
            $(this).css("width", $(this).attr("aria-valuenow") + '%');
        });
    }, {
        offset: '80%'
    });

    $(".portfolio-details-carousel").owlCarousel({
        autoplay: true,
        dots: true,
        loop: true,
        items: 1
    });

    if ($('.select-district').length) {
        $('.select-district').select2({
            ajax: {
                url: '/data/get-select-district',
                dataType: 'json'
            },
            theme: 'bootstrap4'
        });
    }
})(jQuery);
