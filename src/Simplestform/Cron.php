<?php

/**
 * Cron class.
 * 
 * Method and property for admin section.
 * 
 * @since 2.0
 * 
 * 
 */

namespace Simplestform;

class Cron extends \Simplestform\Base {
	
	/**
	 * Use cron
	 * 
	 * @var bool
	 * 
	 * @since 2.0
	 * 
	 */
	private $_is_crone_active = false;
	
	
	/**
	 * Setup the cron
	 * 
	 */
	public function setup_cron() {
		
		if ( !wp_next_scheduled( 'simplestform_clean_email' ) ) {
			
			wp_schedule_event( time(), 'hourly', 'simplestform_clean_email' );
			  
		}
		
	}
	
	public function disable_cron () {	
	
		// find out when the last event was scheduled
		$timestamp = wp_next_scheduled ('simplestform_clean_email');
		// unschedule previous event if any
		wp_unschedule_event ($timestamp, 'simplestform_clean_email');
		
	} 
	
}