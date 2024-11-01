<?php

/**
 * Frontend class.
 * 
 * Method and property for frontend section.
 * 
 * 
 * @version 1.3
 * 
 */

namespace Simplestform;

// require library for Google Analytics
require plugin_dir_path( __DIR__ ).'Ssga/autoload.php';

class Frontend extends \Simplestform\Base {
	
	/**
	 * Contain error submitted form 
	 * 
	 * @since 1.0
	 * 
	 * @return Array
	 * 
	 */
	private $_frontend_error;
	
	/**
	 * Success feedback
	 * 
	 * @since 1.0
	 * 
	 * @return string
	 * 
	 */
	private $_frontend_success;
	
	/**
     * Our constructor.
	 * 
	 * 
     */
	
	public function __construct( $base_dir = null , $plugin_base_name = null ) {
		
		parent::__construct($base_dir , $plugin_base_name);
		
		/*
		 * 
		 * Register shortcode
		 * 
		 */ 
		$this->add_shortcode();
		
		/**
		 * 
		 * Launch all init scripts
		 */
		 $this->perform_init_hooked_frontend_action();
		 
		 /**
		 * 
		 * Launch all wp_enqueue_scripts action
		 */
		 $this->perform_wp_enqueue_scripts_hooked_frontend_action();
		
	}
	
	/**
     * Perform all actions for frontend that need to be hooked in init.
	 * 
	 * @since 1.0 
     */
	
	private function perform_init_hooked_frontend_action() {
		
		add_action ( 'init' , array ( $this , 'submit_form' ) );
		add_action ( 'init' , array ( $this , 'register_simplestform_css' ) );
		
	}
	
	/**
	 * Perform all enqueue scripts action
	 * 
	 * @since 1.1
	 */
	
	private function perform_wp_enqueue_scripts_hooked_frontend_action() {
		
		add_action ( 'wp_enqueue_scripts' , array ( $this , 'enqueue_simplesform_css' ));
		
	}
	
	/**
	 * Register the css.
	 * Callback from perform_init_hooked_frontend_action
	 * 
	 * @since 1.1
	 */
	public function register_simplestform_css() {
		
		wp_register_style( $this->get_slugged_plugin_name().'-theme', plugins_url().'/simplest-form/assets/css/frontend/simplestform.css' , false, $this->get_plugin_version() , 'all');
		
	}
	
	/**
	 * 
	 * Enqueue the CSSs.
	 * 
	 * Callback from perform_wp_enqueue_scripts_hooked_frontend_action
	 * 
	 * @since 1.1
	 */
	public function enqueue_simplesform_css() {
		
		wp_enqueue_style ( $this->get_slugged_plugin_name().'-theme' );		
	}
	
	
	/**
     * Register the shortcode
	 * 
	 * @since 1.0 
     */
	
	private function add_shortcode() {
		
		add_shortcode( $this->get_shortcode_tag() , array ( $this , 'render_contact_form' ) );
		
	}
	
	/**
     * Render the contact form.
	 * Include the file.
	 * 
	 * @since 1.0 
     */
	
	public function render_contact_form() {
			
			
		// check if settings are saved
		$main_recipient = get_option( $this->get_prefix().'option_to_email' );
		ob_start();
		if ( $main_recipient === false ) {
			
			// no options set
			include_once ( $this->get_base_dir().'/views/frontend/alert.php' );
			
			
		} else {
			
			include_once ( $this->get_base_dir().'/views/frontend/basic-form.php' );
			
		}
		return ob_get_clean();
		
		
		
	}
	
	/**
     * Perform the submit of form.
	 * Called from perform_init_hooked_action.
	 * 
	 * @since 1.0 
     */
	
	public function submit_form() {
		
		$prefix = $this->get_prefix();
		$submit = $prefix.'submit';
		
		if ( isset ( $_POST[$submit] ) ) {
			
			$nonce = $_POST['_wpnonce'];
			
			if ( ! wp_verify_nonce ( $nonce , $this->get_prefix().'nonce_field' ) ) {
				
				wp_die();
				
			}
			
			// check if trap is filled.
			// @since 1.3
			
			if ( isset ( $_POST['trap'] ) ) {
			
				if ( $_POST['trap'] != '' ) {
				
					wp_die(_e( 'Non inserire nulla nel campo "Trap"' , $this->get_language_domain() ) );
				
				}
				
			}
			
			$error = $this->validate_form();
			
			/*
			 * $error is an array of 0 or 1 or multiple errors.
			 * If 0, all ok, proceed.
			 * Otherwise, stop all and set frontend errors.
			 * 
			 */ 
			
			if ( count ( $error ) == 0 ) {
				
				$sanitized_post = $this->get_sanitized_post();
				
				// 1 - save to database();
				$database = new \Simplestform\Database($sanitized_post);
				
				$id_post = null;
				$id_post = $database->save_frontend_form();
				
				
				// 2 - send the email to main reciepients
				$email = new \Simplestform\Email($sanitized_post , $this->get_base_dir() , $id_post);
				$email->send_email_to_main_recipients($id_post);
				
				// 3 - send the email copy to the poster
				$email->send_email_to_poster();
				
				// 4 - set GA goal for submit.
				$this->set_ga_goal();
				
				
				// 5 - set frontend success
				$this->set_frontend_success($sanitized_post['email']);
				
			} else {
				
				$this->set_frontend_error($error);
				
			}
			
		}
		
		
	}

