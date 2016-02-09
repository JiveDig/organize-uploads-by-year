<?php
/**
 * @package   Ouby
 * @author    The Stiz Media, LLC <mike@thestizmedia.com>
 * @author    Tonya Mork <hellofromtonya@thewpdc.com>
 * @license   GPL-2.0+
 * @link      http://thestizmedia.com.com
 * @copyright 2016 The Stiz Media, INC
 *
 * @wordpress-plugin
 * Plugin Name:        Organize Uploads By Year
 * Description: 	   Organize WP media uploads by wp-content/{year}/{filename.ext}. Be sure you want this as your upload directory structure, once you start using this plugin it's not easy to go back!
 * Plugin URI:         TBD
 * Author:             Mike Hemberger
 * Author URI:         http://thestizmedia.com
 * Text Domain:        ouby
 * License:            GPL-2.0+
 * License URI:        http://www.gnu.org/licenses/gpl-2.0.txt
 * Version:            1.0.0
 * GitLab Plugin URI:  TBD
 * GitLab Branch:	   master
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) { die; }

if ( ! defined( 'OUBY_PLUGIN_DIR' ) ) {
	define( 'OUBY_PLUGIN_DIR', dirname( __FILE__ ) );
}
if ( ! defined( 'OUBY_INCLUDES_DIR' ) ) {
	define( 'OUBY_INCLUDES_DIR', OUBY_PLUGIN_DIR . '/includes/' );
}
if ( ! defined( 'OUBY_PLUGIN_URI' ) ) {
	define( 'OUBY_PLUGIN_URI', plugin_dir_url( __FILE__ ) );
}
if ( ! defined( 'OUBY_BASENAME' ) ) {
	define( 'OUBY_BASENAME', dirname( plugin_basename( __FILE__ ) ) );
}

function ouby_require() {
	$files = array(
		'class-ouby',
	);
	foreach ( $files as $file ) {
		require OUBY_INCLUDES_DIR . $file . '.php';
	}
}
ouby_require();

$ouby = new Ouby();
$ouby->run();
