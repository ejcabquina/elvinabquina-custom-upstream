<?php
/**
 * This is a rough partial. Its very brittle. In the future it makes WAAAAAAY more sense to refactor the offer card setup into a class
 */
$terms_and_conditions = get_field('terms_and_conditions', $campaign->ID);
$campaign_terms = '';
if ($terms_and_conditions && !empty($terms_and_conditions)) {
	$campaign_terms = '
      <span class="block-offer-card__terms">
        <div class="block-offer-card__terms-details modal" id="modal-' . $campaign->ID . '" aria-hidden="true">
          <div class="modal__overlay" tabindex="-1" data-micromodal-close>
            <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-' . $campaign->ID . '-title">
              <div class="modal__header">
                <h2 class="modal__heading" id="modal-' . $campaign->ID . '-title">Terms and Conditions: ' . $campaign_heading . '</h2>
                <button class="block-offer-card__terms-details__close modal__close" aria-label="Close modal" data-micromodal-close></button>
              </div>
              <div class="modal__content block-offer-card__terms-details__content">' . $terms_and_conditions . '</div>
              <div class="modal__footer"></div>
            </div>
          </div>
        </div>
      </span>
    ';
}
