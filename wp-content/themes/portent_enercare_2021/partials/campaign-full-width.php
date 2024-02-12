<?php
	/*
	 * Template partial for the full width variation of the offer card.
	 */
?>
<div class="block-offer-card__wrapper__full-width" data-allow-multiple>
	<div class="block-offer-card__container <?= $background_color_setting ?>">
			<?php if ($campaign_subheading) { ?>
				<p class="block-offer-card__subheading"><?php echo $campaign_subheading; ?></p>
			<?php } ?>
			<h3 class="block-offer-card__heading <?= !empty($block['fontSize']) ? 'is-font-size-'.$block['fontSize'] : ''; ?>"><?= $campaign_heading; ?></h3>
				<?php if(!empty($campaign_excerpt)): ?>
      				<p class="block-offer-card__excerpt"><?= $campaign_excerpt ?></p>
    			<?php endif; ?>

				<p class="block-offer-card__offer-expiration">
					<?php if ($campaign_expiration && $campaign_expiration != "") { ?>
						Offer expires <?= date('F d, Y', strtotime($campaign_expiration)); ?>.
					<?php } ?>
					<button class="block-offer-card__terms-toggle" aria-controls="terms_<?= $campaign->ID ?>" data-micromodal-trigger="modal-<?=$campaign->ID ?>" aria-label="Open modal and view Terms and Conditions for <?= $campaign_heading ?>">View Terms and Conditions</button>
				</p>
			<a href="<?= $campaign_destination; ?>" class="wp-block-button__link has-red-background-color has-background block-offer-card__link" aria-label="Get deal for <?= $campaign_heading; ?>"><img class="block-offer-card__link__icon" alt="" role="presentation" src="<?= $cta_icon ?>"/><?= $cta_text ?></a>
		<?= $campaign_terms; ?>
	</div>
	<?= $campaign_image; ?>
</div>
