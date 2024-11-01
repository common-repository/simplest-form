<?php

/**
 * 
 */

/**
* Base class.
* It is abstract because we want mantain here his data.
 * 
 * @version 1.3
* 
*/

namespace Simplestform;

use \WP_Query;

abstract class Base {
	
	/**
     * The name of the plugin
	 * 
	 * @since 1.0
	 * 
     * @var string
     */
	private $_plugin_name = 'Simplestform';
	
	/**
     * Current version of *plugin*, not of file! :)
	 * 
	 * @since 1.0
	 * 
     * @var string
     */
	private $_version = '2.0.3';
	
	/**
     * Language domain
	 * 
	 * @since 1.0
	 * 
     * @return string
     */
	private $_language_domain = 'language_domain';
	
	/**
     * Prefix for every single input / key / field / etc.
	 * Prefix has "_" on the end, you don't need to add.
	 * 
	 * @since 1.0
	 * 
     * @var string
     */
	private $_prefix = 'simplestform_';
	
	/**
     * Container for the key(s) of the admin input / textarea form.
	 * 
	 * @since 1.0
	 * 
     * @var Array
     */
	private $_array_admin_key_form;
	
	/**
     * Container for the key(s) of the frontend input / textarea form.
	 * 
	 * @since 1.0
	 * 
     * @var Array
     */
	private $_array_frontend_key_form;
	
	
	/**
     * Base dir of the plugin
	 * 
	 * @since 1.0
	 * 
     * @var string
	 * 
     */
	private $_base_dir = '';
	
	/**
	 * Plugin base name.
	 * 
	 * @since 1.5.3
	 * 
	 * @var string
	 * 
	 */
	private $_plugin_base_name = '';
	
	
	/**
     * Custom post name
	 * 
	 * @since 1.0
	 * 
     * @var string
	 * 
     */
	private $_custom_post_name = 'simplestform_cp';
	
	/**
     * Shortcode TAG
	 * 
	 * @since 1.0
	 * 
     * @var string
	 * 
     */
	private $_shortcode_tag = 'simplest_form_sf';
	
	/**
     * Get a slugged plugin name
	 * 
	 * @since 1.1
	 * 
     * @var string
	 * 
     */
	private $_slugged_plugin_name = 'simplestform';
	
	
	/**
	 * Is authorized "premium" with api key
	 * 
	 * @since 1.3
	 * 
	 * @var bool
	 * 
	 */
	private $_is_premium = false;
	
	
	/**
	 * Base API endpoint url for api key
	 * 
	 * @since 1.2
	 * 
	 * @var string
	 * 
	 */
	private $_api_endpoint_url = 'http://www.tresrl.it/api?';
	
	
	/**
	 * Return answer from check code
	 * 
	 * @since 2.0
	 * 
	 */
	private $_return_answer_from_check_code;
	
	
	/**
     * Our constructor.
	 * Prepare all the key/valus to use in our plugin.
	 * 
	 * @param string $base_directory_plugin The base plugin directory
	 * 
     */
	
