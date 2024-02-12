"use strict";

window.addEventListener('load', function () {
  var blockCarousels = [];
  ssm.addState({
    id: 'block--blog-posts-mobile',
    query: '(max-width: 767px)',
    onEnter: function onEnter() {
      console.log('enter mobile');
      var columnCarousel = document.querySelectorAll('.block-blog-posts.is_mobile_carousel');
      columnCarousel.forEach(function (carousel) {
        var newGlider = new Glider(carousel, {
          slidesToShow: 1,
          slidesToScroll: 1,
          scrollLock: true,
          itemWidth: 260,
          arrows: {
            prev: '.block-blog-posts__glider-prev',
            next: '.block-blog-posts__glider-next'
          },
          dots: '.block-blog-posts__glider-dots'
        });
        newGlider.scrollItem(1, false);
        blockCarousels.push(newGlider);
      });
      console.log(blockCarousels);
    },
    onLeave: function onLeave() {
      blockCarousels.forEach(function (carousel) {
        carousel.destroy();
      });
      blockCarousels = [];
    }
  });
});