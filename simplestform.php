<?php

/*
Plugin Name: Simplest Form
Description: A simplest contact form
URI: http://www.tresrl.it
Author: TRe Technology And Research S.r.l.
Author URI: http://www.tresrl.it
Version: 2.0.4
License: GPL-2.0+
*/

/*
 * 
 *  * ***************** [DEVELOPER NOTE] **********************
 * 
 * To add (or remove) new fields on the form, follow this pattern.
 * 
 * \Simplestform\Base				__construct()				add field to array 	$this->_array_frontend_key_form
 * \Simplestform\Frontend			validate_form()				if new field is required
 * \Simplestform\Frontend			get_sanitized_post()		to get a sanitized post to use in others field
 * \Simplestform\Database			save_frontend_form()		get new field from sanitized post and add as update_post_meta
 * \Simplestform\Email				__construct()				set new fields
 * \Simplestform\Email				setters and getters			set / get new fields
 * views\email\email-basic-template.php							add field to email (see other fields to add same style)
 * \Simplestform\Email				prepare_body_email()		set new field and his translation
 * \Simplestform\Admin				add_meta_boxes()			add new action
 * \Simplestform\Admin				add_meta_FIELD_box()		add new action
 * \Simplestform\Admin				display_meta_FIELD_box()	display the new meta box
 * views\frontend\basic-form.php								add field to form (see other to follow the same style)
 * 
 * 
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/* AUTOLOADER */
/* Inspired by: https://www.smashingmagazine.com/2015/05/how-to-use-autoloading-and-a-plugin-container-in-wordpress-plugins/
 * 
 */

spl_autoload_register( 'simplestform_autoloader' );

function simplestform_autoloader( $class_name ) {
	
	/*
	 * Check if class has our prefix.
	 * Thank to Facebook PHP SDK for the hint.
	 */
	$prefix = 'Simplestform\\';
	
    // does the class use the namespace prefix?
    $len = strlen($prefix);
	
	if (strncmp($prefix, $class_name, $len) !== 0) {
        	
        // no, move to the next registered autoloader
        return;
		
    } else {
		
		$classes_dir = realpath ( plugin_dir_path ( __FILE__ ) ) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR;
	
		$class_file = str_replace( '\\', DIRECTORY_SEPARATOR, $class_name ) . '.php';
	
		require_once $classes_dir . $class_file;
		
	}
	
}

/**
 * The base dir of the plugin.
 * Returns the absolute path.
 * E.g. /var/docs/html/public/wp-content/plugins/simplest-form
 * 
 * @var string
 * 
 * 
 */

$base_dir = plugin_dir_path( __FILE__ );
$plugin_base_name = plugin_basename(__FILE__);


/**
 * Load plugin textdomain.
 * The directory doesn't need to have full path!!!
 *
 * @since 2.0.3
 */
add_action( 'init', 'enable_translation' );

function enable_translation() {
		
	load_plugin_textdomain( 'simplestform_language_domain' , FALSE , basename( dirname( __FILE__ ) ).'/inc/lang/' );
	
	
}

if ( is_admin() ) {
	
		
	if ( ! isset ( $admin_simplestform ) ) {

		$admin_simplestform = new \Simplestform\Admin($base_dir , $plugin_base_name);
		
	}
	
}

if ( ! isset ( $frontend_simplestform ) ) {
	
	$frontend_simplestform = new \Simplestform\Frontend($base_dir , $plugin_base_name);
	
}