
(function ($, Drupal) {
    'use strict';

    /*Drupal.behaviors.header = {
      attach: function (context, settings) {

        var stickyOffset = $('.region-header-bottom').offset().top + 50 ;

        $(window).scroll(function(){
          var sticky = $('.region-header-bottom'),
              scroll = $(window).scrollTop();

          if (scroll >= stickyOffset) {
            sticky.addClass('fixed');
          }
          else
          {
            sticky.removeClass('fixed');
          }
        });
      }
    };*/


    Drupal.behaviors.menu = {
        attach: function (context, settings) {

            if ($(window).width() > 991) {


                $("li.menu-item--expanded").hover(
                    function() {
                        $('> ul.menu', this).stop( true, true ).fadeIn("fast");
                        $(this).toggleClass('open');
                        $('b', this).toggleClass("caret caret-up");
                    },
                    function() {
                        $('> ul.menu', this).stop( true, true ).fadeOut("fast");
                        $(this).toggleClass('open');
                        $('b', this).toggleClass("caret caret-up");
                    });
            }

            $('.navbar-toggle', context).click(function(){
                $('.navbar-toggle').toggleClass('navbar-on');
                $('.navigation').animate({width: 'toggle'});
            });
            $('.menu-accordion-icon', context).click(function(){
                $(this).next('ul.menu').slideToggle();

            });

            $('.menu-accordion-icon', context).click(function(){
                $(this).toggleClass('accordion-open');

            });
        }
    };






    /*Drupal.behaviors.mobileMenu = {
      attach: function (context, settings) {

        $('.navbar-toggle', context).click(function(){
          $('.navbar-toggle').toggleClass('navbar-on');
          $('.menu--main-menu').animate({width: 'toggle'});
        });
      }
    };

    Drupal.behaviors.menuAccordion = {
      attach: function (context, settings) {
        $('.menu-item--expanded', context).click(function(){
          $('.menu-item--expanded > .menu').slideToggle();
        });
      }
    };
  */

})(jQuery, Drupal);
