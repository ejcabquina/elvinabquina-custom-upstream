<?php
//template part of Blue Highlight Offer Card
$terms = get_the_terms( $campaign->ID, 'campaign-category' );
?>
<div class="<?php echo esc_attr($classes); ?>" data-allow-multiple>
    <?php 
        if(!empty($terms)) {
            echo '<p class="block-offer-card__category">'.$terms[0]->name.'</p>';
        }
    ?>
    <?= $campaign_image; ?>
    <div class="block-offer-card__container">
        <h3 class="block-offer-card__heading"><?= $campaign_heading; ?></h3>
        <?php if ($campaign_subheading) { ?>
        <p class="block-offer-card__subheading"><?php echo $campaign_subheading; ?></p>
        <?php } ?>
        <?php if ($campaign_expiration && $campaign_expiration != "") { ?><p class="block-offer-card__offer-expiration">Offer expires <?= date('F d, Y', strtotime($campaign_expiration)); ?>.<?php } ?></p>
            <a class="wp-block-button__link has-red-background-color has-background block-offer-card__link" href="<?= $campaign_destination ?>" aria-label="Get deal for <?= $campaign_heading; ?>"><img class="block-offer-card__link__icon" alt="" width="20" height="20" role="presentation" src="<?= $cta_icon ?>" /><span><?= $cta_text ?></span></a>
            <button class="block-offer-card__terms-toggle" aria-controls="terms_<?= $campaign->ID ?>" data-micromodal-trigger="modal-<?= $campaign->ID ?>" aria-label="Open modal and view Terms and Conditions for <?= $campaign_heading ?>">View Terms and Conditions</button>
    </div>
    <?= $campaign_terms; ?>
</div>