"use strict";

window.addEventListener('load', function () {
  var carouselWrapper = document.querySelectorAll('.block-carousel__wrapper');
  carouselWrapper.forEach(function (carousel) {
    //let carousel = document.querySelector('.block-carousel__wrapper');
    var $slidesToShow = carousel.getAttribute("data-num-slides");
    if ($slidesToShow === "") $slidesToShow = 1;
    var $slidesToScroll = carousel.getAttribute("data-num-advance");
    if ($slidesToScroll === "") $slidesToScroll = 1;
    var $breakpoints = carousel.getAttribute("data-breakpoints");
    var $responsive_obj = null;
    if ($breakpoints !== "") {
      $responsive_obj = JSON.parse(decodeURIComponent($breakpoints));
    }
    var $id = carousel.getAttribute("data-id");

    //console.log("slidesToShow", $slidesToShow);
    //console.log("slidesToScroll", $slidesToScroll);
    //console.log("responsive_obj", $responsive_obj);

    var carouselGlider = new Glider(carousel.querySelector('.block-carousel'), {
      slidesToShow: $slidesToShow,
      slidesToScroll: $slidesToScroll,
      arrows: {
        prev: '.glider-prev-' + $id,
        next: '.glider-next-' + $id
      },
      dots: '.glider-dots-' + $id,
      responsive: $responsive_obj
    });
    carouselGlider.addEventListener('glider-loaded', function (e) {
      console.log(e);
    });
  });

  /**
   * Setup Next/Previous Status Reporters
   */
  var gliderNotificationCenter;
  gliderNotificationCenter = document.querySelector('#gliderNotificationCenter');
  var reviewCarouselAction = function reviewCarouselAction(event, direction) {
    gliderNotificationCenter.textContent = '';
    if (!event.target.classList.contains('disabled') && window.outerWidth >= 1024) {
      gliderNotificationCenter.textContent = "Carousel moved to ".concat(direction, " group of 4 items.");
    } else if (!event.target.classList.contains('disabled') && window.outerWidth < 775) {
      gliderNotificationCenter.textContent = "Carousel moved to ".concat(direction, " item.");
    }
  };
  var reviewsCarouselPrev = document.querySelectorAll('.block-reviews__prev');
  var reviewsCarouselNext = document.querySelectorAll('.block-reviews__next');
  if (typeof reviewsCarouselPrev !== 'undefined') {
    reviewsCarouselPrev.forEach(function (button) {
      button.addEventListener('click', function (event) {
        reviewCarouselAction(event, 'Previous');
      });
    });
  }
  if (typeof reviewsCarouselPrev !== 'undefined') {
    reviewsCarouselNext.forEach(function (button) {
      button.addEventListener('click', function (event) {
        reviewCarouselAction(event, 'Next');
      });
    });
  }

  /**
   * Setup Aria Selectr true triggers for dots
   */
});