(function ($) {
    "use strict";

    // jQuery for page scrolling feature - requires jQuery Easing plugin
    $('a.page-scroll').on('click', function (event) {
        var $anchor = $(this);

        $('html, body')
            .stop()
            .animate(
                {scrollTop: ($($anchor.attr('href')).offset().top - 54)},
                1250,
                'easeInOutExpo'
            );

        event.preventDefault();
    });

    // Highlight the top nav as scrolling occurs
    $('body').scrollspy({
        target: '#mainNav',
        offset: 54
    });

    // Closes the Responsive Menu on Menu Item Click
    $('.navbar-collapse>ul>li>a').on('click', function () {
        $('.navbar-collapse').collapse('hide');
    });

    // jQuery to collapse the navbar on scroll
    $(window).on('scroll', function () {
        var nav = $('#mainNav');

        if (nav.hasClass('navbar-shrink-always')) {
            return;
        }

        if (nav.offset().top > 100) {
            nav.addClass('navbar-shrink');
        } else {
            nav.removeClass('navbar-shrink');
        }
    });

    $(document).ready(function () {
        if ($('#mainNav').offset().top > 100) {
            var top = document.documentElement.scrollTop || document.body.scrollTop;
            document.documentElement.scrollTop = document.body.scrollTop = top - 54;
        }
    });

})(jQuery);