	/**
	 * 
	 * Set the goal for Analytics.
	 * Send after submit. No Jquery involved.
	 * 
	 * @since 1.6
	 * 
	 */
	private function set_ga_goal() {
		
		
		$ga_code = get_option( $this->get_prefix().'option_ga_ua_code' );
		
		if ( $ga_code != false || strlen( $ga_code ) > 0 ) {
			
			$domain = get_home_url();
			
			$category = get_option( $this->get_prefix().'option_ga_category' );
			$action = get_option( $this->get_prefix().'option_ga_action' );
			$label = get_option( $this->get_prefix().'option_ga_label' );
			
			if (  $category!=false && $action!=false && $label!=false ) {
				
				if ( ( strlen($category) > 0 ) && ( strlen($action) > 0 ) && ( strlen($label) > 0 ) ) {
					
					$ga = new \Ssga\Ssga($ga_code , $domain);
					
					$ga->set_event( $category , $action , $label );
					$ga->send();
					
				}
				
			}
			
			
		}
		
		
	}

	/**
	 * 
	 * Sanitize the posted data and return a safe $post array
	 * 
	 * @return array $post The safe and sanitized $_POST.
	 */
	 private function get_sanitized_post() {
	 	
		$field = $this->get_array_frontend_key_form();
					
		$name = sanitize_text_field($_POST[$field['name']]);
		$email = sanitize_text_field($_POST[$field['email']]);
		$phone = sanitize_text_field($_POST[$field['phone']]);
		$message = sanitize_text_field($_POST[$field['message']]);
		$page = sanitize_text_field($_POST[$field['page']]);
		$privacy = sanitize_text_field($_POST[$field['privacy']]);
		
		if ( isset ( $_POST[$field['privacy_art_4']] ) ) {
		
			$privacy_art_4 = sanitize_text_field($_POST[$field['privacy_art_4']]);
			
			$post['privacy_art_4'] = $privacy_art_4;
			
		}
		
		
		if ( isset ( $_POST[$field['privacy_art_5']] ) ) {
		
			$privacy_art_5 = sanitize_text_field($_POST[$field['privacy_art_5']]);
			
			$post['privacy_art_5'] = $privacy_art_5;
			
		}
		
		// add the ip address
		$ip_address = $this->get_ip_of_submitting_form();
		
		// create random code
		
		$post['name'] = $name;
		$post['email'] = $email;
		$post['phone'] = $phone;
		$post['message'] = $message;
		$post['page'] = $page;
		$post['ip_address'] = $ip_address;
		$post['privacy'] = $privacy;
		
		
		
		return $post;
		
	 }
	 
	
	/**
     * Validate the form.
	 * If array returned is not emtpy, there was one (or more) error
	 * 
	 * @return Array
	 * 
	 * @since 1.0 
     */
     private function validate_form() {
			
		$error = array();
		
		$prefix = $this->get_prefix();
		// 1 - check required field
		
		$field = $this->get_array_frontend_key_form();
		
		if ( $_POST[$field['name']] == '' || $_POST[$field['email']] == '' ) {
				
			$error[] = __('Alcuni campi obbligatori non sono stati compilati' , $this->get_language_domain() );
				
		}
		
		// IS_EMAIL: contain non valid chars!
		
		if ( !is_email ( $_POST[$field['email']] ) ) {
				
			$error[] = __('L\'email inserita non sembra corretta' , $this->get_language_domain() );
				
		}
		
		// privacy check
		if ( ! isset( $_POST[$field['privacy']] ) ) {
				
			$error[] = __('L\'accettazione privacy è obbligatoria' , $this->get_language_domain() );
				
		}
		
		return $error;
		
		
     }
	 
	 /**
     * Set error array
	 * 
	 * @var Array
	 * 
	 * @since 1.0 
     */
     private function set_frontend_error($error) {
     	
		$this->_frontend_error = $error;
		
     }
	 
	 /**
     * Set success feedback
	 * 
	 * @var string
	 * 
	 * @since 1.0 
     */
     private function set_frontend_success($email) {
     	
		$string = __( ' Abbiamo ricevuto la tua richiesta ed una copia è stata inviata al tuo indirizzo %s. Se non fosse corretto, inviaci una nuova richiesta. Grazie! ' , $this->get_language_domain() );
		
		$this->_frontend_success = sprintf( esc_html__( $string , $this->get_language_domain() ) , $email );
		
     }
	 
	 /**
     * Get error array
	 * 
	 * @return Array
	 * 
	 * @since 1.0 
     */
     private function get_frontend_error() {
     	
		return $this->_frontend_error;
		
     }
	 
	/**
     * Get success string
	 * 
	 * @return string
	 * 
	 * @since 1.0 
     */
     private function get_frontend_success() {
     	
		return $this->_frontend_success;
		
     }

	
}	