	public function __construct($base_directory_plugin = null , $plugin_base_name = null) {
		
		if ( $base_directory_plugin != null ) {
			
			$this->set_base_dir($base_directory_plugin);
			
		}
		
		
		if ( $plugin_base_name != null ) {
			
			$this->set_base_plugin_name($plugin_base_name);
			
		}
		
		$this->check_premium_version();
		
		if ( $this->get_is_premium() ) {
			
			add_action( 'init' , array ( $this , 'setup_cron' ) );
			
			add_action( 'after_setup_theme' , array ( $this , 'simplestform_check_code' ) );
		
			add_action ('simplestform_clean_email' , array ( $this , 'simplestform_clean_email' ) );
			
		}
		
		//add_action( 'init' , array ( $this , 'setup_cron' ) );
		// enable shortcode in widget
		add_filter( 'widget_text' , 'do_shortcode');
		//add_action( 'after_setup_theme' , array ( $this , 'simplestform_check_code' ) );
		
		//add_action ('simplestform_clean_email' , array ( $this , 'simplestform_clean_email' ) );
		
		// admin form && option name
		$this->_array_admin_key_form['from_name'] = $this->get_prefix().'option_from_name';
		$this->_array_admin_key_form['from_email'] = $this->get_prefix().'option_from_email';
		$this->_array_admin_key_form['to_email'] = $this->get_prefix().'option_to_email';
		$this->_array_admin_key_form['email_subject_for_admin'] = $this->get_prefix().'option_subject_for_admin';
		$this->_array_admin_key_form['bcc_recipient'] = $this->get_prefix().'option_bcc_recipient';
		$this->_array_admin_key_form['url_privacy'] = $this->get_prefix().'option_url_privacy';
			// google analytics section
		$this->_array_admin_key_form['ga_ua_code'] = $this->get_prefix().'option_ga_ua_code';
		$this->_array_admin_key_form['ga_category'] = $this->get_prefix().'option_ga_category';
		$this->_array_admin_key_form['ga_action'] = $this->get_prefix().'option_ga_action';
		$this->_array_admin_key_form['ga_label'] = $this->get_prefix().'option_ga_label';
			// api section
		$this->_array_admin_key_form['sf_api_key'] = $this->get_prefix().'option_api_key';
		$this->_array_admin_key_form['sf_api_is_premium'] = $this->get_prefix().'option_api_is_premium';
		$this->_array_admin_key_form['sf_api_last_check'] = $this->get_prefix().'option_api_last_check';
		
		// frontend form
		$this->_array_frontend_key_form['name'] = $this->get_prefix().'form_frontend_name';
		$this->_array_frontend_key_form['email'] = $this->get_prefix().'form_frontend_email';
		$this->_array_frontend_key_form['phone'] = $this->get_prefix().'form_frontend_phone';
		$this->_array_frontend_key_form['message'] = $this->get_prefix().'form_frontend_message';
		$this->_array_frontend_key_form['page'] = $this->get_prefix().'form_frontend_page';
		$this->_array_frontend_key_form['privacy'] = $this->get_prefix().'form_frontend_privacy';
		$this->_array_frontend_key_form['privacy_art_4'] = $this->get_prefix().'form_frontend_privacy_art_4';
		$this->_array_frontend_key_form['privacy_art_5'] = $this->get_prefix().'form_frontend_privacy_art_5';
		
	}

	/**
	 * 
	 * Check and active cron
	 * 
	 * @since 2.0
	 */
	public function setup_cron() {
		
		//$cron = new \Simplestform\SimplestFormCron();
		$cron = new \Simplestform\Cron();
		//$cron->disable_cron();
		$cron->setup_cron();
		//$cron->start_simplestform_cron();
		
	}
	
	/**
	 * 
	 * Clean the email that has not confirmed
	 * 
	 * @since 2.0
	 * 
	 */
	public function simplestform_clean_email() {
		
		$this->delete_contact_with_no_meta_key_confirmed();
		
	}

	/**
	 * Delete email whitch doesn't have the meta key "confirmed on"
	 * 
	 * @since 2.0
	 * 
	 */
	private function delete_contact_with_no_meta_key_confirmed() {
		
		$post = null;
		
		$args = array ( 
		
		
			'posts_per_page' => -1,
			'post_type'		=>	$this->get_custom_post_name(),
			
			'date_query' => array(
			
        		'before' => date('Y-m-d H:i:s', strtotime('-1 days'))
				 
    		)
			
		
		);
		
		$first_key = array (
		
			'key'		=>		$this->get_custom_post_name().'-confirmed-on',
			'compare'	=>		'NOT EXISTS'
		
		);
		
		$meta_query = array (
		
			$first_key
		
		);
		
		
		$args['meta_query'] = array($meta_query);
		
		$temp = new \WP_Query( $args );
		
		if ( $temp->have_posts() ) {
			
			while ( $temp->have_posts() ) {
				
				$temp->the_post();
				
				$id_post = get_the_ID();
				
				wp_trash_post($id_post);
				
				
			}
			
		}
		
	}


