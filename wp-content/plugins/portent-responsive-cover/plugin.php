<?php
/**
 * Plugin Name: Responsive Cover Block (Portent)
 * Plugin URI: https://github.com/ahmadawais/create-guten-block/
 * Description: A cover block that mimics the WordPress core cover block but adds the ability to art direct the cover image through use of a picture element instead of img
 * Author: Portent
 * Author URI: https://portent.com
 * Version: 1.0.0
 * License: GPL2+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package PORTENT
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Block Initializer.
 */
require_once plugin_dir_path( __FILE__ ) . 'src/init.php';
