<?php
/**
 * Newsletter Sign Up Block
 */
// Create class attribute allowing for custom "className" and "align" values.
$classes = "";
if (!empty($block["className"])) {
	$classes .= sprintf(" %s", $block["className"]);
}
if (!empty($block["align"])) {
	$classes .= sprintf(" align%s", $block["align"]);
}
if (!empty($block["backgroundColor"])) {
	$classes .= sprintf(" has-%s-background-color", $block["backgroundColor"]);
}

$heading = get_field("heading");
$content = get_field("content");
$gform = get_field("gravity_form");
?>

<section class="block-newsletter-form <?php echo esc_attr(
	$classes
); ?>" data-interface="block-newsletter-form">
	<div class="block-newsletter-form__container">
		<div class="block-newsletter-form__content-container">
			<img data-interface="block-newsletter-form__image" class="block-newsletter-form__image" src="<?= get_template_directory_uri() ?>/assets/img/join-email-list-24px-thin-r.svg" />
			<div class="block-newsletter-form__content">
				<p data-interface="block-newsletter-form__heading" class="block-newsletter-form__heading"><?php _e(
    	$heading,
    	"portent_enercare"
    ); ?></p>
				<div data-interface="block-newsletter-form__content" class="block-newsletter-form__content"><?php _e(
    	$content,
    	"portent_enercare"
    ); ?></div>
			</div>
		</div>
		<div class="block-newsletter-form__form-container">
			<iframe width=378 height=410 style="border:none;" scrolling="no" src="https://mc7h6pzvkzvcryh60nr99md1dmd0.pub.sfmc-content.com/iq5ulrci3fn"></iframe>
		</div>
	</div>
</section>
