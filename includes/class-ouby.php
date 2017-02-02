<?php
/**
 * Ouby
 *
 * @package   Ouby
 * @author    Mike Hemberger <mike@bizbudding.com.com>
 * @author    Tonya Mork <hellofromtonya@thewpdc.com>
 * @link      https://github.com/JiveDig/organize-uploads-by-year/
 * @copyright 2016 Mike Hemberger
 * @license   GPL-2.0+
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Main plugin class.
 *
 * @package Ouby
 */
class Ouby {

	public function run() {
		add_action( 'admin_head', array( $this, 'hide_media_upload_setting' ) );
		add_action( 'admin_init', array( $this, 'show_message' ) );
		add_filter( 'upload_dir', array( $this, 'upload_dir_structure' ) );
		// Force media uploads to use yearmonth
		add_filter( 'option_uploads_use_yearmonth_folders', '__return_true', 100 );
	}

	/**
	 * Hide the default upload checkbox with inline CSS
	 *
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function hide_media_upload_setting() {
		echo '<style type="text/css">
			    label[for=uploads_use_yearmonth_folders] {
			    	display: none;
			    	visibility: hidden;
			    }
			  </style>';
	}

	/**
	 * Register a new setting for message display only
	 *
	 * @since  1.0.0
	 *
	 * @return null
	 */
	public function show_message() {
		add_settings_field(
	        'ouby',                      		// ID used to identify the field throughout the theme
	        'Organize Uploads By Year',         // The label to the left of the option interface element
	        array( $this, 'message_callback' ), // The name of the function responsible for rendering the option interface
	        'media',                            // The page on which this option will be displayed
	        'uploads',       					// The name of the section to which this field belongs
	        array(                              // The array of arguments to pass to the callback. In this case, just a description.
	            __('Organize Uploads By Year plugin is uploading all media to /wp-content/uploads/{year}/{filename.ext}</strong>', 'ouby')
	        )
	    );
	}

	/**
	 * Display the field description
	 *
	 * @since  1.0.0
	 *
	 * @param  array  $args  aray of data from add_settings_field()
	 *
	 * @return string
	 */
	public function message_callback( $args ) {
		echo $args[0];
	}

	/**
	 * Filter the WordPress upload directory to show only /year/
	 *
	 * @since  1.0.0
	 *
	 * @param  array  $uploads_dir_data  settings data for upload directory
	 *
	 * @return array
	 */
	public function upload_dir_structure( array $uploads_dir_data ) {
	    // $time = current_time( 'mysql' );
	    // $year = substr( $time, 0, 4 );
	    // $upload['subdir']   = '/' . $year . $upload['subdir'];
	    // $upload['path'] = $upload['basedir'] . $upload['subdir'];
	    // $upload['url']  = $upload['baseurl'] . $upload['subdir'];
		$uploads_dir_data['subdir'] = $this->get_the_year_from_uploads_subdir( $uploads_dir_data['subdir'] );
		$uploads_dir_data['path']   = $uploads_dir_data['basedir'] . $uploads_dir_data['subdir'];
		$uploads_dir_data['url']    = $uploads_dir_data['baseurl'] . $uploads_dir_data['subdir'];
		return $uploads_dir_data;
	}

	/**
	 * Get the year from the uploads subdirectory.  Standard format is '/yyyy/mm'.
	 *
	 * 1. Break the subdir into parts by the '/' delimiter
	 * 2. If for some reason it's empty, bail out.
	 * 3. The array_filter() finds the year, which is based upon a string length of 4.
	 * 4. Then rebuild it with the '/' and year.
	 *
	 * @since 1.0.0
	 *
	 * @param string $subdir
	 * @param string $suffix Default '/'
	 *
	 * @return string
	 */
	public function get_the_year_from_uploads_subdir( $subdir, $suffix = '/' ) {
		$dir_parts = explode( '/', trim( $subdir ) );
		if ( empty( $dir_parts ) ) {
			return '';
		}
		$dir_parts = array_filter( $dir_parts, function( $dir_part ) {
			$dir_part = trim( $dir_part );
			return strlen( $dir_part ) == 4;
		});
		return $suffix . array_shift( $dir_parts );
	}

}
