<?php
/**
 * Site Footer
 *
 * @package      GrownAndFlown2020
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
**/

/**
 * Register footer widget areas
 *
 */
function enercare_register_footer_widget_areas() {

	for( $i = 1; $i <=4; $i++ ) {

		register_sidebar( enercare_widget_arenercare_args( array(
			'name' => esc_html__( 'Footer ' . $i, 'ea-starter' ),
		) ) );
	}

  register_sidebar( enercare_widget_arenercare_args( array(
    'name' => esc_html__( 'Commercial Footer', 'ea-starter' ),
  ) ) );

}
add_action( 'widgets_init', 'enercare_register_footer_widget_areas' );

/**
 * Footer Legal area
 *
 */
function footer_legal() {
  if (is_single() || is_page()) {
    if( get_field('terms_and_conditions') ) {
      $terms = get_field('terms_and_conditions');
      echo '<div class="legal__terms">';
        echo '<button class="legal__terms-toggle" aria-expanded="false" aria-controls="terms_' . get_the_ID() . '"><span>Legal</span> <img alt="" src="' . get_template_directory_uri() . '/assets/icons/utility/navigate-down.svg" /></button>';
        echo '<div class="legal__terms-details" data-state="closed" aria-labelledby="terms_' . get_the_ID() . '">' . $terms . '</div>';
      echo '</div>';
    }
  }
}
add_action( 'tha_footer_before', 'footer_legal', 1 );

/**
 * Footer Widget Areas
 *
 */
function enercare_site_footer_widgets() {
	if (get_post_type(get_the_ID()) != "landing-page") {
		echo '<div class="footer-widgets"><div class="wrap">';
		// check if page has Commercial site override. Display the commercial footer if so
		if (get_field('site_override') && get_field('site_override') == 'Commercial') {
			dynamic_sidebar( 'commercial-footer' );
		} else {
			for( $i = 1; $i < 4; $i++ ) {
				dynamic_sidebar( 'footer-' . $i );
			}
		}
		echo '</div></div>';
	}
}
add_action( 'tha_footer_before', 'enercare_site_footer_widgets', 2 );

/**
 * Areas we Serve
 */
function enercare_site_footer_areas() {

}
add_action('tha_footer_top', 'enercare_site_footer_areas');

/**
 * Site Footer
 *
 */
function enercare_site_footer() {
	echo '<div class="footer-left">';
		echo '<p class="copyright">&copy; ' . date( 'Y' ) . ' ' . get_bloginfo( 'name' ) . '</p>';
		echo '<ul class="footer-links"><li><a href="/privacy-policy">Privacy Policy</a></li><li><a href="' . home_url( 'legal/terms-of-use' ) . '">Terms of Use</a></li><li><a href="#">ECRA/ESA License #7010500</a></li></ul>';
	echo '</div>';
	echo '<div class="footer-right">';
		dynamic_sidebar( 'footer-4' );
	echo '</div>';
	//echo '<a class="backtotop" href="#main-content">Back to top' . enercare_icon( array( 'icon' => 'arrow-up' ) ) . '</a>';
}
add_action( 'tha_footer_top', 'enercare_site_footer' );