	/**
	 * 
	 * Check if exists a code via $_GET
	 * 
	 * @since 1.9.1
	 * 
	 * 
	 */
	public function simplestform_check_code() {
		
		
		$success = __('La tua richiesta è stata confermata con successo. Grazie!' , $this->get_language_domain() );
		$error = __('Non abbiamo trovato alcuna richiesta. Forse hai già confermato questa richiesta oppure il link è scaduto? Ricorda che devi confermare la tua richiesta entro 24h!' , $this->get_language_domain() );
		
		if ( isset ( $_GET['id_submission'] ) ) {
			
			$id_post = $_GET['id_submission'];
			
			// start the check of email sent
			
		}
		
		if ( isset ( $id_post ) ) {
			
			
			$args = array ( 
			
			
				'posts_per_page'	=> 1,
				'post_type'			=> $this->get_custom_post_name(),
				'p'         		=> $id_post
			
			);
			
			$first_key = array (
		
				'key'		=>		$this->get_custom_post_name().'-confirmed-on',
				'compare'	=>		'NOT EXISTS'
			
			);
			
			$meta_query = array (
			
				$first_key
			
			);
			
			
			$args['meta_query'] = array($meta_query);
			
			$temp = new \WP_Query( $args );
			
			if ( $temp->have_posts() ) {
				
				while ( $temp->have_posts() ) {
					
					$temp->the_post();
					
					$id_post = get_the_ID();
					
					$database = new \Simplestform\Database();
					$database->confirm_submission_request($id_post);
					
					$this->set_answer_from_check_code($success);
					$alert_type = 'success';
					
					$email = new \Simplestform\Email(null , $this->get_base_dir() , $id_post , true);
					$email->send_email_to_main_recipients();
					
					
					
				}
				
			} else {
				
				$this->set_answer_from_check_code($error);
				$alert_type = 'error';
				
			}
			
		}

		if ( $this->get_answer_from_check_code() ) {
		
			
			include_once ( $this->get_base_dir().'/views/frontend/alert-submission.php' );
		
		}
		
	}
	
	
	/**
	 * Get the IP of user.
	 * 
	 * @since 1.3
	 * 
	 * @return string the IP or null
	 * 
	 */
	protected function get_ip_of_submitting_form() {
		
		$ip = null;
		
		if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			
			//check ip from share internet
			$ip = $_SERVER['HTTP_CLIENT_IP'];
			
		} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			
			//to check ip is pass from proxy
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
			
		} else {
			
			$ip = $_SERVER['REMOTE_ADDR'];
			
		}

		return $ip;
		
	}

	/**
	 * Check if plugin is premium
	 * 
	 * @since 1.9
	 */
	protected function check_premium_version() {
		
		$authorized = esc_attr( get_option ( $this->get_prefix().'option_api_is_premium' ) );
		
		$need_to_check = true;
		
		if ( $authorized ) {
			
			// get last check
			$last_check = esc_attr( get_option ( $this->get_prefix().'option_api_last_check' ) );
			
			// if last check exists
			if ( $last_check ) {
				
				// get the current time
				$now = current_time( 'timestamp' , 1);
				
				// get last check and add 24h
				$tomorrow = strtotime('+1 day', $last_check);
				
				if ( $now >= $tomorrow ) {
					
					delete_option ( $this->get_prefix().'option_api_is_premium' );
					delete_option ( $this->get_prefix().'option_api_last_check' );
					$need_to_check = true;
					
				} else {
					
					$need_to_check = false;
					
				}
				
				
			} else {
				
				$need_to_check = true;
				
			}
			
		}
		
		if ( $need_to_check === true ) {
			
			$api_key = esc_attr( get_option ( $this->get_prefix().'option_api_key' ));
				
			if ( get_home_url() == 'http://www.tresrl.it' ) {
				
				$authorized = true;
					
			} else {
				
				if ( $api_key ) {
					
					$endpoint = $this->get_api_endpoint_url();
					$url = $endpoint.'api='.$api_key.'&service=simplestform';
							
					$get = wp_remote_get ( $url );
							
					if ( is_array ( $get ) ) {
							
						$response = wp_remote_retrieve_body( $get );
								
						$response = json_decode($response);
								
						if ( isset ( $response->authorized ) ) {
								
							$authorized = $response->authorized;
									
						}
								
					}
							
				}
					
			}
				
		}
			
		if ( $authorized == true && $need_to_check == true ) {
			
			// save in the database
			update_option ( $this->get_prefix().'option_api_is_premium' , true );
			update_option ( $this->get_prefix().'option_api_last_check' , current_time( 'timestamp' , 1) );
			
		}
		
		// set the property
		$this->set_is_premium($authorized);
			
	}
	
	/**
     * Return the key(s) of the input / textarea form.
	 * 
	 * @return Array
	 * 
	 * @since 1.0
	 * 
     */
	protected function get_array_admin_key_form() {
		
		return $this->_array_admin_key_form;
		
	}
	
	/**
     * Return the key(s) of the frontend input / textarea form.
	 * 
	 * @return Array
	 * 
	 * @since 1.0
	 * 
     */
	protected function get_array_frontend_key_form() {
		
		return $this->_array_frontend_key_form;
		
	}
	
	/**
     * Return language domain
	 * 
	 * @return string
	 * 
	 * @since 1.0
	 * 
     */
	protected function get_language_domain() {
		
		return $this->get_prefix().$this->_language_domain;
		
	}
	
	/**
     * Return name of the plugin
	 * 
	 * @return string
	 * 
	 * @since 1.0
	 * 
     */
	protected function get_plugin_name() {
		
		return $this->_plugin_name;
		
	}
	
	/**
     * Return version of the plugin
	 * 
	 * @return string
	 * 
	 * @since 1.1
	 * 
     */
	protected function get_plugin_version() {
		
		return $this->_version;
		
	}
	
	/**
     * Return prefix
	 * 
	 * @return string
	 * 
	 * @since 1.0
	 * 
     */
	protected function get_prefix() {
		
		return $this->_prefix;
		
	}
	
	/**
     * Set base_dir
	 * 
	 * @var string
	 * 
	 * @since 1.0
	 * 
     */
	protected function set_base_dir($base_dir) {
		
		$this->_base_dir = $base_dir;
		
	}
	
	
	/**
	 * Set base plugin name
	 * 
	 * @param string
	 * 
	 * @since 1.5.3
	 */
	private function set_base_plugin_name ( $plugin_base_name ) {
		
		$this->_plugin_base_name = $plugin_base_name;
		
	}
	
	/**
	 * Get base plugin name
	 * 
	 * @return string
	 * 
	 * @since 1.5.3
	 *
	 */
	
	protected function get_base_plugin_name() {
		
		return $this->_plugin_base_name;
		
	}
	
	/**
     * Return base_dir
	 * 
	 * @return string
	 * 
	 * @since 1.0
	 * 
     */
	protected function get_base_dir() {
		
		return $this->_base_dir;
		
	}
	
	/**
     * Return custom post name
	 * 
	 * @return string
	 * 
	 * @since 1.0
	 * 
     */
	protected function get_custom_post_name() {
		
		return $this->_custom_post_name;
		
	}
	
	/**
     * Return shortcode
	 * 
	 * @return string
	 * 
	 * @since 1.0
	 * 
     */
	protected function get_shortcode_tag() {
		
		return $this->_shortcode_tag;
		
	}
	
	/**
	 * Return slugged plugin name
	 * 
	 * @return string
	 * 
	 * @since 1.1
	 */
	protected function get_slugged_plugin_name() {
		return $this->_slugged_plugin_name;
	}
	
	/**
	 * 
	 * Return base api endpoint url
	 * 
	 * @return string
	 * 
	 * @since 1.2
	 * 
	 */
	protected function get_api_endpoint_url() {
		
		return $this->_api_endpoint_url;
		
	}
	
	/**
	  * Set if is premium
	  * 
	  * @param bool
	  * 
	  * @since 1.3
	  * 
	  */
	 protected function set_is_premium( $is_premium ) {
	 	
		$this->_is_premium = $is_premium;
		
	 }
	 
	 /**
	  * 
	  * Get if is premium
	  * 
	  * @return bool
	  * 
	  * @since 1.3
	  * 
	  */
	 protected function get_is_premium() {
	 	
		return $this->_is_premium;
		
	 }
	 
	 /**
	  * Set the answer from check code
	  * 
	  * @param string
	  * 
	  * @since 2.0
	  * 
	  */
	 private function set_answer_from_check_code($answer) {
	 	
		$this->_return_answer_from_check_code = $answer;
		
	 }
	 
	 /**
	  * Get the answer from check code
	  * 
	  * @return string
	  * 
	  * @since 2.0
	  * 
	  */
	 protected function get_answer_from_check_code() {
	 	
		return $this->_return_answer_from_check_code;
		
	 }
		
	
}