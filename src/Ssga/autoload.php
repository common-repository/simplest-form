<?php

spl_autoload_register( 'ssga_autoloader' );

function ssga_autoloader( $class_name ) {
	
	/*
	 * Check if class has our prefix.
	 * Thank to Facebook PHP SDK for the hint.
	 */
	$prefix = 'Ssga\\';
	
    // does the class use the namespace prefix?
    $len = strlen($prefix);
	
	if (strncmp($prefix, $class_name, $len) !== 0) {
        	
        // no, move to the next registered autoloader
        return;
		
    } else {
		
		$classes_dir = realpath ( plugin_dir_path ( __DIR__ ) ) . DIRECTORY_SEPARATOR;
		
		$class_file = str_replace( '\\', DIRECTORY_SEPARATOR, $class_name ) . '.php';
		
		require_once $classes_dir . $class_file;
		
	}
	
}