import 'jquery/dist/jquery.js';
import 'owl.carousel/dist/assets/owl.carousel.css';
import 'owl.carousel/dist/owl.carousel';
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap/dist/js/bootstrap';

<<<<<<< Updated upstream

$(document).ready(function(){
    $('.owl-carousel').owlCarousel({
        loop:true,
        margin:10,
        nav:true,
        responsive:{
            0:{
                items:1
            },
            600:{
                items:3
            },
            1000:{
                items:5
            }
        }
    })

});











=======
$(document).ready(function() {
    $('.owl-carousel').owlCarousel({
        loop:true,
        margin:0,
        items:1,
        nav:false,
        dots:true,
        autoplay:false,
        autoplayTimeout:5000,
    });

    function stickyHeader() {
        if ($(this).scrollTop() > 50) {

            $('.site-header').addClass('sticky-header');

        } else {

            $('.site-header').removeClass('sticky-header');

        }

    }

    function windowScroll() {

        $(window).on('scroll', function () {
            stickyHeader();
        });
    }

    windowScroll();
    stickyHeader();

    $(".site-header").on("click", "a", function (event) {

        var $href = $(this).attr('href').replace('/','');

        var $anchor = $($href).offset();

        if (typeof $anchor !== 'undefined') {

            $('html, body').animate({

                scrollTop: $anchor.top

            }, 1000);

            return false;



        }

    });



    $('.main-navigation a').on('click', function (e) {

        $(this).closest('.main-navigation').removeClass('toggled');

        $(this).closest('.main-navigation').find('.hamburger').removeClass('hamburger-open');

        $(this).parent().addClass('active-menu-item');

        $(this).parent().siblings().removeClass('active-menu-item');

    });



    //hamburger

    $("button.hamburger").on("click", function () {

        $(this).toggleClass("hamburger-open");

        // $(this).next("ul").toggleClass("hidden");

    });

});
>>>>>>> Stashed changes
