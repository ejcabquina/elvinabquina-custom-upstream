window.addEventListener('load', function () {

	let columnCarousel = document.querySelectorAll('.block-blog-posts');
	columnCarousel.forEach(function (carousel) {

		let id = '5772835235';
		new Glider(carousel, {
			slidesToShow: 1,
			slidesToScroll: 1,
			itemWidth: 260,
			arrows: {
				prev: '.related-posts__glider-prev',
				next: '.related-posts__glider-next'
			},
			dots: '.related-posts__glider-dots',
			responsive: [
				{
					// screens greater than >= 775px
					breakpoint: 768,
					settings: {
						// Set to `auto` and provide item width to adjust to viewport
						slidesToShow: 'auto',
						slidesToScroll: 'auto',
						itemWidth: 260,
						duration: 0.25
					}
				}, {
					// screens greater than >= 1024px
					breakpoint: 1024,
					settings: {
						slidesToShow: 4,
						slidesToScroll: 1,
						itemWidth: 260,
						duration: 0.25
					}
				}
			]
		});


		/**
		 *
		 */
		carousel.addEventListener('glider-loaded', gliderDotUpdate);
		/**
		 * Glider refresh listener
		 */
		carousel.addEventListener('glider-refresh', gliderDotUpdate);

	});

	/**
	 * Glider dot update check
	 * This function checks the status of the dots within the controls container and updates each
	 * with an aria-selected attribute. If a dot has the class 'active' applied to it then aria-selected
	 * will be set to true. This function should be called anytime there is a potential update to the glider
	 * and requires that an event object is passed in. The event object has to originate from a glider event
	 * @param e
	 */
	let gliderDotUpdate = (e) => {
		let dots = e.target.parentElement.querySelectorAll('.glider-dot');
		if(typeof dots !== 'undefined') {
			dots.forEach((elem)=> {
				//Remove tab role
				elem.removeAttribute('role');

				if(elem.classList.contains('active')) {
					elem.setAttribute('aria-selected', 'true');
				} else {
					elem.setAttribute('aria-selected', 'false');
				}
			});
		}

		//Remove tab list role
		let dotsContainer = e.target.parentElement.querySelector('.glider-dots');
		if(typeof dotsContainer !== 'undefined') {
			dotsContainer.removeAttribute('role');
		}
	};

	let dots = document.querySelectorAll('.glider-dot, .glider-dots');
	dots.forEach(function (dot) {
		dot.removeAttribute('role');
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

	let reviewsCarouselPrev = document.querySelectorAll('.related-posts__glider-prev');
	let reviewsCarouselNext = document.querySelectorAll('.related-posts__glider-next');

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

	// When focus is on last visible slide, transition focus to left arrow

	let gliderCarousel = document.querySelector('.glider > .glider-track');
	let visibleSlides = gliderCarousel.querySelectorAll(':scope > .visible');
	let carouselPrev = document.querySelector('.related-posts__glider-prev');
	let carouselNext = document.querySelector('.related-posts__glider-next');

	visibleSlides.forEach((slide, i, array) => {
		let slideUrl = slide.querySelector(':scope > div > a');

		slideUrl.addEventListener('focusout', () => {
			if(i === array.length-1) {
				carouselPrev.focus();
			}
		});
	});

	// Focus on slide that has moved into view after right arrow is selected

	document.querySelector('.glider').addEventListener('glider-slide-visible', function(event) {
		let glider = Glider(this);
		// let focusEl = document.activeElement;

		for( let i = 0; i <= glider.slides.length-1; i++ ) {
			let currentSlide = glider.slides[i];
			let nextSlide = glider.slides[i+1];
			let activeSlide = currentSlide.classList.contains('active');

			if ( currentSlide.classList.contains('visible') && i === visibleSlides.length-1 ) {
				let nextSlideUrl = nextSlide.querySelector('.visible > div > h3 > a');
				nextSlideUrl.focus();
				i++;
			}
		}
	});

});

jQuery( function( $ ) {

	// Accessibility for related posts slider dots
	$( window ).on( 'load', function() {
		$( '.block-carousel__dots.related-posts__glider-dots' ).attr( 'role', 'tablist' );
		$( '.related-posts__glider-dots button.glider-dot' ).attr( 'role', 'tab' );
	});

});
