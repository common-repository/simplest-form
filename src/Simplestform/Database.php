<?php

/**
 * Database class.
 * 
 * Method and property for database interaction
 * 
 * @since 1.0
 * 
 */

namespace Simplestform;

class Database extends \Simplestform\Base {
	
	/*
	 * Default data
	 */ 
	
	/**
	 * Table to save data
	 * 
	 * @var string
	 * 
	 * @since 1.0
	 * 
	 */ 
	private $_table = 'posts';
	
	/**
	 * Default comment status
	 * 
	 * @var string
	 * 
	 * @since 1.0
	 * 
	 */ 
	private $_comment_status = 'close';
	
	/**
	 * Default ping status
	 * 
	 * @var string
	 * 
	 * @since 1.0
	 * 
	 */ 
	private $_ping_status = 'close';
	
	/**
	 * Default post status
	 * 
	 * @var string
	 * 
	 * @since 1.0
	 * 
	 */ 
	private $_post_status = 'publish';
	
	/**
	 * The sanitized post
	 * 
	 * @var array
	 * 
	 * @since 1.0
	 * 
	 */ 
	private $_sanitized_post;
	
	
	/**
	 * Name of poster of the form
	 * 
	 * @var string
	 * 
	 * @since 1.0
	 * 
	 */ 
	private $_form_from_name;
	
	/**
	 * Email of poster of the form
	 * 
	 * @var string
	 * 
	 * @since 1.0
	 * 
	 */ 
	private $_form_from_email;
	
	/**
	 * Message of poster.
	 * 
	 * @var string
	 * 
	 * @since 1.0
	 * 
	 */ 
	private $_form_message;
	
	
	/**
	 * Page from submitted
	 * 
	 * @var string
	 * 
	 * @since 1.5.5
	 * 
	 */ 
	private $_form_page;
	
	/**
	 * Our constructor
	 * 
	 * @param array The sanitized post from form.
	 * 
	 * @since 1.0
	 * 
	 */
	 public function __construct($sanitized_post = null) {
		
		parent::__construct();
		
		if ( $sanitized_post != null ) {
		
			$this->_sanitized_post = $sanitized_post;
			
		}
		
	 }
	 
	 

	/**
	 * Save field in database.
	 * Sanitize post and save
	 * 
	 * @since 1.0
	 * 
	 */
	 public function save_frontend_form() {
	 	
		$name = $this->_sanitized_post['name'];
		$email = $this->_sanitized_post['email'];
		$phone = $this->_sanitized_post['phone'];
		$message = $this->_sanitized_post['message'];
		$page = $this->_sanitized_post['page'];
		$privacy = $this->_sanitized_post['privacy'];
		$ip_address = $this->_sanitized_post['ip_address'];
		
		$data = array(
		
			'post_date'			=>	current_time( 'mysql' ),
			'post_date_gmt'		=>	current_time( 'mysql' , 1),
			'comment_status' 	=> 	$this->_comment_status,
			'ping_status'		=>	$this->_ping_status,
			'post_type'			=>	$this->get_custom_post_name(),
			'post_status'   	=>  $this->_post_status,
			'post_title'		=>	$name.' ('.$email.')'
			//'post_content'		=>	$html_email['body']
			
		);
		
		$post_id = wp_insert_post( $data , true );
		
		if (is_int($post_id)) {
			
			update_post_meta( $post_id, $this->get_custom_post_name().'-name', $name );
			update_post_meta( $post_id, $this->get_custom_post_name().'-email', $email );
			update_post_meta( $post_id, $this->get_custom_post_name().'-phone', $phone );
			update_post_meta( $post_id, $this->get_custom_post_name().'-message', $message );
			update_post_meta( $post_id, $this->get_custom_post_name().'-page', $page );
			update_post_meta( $post_id, $this->get_custom_post_name().'-privacy', $privacy );
			
			if ( isset ( $this->_sanitized_post['privacy_art_4'] ) ) {
			
				update_post_meta( $post_id, $this->get_custom_post_name().'-privacy-art-4', $this->_sanitized_post['privacy_art_4'] );
			
			}
			
			if ( isset ( $this->_sanitized_post['privacy_art_5'] ) ) {
			
				update_post_meta( $post_id, $this->get_custom_post_name().'-privacy-art-5', $this->_sanitized_post['privacy_art_5'] );
			
			}
			
			update_post_meta( $post_id, $this->get_custom_post_name().'-ip-address', $this->_sanitized_post['ip_address'] );
			
			return $post_id;
			
		}

	}

	/**
	 * 
	 * Confirm the submission
	 * 
	 * @param int id_post the id of custom post
	 * @since 2.0
	 * 
	 */
	public function confirm_submission_request($id_post) {
		
		$confirmed_on		=	current_time( 'mysql' );
		$confirmed_on_gmt	=	current_time( 'mysql' , 1);
		$confirmed_ip		=	$this->get_ip_of_submitting_form();
		
		update_post_meta( $id_post, $this->get_custom_post_name().'-confirmed-on', $confirmed_on );
		update_post_meta( $id_post, $this->get_custom_post_name().'-confirmed-on-gmt', $confirmed_on_gmt );
		update_post_meta( $id_post, $this->get_custom_post_name().'-confirmed-ip', $confirmed_ip );
		
	}
	 
	 

	/**
	 * 
	 * Set the message
	 * 
	 * @deprecated 1.1
	 * @param string
	 * 
	 * @since 1.0
	 */
	 private function set_form_message($message) {
	 	
		$this->_form_message = $message;
		
	 }
	 
	 /**
	 * 
	 * Get the message
	 * 
	 * @deprecated 1.1
	 * @return string
	 * 
	 * @since 1.0
	 */
	 private function get_form_message() {
	 	
		return $this->_form_message;
		
	 }

	/**
	 * 
	 * Set the name of the poster form
	 * 
	 * @deprecated 1.1
	 * @param string
	 * 
	 * @since 1.0
	 */
	 private function set_form_from_name($name) {
	 	
		$this->_form_from_name = $name;
		
	 }
	 
	 /**
	 * 
	 * Get the name of the poster form
	 * 
	 * @deprecated 1.1
	 * @return string
	 * 
	 * @since 1.0
	 */
	 private function get_form_from_name() {
	 	
		return $this->_form_from_name;
		
	 }
	 
	 /**
	 * 
	 * Set the email of the poster form
	 *
	 * @deprecated 1.1
	 * 
	 * @param string
	 * 
	 * @since 1.0
	 */
	 private function set_form_from_email($email) {
	 	
		$this->_form_from_email = $email;
		
	 }
	 
	 /**
	 * 
	 * Get the email of the poster form
	 * 
	 * @deprecated 1.1
	 * @return string
	 * 
	 * @since 1.0
	 */
	 private function get_form_from_email() {
	 	
		return $this->_form_from_email;
		
	 }
	
}