<?php
/**
 * Login Logo
 *
 * @package      EAStarter
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
**/

/**
 * Login Logo URL
 *
 */
function enercare_login_header_url( $url ) {
    return esc_url( home_url() );
}
add_filter( 'login_headerurl', 'enercare_login_header_url' );
add_filter( 'login_headertext', '__return_empty_string' );

/**
 * Login Logo
 *
 */
function enercare_login_logo() {

  $logo_id = get_field( 'enercare_default_logo', 'options');
  $logo_path = wp_get_attachment_url($logo_id);
	
	if( !$logo_path )
		return;
    ?>
    <style type="text/css">
    .login h1 a {
        background-image: url(<?php echo $logo_path;?>);
        background-size: contain;
        background-repeat: no-repeat;
        background-position: center center;
        display: block;
        overflow: hidden;
        text-indent: -9999em;
        width: 312px;
        height: 100px;
    }
    </style>
    <?php
}
add_action( 'login_head', 'enercare_login_logo' );
