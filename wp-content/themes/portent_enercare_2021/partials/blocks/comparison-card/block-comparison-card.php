<?php
// Create class attribute allowing for custom "className" and "align" values.
$classes = '';
if( !empty($block['className']) ) {
	$classes .= sprintf( ' %s', $block['className'] );
}
if( !empty($block['align']) ) {
	$classes .= sprintf( ' align%s', $block['align'] );
}
if( !empty($block['backgroundColor'] ) ) {
	$classes .= sprintf(' has-%s-background-color', $block['backgroundColor']);
}
$hash_arr = array();

?>
<?php if (have_rows('comparison_cards')) { ?>
<div class="block-comparison-card__wrapper <?php echo esc_attr($classes); ?>">
<?php while ( have_rows('comparison_cards') ) {
  the_row();
  $hash = hash('adler32', random_bytes(18));
  $toggle_open_text = get_sub_field('toggle_open_text');
  $toggle_close_text = get_sub_field('toggle_close_text'); ?>
  <div class="block-comparison-card">
    <h3 class="block-comparison-card__title"><?= get_sub_field('title'); ?></h3>
    <div class="block-comparison-card__contents">
      <?php if( get_sub_field('heading') ): ?>
		<h4 class="block-comparison-card__contents-heading"><?= get_sub_field('heading'); ?></h4>
		<?php endif; ?>

		<?php if( get_sub_field('subheading') ): ?>
			<div class="block-comparison-card__contents-subheading"><?= get_sub_field('subheading'); ?></div>
		<?php endif; ?>

		<?php if( get_sub_field('description') ): ?>
			<p class="block-comparison-card__contents-description"><?= get_sub_field('description'); ?></p>
		<?php endif; ?>


      <?php if (have_rows('comparison_table')) { ?>
      <button aria-expanded="false" class="block-comparison-card__contents-toggle" data-id="<?= $hash; ?>" aria-controls="comparison_card_<?= $hash; ?>" data-toggle-open="<?= $toggle_open_text; ?>" data-toggle-close="<?= $toggle_close_text; ?>">
        <img alt="" src="<?= get_template_directory_uri(); ?>/assets/icons/utility/navigate-down.svg" />
		  <span><?= $toggle_open_text; ?></span>
      </button>
      <div class="block-comparison-card__contents-table comparison_card_<?= $hash; ?>" aria-expanded="false" data-state="closed" aria-labelledby="comparison_card_<?= $hash; ?>">
        <ul>
        <?php while ( have_rows('comparison_table') ) { the_row(); ?>
          <li>
              <?= '<p class="block-comparison-card__contents-table__text">' . get_sub_field('comparison_name'); ?>
              <?php if (get_sub_field('comparison_subtext')) { ?>
              <span class="block-comparison-card__contents-table__subtext"><?= get_sub_field('comparison_subtext'); ?></span>
              <?php } ?>
			  <?= '</p>'; ?>
            <span class="block-comparison-card__contents-table__comparison-icon">
            <?php
              $comparison_value = get_sub_field('comparison_value');
              if ($comparison_value == 'Checkmark')
				  $comparison_value = '<img class="block-comparison-card__contents-table__checkmark-icon" alt="" src="' . get_template_directory_uri() . '/assets/icons/action/done_black_24dp_rounded.svg" /><span class="block-comparison-card__contents-table__checkmark-text">Included</span>';
              echo $comparison_value;
            ?>
            </span>
          </li>
        <?php } ?>
        </ul>
        <?php if (get_sub_field('cta_text')) { ?>
        <div class="block-comparison-card__contents-table__cta"><?= get_sub_field('cta_text'); ?></div>
        <?php } ?>
        <?php if (get_sub_field('cta_button')) { $cta_button = get_sub_field('cta_button'); ?>
        <div class="block-comparison-card__contents-table__cta-button">
          <a class="wp-block-button__link has-red-background-color has-background" href="<?= $cta_button['url'] ?>"><?= $cta_button['title'] ?></a>
        </div>
        <?php } ?>
      </div>
      <?php } ?>

    </div>
  </div>
<?php } ?>
</div>

<div class="block-comparison-card__mobile-wrapper wp-block-portent-block-tabbed-content <?php echo esc_attr($classes); ?>">
  <div class="block-tabbed-content__tabs init block-tabbed-content__tabs--left">
<?php
  $cc_index = 1;
  while ( have_rows('comparison_cards') ) {
    the_row();
    $hash = hash('adler32', random_bytes(18));
    $hash_arr[$cc_index] = $hash; $cc_index++; ?>
    <button class="block-tabbed-content__tab" data-interface="tab-button" data-tab="<?= $hash; ?>"><?= get_sub_field('title'); ?></button>
<?php } ?>
  </div>
  <div class="block-tabbed-content__tab-panels init">
    <?php
    $cc_index = 1;
    while ( have_rows('comparison_cards') ) {
      the_row();
      $hash = $hash_arr[$cc_index]; $cc_index++;
      $toggle_open_text = get_sub_field('toggle_open_text');
      $toggle_close_text = get_sub_field('toggle_close_text'); ?>
    <div class="wp-block-portent-block-tabbed-content--tab block-tabbed-content__panel">
      <div class="block-tabbed-content__tab-content" data-tab="<?= $hash; ?>">

        <div class="block-comparison-card">
          <div class="block-comparison-card__contents">
            <h3 class="block-comparison-card__contents-heading"><?= get_sub_field('heading'); ?></h3>
            <div class="block-comparison-card__contents-subheading"><?= get_sub_field('subheading'); ?></div>
            <p class="block-comparison-card__contents-description"><?= get_sub_field('description'); ?></p>

            <?php if (have_rows('comparison_table')) { ?>
            <button class="block-comparison-card__contents-toggle" aria-expanded="false" data-id="<?= $hash; ?>" aria-controls="comparison_card_<?= $hash; ?>" data-toggle-open="<?= $toggle_open_text; ?>" data-toggle-close="<?= $toggle_close_text; ?>">
              <img alt="" src="<?= get_template_directory_uri(); ?>/assets/icons/utility/navigate-down.svg" />
              <?= $toggle_open_text; ?>
            </button>
            <div class="block-comparison-card__contents-table comparison_card_<?= $hash; ?>" data-state="closed" aria-labelledby="comparison_card_<?= $hash; ?>">
				<ul>
					<?php while ( have_rows('comparison_table') ) { the_row(); ?>
						<li>
							<?= '<p class="block-comparison-card__contents-table__text">' . get_sub_field('comparison_name'); ?>
							<?php if (get_sub_field('comparison_subtext')) { ?>
								<span class="block-comparison-card__contents-table__subtext"><?= get_sub_field('comparison_subtext'); ?></span>
							<?php } ?>
							<?= '</p>'; ?>
							<span class="block-comparison-card__contents-table__comparison-icon">
            <?php
			$comparison_value = get_sub_field('comparison_value');
			if ($comparison_value == 'Checkmark')
				$comparison_value = '<img class="block-comparison-card__contents-table__checkmark-icon" alt="" src="' . get_template_directory_uri() . '/assets/icons/action/done_black_24dp_rounded.svg" /><span class="block-comparison-card__contents-table__checkmark-text">Included</span>';
			echo $comparison_value;
			?>
            </span>
						</li>
					<?php } ?>
				</ul>
              <?php if (get_sub_field('cta_text')) { ?>
              <div class="block-comparison-card__contents-table__cta"><?= get_sub_field('cta_text'); ?></div>
              <?php } ?>
              <?php if (get_sub_field('cta_button')) { $cta_button = get_sub_field('cta_button'); ?>
              <div class="block-comparison-card__contents-table__cta-button">
                <a class="wp-block-button__link has-red-background-color has-background" href="<?= $cta_button['url'] ?>"><?= $cta_button['title'] ?></a>
              </div>
              <?php } ?>
            </div>
            <?php } ?>

          </div>
        </div>

      </div>
    </div>
    <?php } ?>
  </div>

</div>

<?php } ?>
