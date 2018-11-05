// /*
//  * Welcome to your app's main JavaScript file!
//  *
//  * We recommend including the built version of this JavaScript file
//  * (and its CSS file) in your base layout (base.html.twig).
//  */
//
// // any CSS you require will output into a single css file (app.css in this case)
// require('../css/app.css');
//
// // Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
// // var $ = require('jquery');
//
// console.log('Hello Webpack Encore! Edit me in assets/js/app.js');
//
//
// import 'owl.carousel/dist/assets/owl.carousel.css';
// import 'owl.carousel';
//
//
// $(document).ready(function(){
//
//     function stickyHeader() {
//         if ($(this).scrollTop() > 50) {
//             $('.site-header').addClass('sticky-header');
//         } else {
//             $('.site-header').removeClass('sticky-header');
//         }
//     }
//     function windowScroll() {
//         $(window).on('scroll', function () {
//             stickyHeader();
//         });
//     }
//     windowScroll();
//     stickyHeader();
//
//     $('.main-navigation a').on('click', function (e) {
//         $(this).closest('.main-navigation').removeClass('toggled');
//         $(this).closest('.main-navigation').find('.hamburger').removeClass('hamburger-open');
//         $(this).parent().addClass('active-menu-item');
//         $(this).parent().siblings().removeClass('active-menu-item');
//     });
//     //hamburger
//     $("button.hamburger").on("click", function () {
//         $(this).toggleClass("hamburger-open");
//     });
//     $('#masthead').autoHidingNavbar();
//     $('.slideJS').on('inview', function (event, isInView) {
//
//         // if (isInView) {
//         //     function timeoutFunction() {
//         //         setTimeout(function () {
//         //             $(event.currentTarget).css('visibility', 'visible').addClass('animated fadeInUp');
//         //         }, 100);
//         //     }
//         //     timeoutFunction();
//         // } else {
//         // }
//     });
//
//     $('.home_slider_section .owl-carousel').owlCarousel({
//         loop:true,
//         margin:0,
//         items:1,
//         nav:false,
//         dots:true,
//         autoplay:false,
//         autoplayTimeout:5000,
//         // autoplayHoverPause:true,
//         // animateOut:'fadeOut',
//     });
//
//     /*oblicznie ile przcuje w industi*/
//     let date1 = new Date("7/3/2017"); // ustawienie daty za pomocą tekstu (miesiąc/dzień/rok)
//     let date2 = new Date();
//     let diff = new Date(Math.abs(date2.getTime() - date1.getTime()));
//
//     function lataFunction() {
//         if((diff.getFullYear() - 1970)===1){
//             return 'rok';
//         }
//         else if((diff.getFullYear() - 1970)>=2 && (diff.getFullYear() - 1970)<5){
//             return 'lata';
//         }
//         else if((diff.getFullYear() - 1970)>=5){
//             return 'lat';
//         }
//     }
//     function miesiecyFunction() {
//         if(diff.getMonth()===1){
//             return 'miesiąc';
//         }
//         else if((diff.getMonth())>=2 && (diff.getMonth())<5){
//             return 'miesiące';
//         }
//         else if(diff.getMonth()>=5){
//             return 'miesięcy';
//         }
//     }
//     function dniFunction() {
//         if(diff.getMonth()===1){
//             return 'dzień';
//         }
//
//         else if(diff.getMonth()>=2){
//             return 'dni';
//         }
//     }
//
//
//     let lata = lataFunction();
//     let miesiace = miesiecyFunction();
//     let dni = dniFunction();
//     let text = (diff.getFullYear() - 1970) + lata + diff.getMonth() + miesiace + diff.getDate()
//         + dni;
//     $('#start_work').html(text);
// });
//
//
//
//
//
//
//
//
//
//
//
