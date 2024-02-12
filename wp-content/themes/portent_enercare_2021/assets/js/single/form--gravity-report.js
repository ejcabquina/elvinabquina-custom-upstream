(function($, document) {

	/**
	 * Check for the gravity support area. We may have already created it
	 * If we have not, set one up and place it within the body
	 */
	if(!document.body.contains(document.getElementById('gravity-report-area'))) {
		let newGravityReportArea = document.createElement('div');
		newGravityReportArea.id = 'gravity-report-area';
		newGravityReportArea.setAttribute('aria-atomic', 'true');
		newGravityReportArea.setAttribute('aria-live', 'polite');
		newGravityReportArea.classList.add('screen-reader-text');
		document.body.appendChild(newGravityReportArea);
	}

	let gravityReportArea;
	gravityReportArea = document.querySelector('#gravity-report-area');

	$(document).on('gform_post_render', function(event, form_id, current_page) {
		let gravityForm = document.querySelector('#gform_' + form_id);
		let formReport = '';
		/**
		 * Check for errors and report them
		 */
		if(gravityForm.parentElement.classList.contains('gform_validation_error')) {
			formReport += 'Your submission did not go through. Please address the following issues and try your submission again';

			let errors = gravityForm.querySelectorAll('.gfield_validation_message');
			errors.forEach(function(error) {
				formReport += ` ${error.textContent}`;
			});

		}
		gravityReportArea.textContent = formReport;
	});

	/**
	 * Check for submission updates and notify the user if the spinner is on screen
	 * and in the process of submitting the form.
	 */
	const observedGravityForm = document.querySelector('.gform_wrapper');
	const observerConfig = { subtree:true, childList:true };
	const gformObserverCallback = function(mutationList, observer) {
		for (const mutation of mutationList) {
			const addedNodes = mutation.addedNodes;
			let submissionUpdate = '';
			addedNodes.forEach(function(node) {
				if(node.classList && node.classList.contains('gform_ajax_spinner')){
					gravityReportArea.textContent = 'Submitting your request. Please wait';
				}
			});
		}
	};
	const gformObserver = new MutationObserver(gformObserverCallback);
	gformObserver.observe(observedGravityForm, observerConfig);

	/**
	 * Report on gravity forms success page.
	 */
	$(document).on('gform_confirmation_loaded', function(event, form_id) {
		gravityReportArea.textContent = 'Thank you. We\'ll be in touch shortly. Please note that you may receive a call from Enercare or an \'unknown\' number when we try to contact you.'
	});
})(jQuery, document);
