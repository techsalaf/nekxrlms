(function ($) {
  "use strict";

  // ==========================================
  //      Start Document Ready function
  // ==========================================
  $(document).ready(function () {

  // ========================== Header Hide Scroll Bar Js Start =====================
    $('.navbar-toggler.header-button').on('click', function() {
      $('body').toggleClass('scroll-hidden')
    }); 
    $('.body-overlay').on('click', function() {
      $('body').removeClass('scroll-hidden')
    }); 
  // ========================== Header Hide Scroll Bar Js End =====================
  
  // ============================ToolTip Js Start=====================
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))  
  // ============================ToolTip Js End========================
    
  // ================== Sidebar Menu Js Start ===============
    // Sidebar Dropdown Menu Start
    $(".has-dropdown > a").click(function() {
      $(".sidebar-submenu").slideUp(200);
      if (
        $(this)
          .parent()
          .hasClass("active")
      ) {
        $(".has-dropdown").removeClass("active");
        $(this)
          .parent()
          .removeClass("active");
      } else {
        $(".has-dropdown").removeClass("active");
        $(this)
          .next(".sidebar-submenu")
          .slideDown(200);
        $(this)
          .parent()
          .addClass("active");
      }
    });
    // Sidebar Dropdown Menu End

  // Sidebar Icon & Overlay js 
    $(".dashboard-body__bar-icon").on("click", function() {
      $(".sidebar-menu").addClass('show-sidebar'); 
      $(".sidebar-overlay").addClass('show'); 
      $('body').addClass('scroll-hidden'); 
    });
    $(".sidebar-menu__close, .sidebar-overlay").on("click", function() {
      $(".sidebar-menu").removeClass('show-sidebar'); 
      $(".sidebar-overlay").removeClass('show'); 
      $('body').removeClass('scroll-hidden'); 
    });
    // Sidebar Icon & Overlay js 
  // ===================== Sidebar Menu Js End =================

  });
  // ==========================================
  //      End Document Ready function
  // ==========================================

  // ========================= Preloader Js Start =====================
    $(window).on("load", function(){
      $('.preloader').fadeOut(); 
    })
    // ========================= Preloader Js End=====================

    // ========================= Header Sticky Js Start ==============
    $(window).on('scroll', function() {
      if ($(window).scrollTop() >= 300) {
        $('.header').addClass('fixed-header');
      }
      else {
          $('.header').removeClass('fixed-header');
      }
    }); 
    // ========================= Header Sticky Js End===================
    
    //============================ Scroll To Top Icon Js Start =========
    var btn = $('.scroll-top');

    $(window).scroll(function() {
      if ($(window).scrollTop() > 300) {
        btn.addClass('show');
      } else {
        btn.removeClass('show');
      }
    });

    btn.on('click', function(e) {
      e.preventDefault();
      $('html, body').animate({scrollTop:0}, '300');
    });
//========================= Scroll To Top Icon Js End ======================

})(jQuery);
