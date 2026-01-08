(function ($) {
  "use strict";
  
  // ==========================================
  //      Start Document Ready function
  // ==========================================
  $(document).ready(function () {

    // ============== Header Hide Click On Body Js Start ========
    $('.navbar-toggler').on('click', function () {
      $('.body-overlay').toggleClass('show-overlay')
    });

    $('.body-overlay').on('click', function () {
      $('.navbar-toggler').trigger('click')
      $(this).removeClass('show-overlay');
    });
    // =============== Header Hide Click On Body Js End =========
    
    // ========================== Small Device Header Menu On Click Dropdown menu collapse Stop Js Start =====================
    $('.dropdown-item').on('click', function() {
      $(this).closest('.dropdown-menu').addClass('d-block')
    }); 
  // ========================== Small Device Header Menu On Click Dropdown menu collapse Stop Js End =====================
    
    
    // =============== Course Details Content Collapse JS Start =========
    $('.course-wywl-collapse').on('click', function () {
      if($('.course-wywl-content').hasClass('show')) {
        
        $('.course-wywl-content').removeClass('show');
        $(this).find('.text').text('show more');
        $(this).find('.icon').html('<i class="fas fa-chevron-down"></i>');

      } else {

        $('.course-wywl-content').addClass('show');
        $(this).find('.text').text('show less');
        $(this).find('.icon').html('<i class="fas fa-chevron-up"></i>');

      }

    });
    // =============== Course Details Content Collapse JS End  ==========

    // ================ Password Show Hide Js Start ==========
    $(".toggle-password").on('click', function () {
      $(this).toggleClass("fa-eye");
      var input = $(this).parent().find('input');
      if (input.attr("type") == "password") {
        input.attr("type", "text");
      } else {
        input.attr("type", "password");
      }
    });
    // =============== Password Show Hide Js End ============

    // =============== Live Course Lightbox Js Star ===============
    $('.live-course-info-lightbox').magnificPopup({
      type: 'iframe',
    });
    // =============== Live Course Lightbox Js End ===============
    
    // =============== Course Cupon Form Js Start ===============
    $('.course-promo-apply-btn').on('click', function () {
      $('.course-cupon-form').toggleClass('d-none');
    });

    $('.course-cupon-form-close').on('click', function () {
      $(this).parents('.course-cupon-form').toggleClass('d-none');
    })
    // =============== Course Cupon Form Js End ===============
  });
  // ==========================================
  //      End Document Ready function
  // ==========================================

  // ========================= Preloader Js Start =====================
  $(window).on("load", function () {
    $('.preloader').fadeOut();
  })
  // ========================= Preloader Js End=====================

  // ========================= Header Sticky Js Start ==============
  $(window).on('scroll', function () {
    if ($(window).scrollTop() >= 300) {
      $('.header').addClass('fixed');
    }
    else {
      $('.header').removeClass('fixed');
    }
  });
  // ========================= Header Sticky Js End===================

  //============================ Scroll To Top Icon Js Start =========
  var btn = $('.scroll-top');

  $(window).scroll(function () {
    if ($(window).scrollTop() > 300) {
      btn.addClass('show');
    } else {
      btn.removeClass('show');
    }
  });

  btn.on('click', function (e) {
    e.preventDefault();
    $('html, body').animate({ scrollTop: 0 }, '300');
  });
  //========================= Scroll To Top Icon Js End ======================

})(jQuery);
