window.addEventListener('load', function(){

  let carouselWrapper = document.querySelectorAll('.block-carousel__wrapper');
  carouselWrapper.forEach(function(carousel) {

    //let carousel = document.querySelector('.block-carousel__wrapper');
    let $slidesToShow = carousel.getAttribute("data-num-slides");
    if ($slidesToShow === "")
      $slidesToShow = 1;

    let $slidesToScroll = carousel.getAttribute("data-num-advance");
    if ($slidesToScroll === "")
      $slidesToScroll = 1;

    let $breakpoints = carousel.getAttribute("data-breakpoints");
    let $responsive_obj = null;
    if ($breakpoints !== "") {
      $responsive_obj = JSON.parse(decodeURIComponent($breakpoints));
    }

    let $id = carousel.getAttribute("data-id");

    //console.log("slidesToShow", $slidesToShow);
    //console.log("slidesToScroll", $slidesToScroll);
    //console.log("responsive_obj", $responsive_obj);

    let carouselGlider = new Glider(carousel.querySelector('.block-carousel'), {
      slidesToShow: $slidesToShow,
      slidesToScroll: $slidesToScroll,
      arrows: {
        prev: '.glider-prev-' + $id,
        next: '.glider-next-' + $id
      },
      dots: '.glider-dots-' + $id,
      responsive: $responsive_obj
    });

    carouselGlider.addEventListener('glider-loaded', (e) => {
    	console.log(e);
	});
  });

	/**
	 * Setup Next/Previous Status Reporters
	 */
	let gliderNotificationCenter;
	gliderNotificationCenter = document.querySelector('#gliderNotificationCenter');

	let reviewCarouselAction = function(event, direction) {
		gliderNotificationCenter.textContent = '';
		if(!event.target.classList.contains('disabled') && window.outerWidth >= 1024) {
			gliderNotificationCenter.textContent = `Carousel moved to ${direction} group of 4 items.`;
		} else if(!event.target.classList.contains('disabled') && window.outerWidth < 775) {
			gliderNotificationCenter.textContent = `Carousel moved to ${direction} item.`;
		}
	};

	let reviewsCarouselPrev = document.querySelectorAll('.block-reviews__prev');
	let reviewsCarouselNext = document.querySelectorAll('.block-reviews__next');

	if(typeof reviewsCarouselPrev !== 'undefined'){
		reviewsCarouselPrev.forEach(function(button){
			button.addEventListener('click', function(event){
				reviewCarouselAction(event, 'Previous');
			});
		});
	}

	if(typeof reviewsCarouselPrev !== 'undefined'){
		reviewsCarouselNext.forEach(function(button){
			button.addEventListener('click', function(event){
				reviewCarouselAction(event, 'Next');
			});
		});
	}

	/**
	 * Setup Aria Selectr true triggers for dots
	 */

});
