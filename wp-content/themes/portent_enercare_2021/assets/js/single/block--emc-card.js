window.addEventListener('load', (event) => {
	console.log('EMC Card Setup');

	let emcCards = document.querySelectorAll( '.block-emc-card__wrapper' );
	console.log( emcCards );
	emcCards.forEach( (card) => {
		let cardRead, cardClose, cardContent;

		cardRead = card.querySelector( '.block-emc-card__trigger' );
		cardClose = card.querySelector( '.block-emc-card__close-button' );
		cardContent = card.querySelector( '.block-emc-card__content' );

		cardRead.addEventListener( 'click', (event) => {
			cardContent.setAttribute( 'aria-hidden', 'false' );
			cardClose.focus();
		});

		cardClose.addEventListener( 'click', (event) => {
			cardContent.setAttribute( 'aria-hidden', 'true' );
			cardRead.focus();
		});
	});
});
