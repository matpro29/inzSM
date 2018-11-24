import 'owl.carousel';
import 'owl.carousel/dist/assets/owl.carousel.min.css';

$(document).ready(function(){
    $('.owl-carousel').owlCarousel({
        loop:true,
        margin:0,
        items:1,
        nav:false,
        dots:true,
        autoplay:false,
        autoplayTimeout:5000,
        // autoplayHoverPause:true,
        // animateOut:'fadeOut',
    });
});