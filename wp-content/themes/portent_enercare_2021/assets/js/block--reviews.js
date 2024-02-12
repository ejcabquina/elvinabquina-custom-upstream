"use strict";

window.addEventListener('load', function () {
  var blockReviews = document.querySelector('.block-reviews__glider');
  var prevArrow, prevArrowImage, nextArrow, nextArrowImage, dots, imagePath;
  imagePath = '/wp-content/themes/portent_enercare_2021/assets/icons/utility/';

  //prevArrow = document.createElement( 'button' );
  // prevArrowImage = document.createElement( 'img' );
  // prevArrow.classList.add( 'button__prev','block-reviews__prev');
  // prevArrow.setAttribute('aria-label', 'Previous');
  // prevArrowImage.setAttribute('src', `${imagePath}arrow_back.svg`);
  // prevArrowImage.setAttribute( 'alt', 'Previous');
  // prevArrowImage.setAttribute( 'height', '24');
  // prevArrowImage.setAttribute( 'width', '24');
  // prevArrow.appendChild( prevArrowImage );

  // nextArrow = document.createElement( 'button' );
  // nextArrowImage = document.createElement( 'img' );
  // nextArrow.classList.add( 'button__next','block-reviews__next');
  // nextArrow.setAttribute('aria-label', 'Next');
  // nextArrowImage.setAttribute('src', `${imagePath}arrow_forward.svg`);
  // nextArrowImage.setAttribute( 'alt', 'Next');
  // nextArrowImage.setAttribute( 'height', '24');
  // nextArrowImage.setAttribute( 'width', '24');
  // nextArrow.appendChild( nextArrowImage );

  // dots = document.createElement( 'div' );
  // dots.classList.add('dots', 'block-reviews__dots');
  // dots.setAttribute( 'role', 'tablist');

  // blockReviews.parentNode.insertBefore(dots, blockReviews.nextSibling);
  // blockReviews.parentNode.insertBefore(nextArrow, blockReviews.nextSibling);
  // blockReviews.parentNode.insertBefore(prevArrow, blockReviews.nextSibling);
  var reviewsGlider;
  reviewsGlider = new Glider(blockReviews, {
    slidesToShow: 1,
    slidesToScroll: 1,
    draggable: true,
    scrollLock: true,
    duration: 1,
    scrollLockDelay: 500,
    dots: '.block-reviews__dots',
    arrows: {
      prev: '.block-reviews__prev',
      next: '.block-reviews__next'
    },
    responsive: [{
      // screens greater than >= 775px
      breakpoint: 775,
      settings: {
        // Set to `auto` and provide item width to adjust to viewport
        slidesToShow: 2,
        slidesToScroll: 1,
        itemWidth: 150,
        duration: 0.25
      }
    }, {
      // screens greater than >= 1024px
      breakpoint: 1024,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 1,
        itemWidth: 150,
        duration: 0.25
      }
    }]
  });
  var gliderNotificationCenter;
  gliderNotificationCenter = document.querySelector('#gliderNotificationCenter');
  var CurrentDots = document.querySelectorAll('.glider-dot, .glider-dots');
  CurrentDots.forEach(function (dot) {
    dot.removeAttribute('role');
  });

  /**
   * Glider dot update check
   * This function checks the status of the dots within the controls container and updates each
   * with an aria-selected attribute. If a dot has the class 'active' applied to it then aria-selected
   * will be set to true. This function should be called anytime there is a potential update to the glider
   * and requires that an event object is passed in. The event object has to originate from a glider event
   * @param e
   */
  var gliderDotUpdate = function gliderDotUpdate(e) {
    var dots = e.target.parentElement.querySelectorAll('.glider-dot');
    if (typeof dots !== 'undefined') {
      dots.forEach(function (elem) {
        //Remove tab role
        elem.removeAttribute('role');
        if (elem.classList.contains('active')) {
          elem.setAttribute('aria-current', 'true');
        } else {
          elem.setAttribute('aria-current', 'false');
        }
      });
    }

    //Remove tab list role
    var dotsContainer = e.target.parentElement.querySelector('.glider-dots');
    if (typeof dotsContainer !== 'undefined') {
      dotsContainer.removeAttribute('role');
    }
  };

  /**
   *
   */
  blockReviews.addEventListener('glider-loaded', gliderDotUpdate);
  /**
   * Glider refresh listener
   */
  blockReviews.addEventListener('glider-refresh', gliderDotUpdate);

  /**
   * Glider Change Listener
   */
  blockReviews.addEventListener('glider-slide-visible', function (event) {
    gliderNotificationCenter.textContent = "Review ".concat(event.detail.slide + 1, " is now visible");
    //Call dot update just in case
    gliderDotUpdate(event);
  });

  /**
   * Setup Next/Previous Status Reporters
   */
  var reviewsCarouselPrev = document.querySelector('.block-reviews__prev');
  var reviewsCarouselNext = document.querySelector('.block-reviews__next');
  var reviewCarouselAction = function reviewCarouselAction(event, direction) {
    gliderNotificationCenter.textContent = '';
    var closestSlide = event.target.closest('.glider-slide.visible');
    console.log(closestSlide);
    if (!event.target.classList.contains('disabled') && window.outerWidth >= 1024) {
      gliderNotificationCenter.textContent = "Carousel of reviews moved to ".concat(direction, " slide of 4 reviews.");
    } else if (!event.target.classList.contains('disabled') && window.outerWidth < 775) {
      gliderNotificationCenter.textContent = "Carousel of reviews moved to ".concat(direction, " review.");
    }
  };
  if (typeof reviewsCarouselPrev !== 'undefined') {
    reviewsCarouselNext.addEventListener('click', function (event) {
      reviewCarouselAction(event, 'Next');
    });
  }
  if (typeof reviewsCarouselPrev !== 'undefined') {
    reviewsCarouselPrev.addEventListener('click', function (event) {
      reviewCarouselAction(event, 'Previous');
    });
  }
});