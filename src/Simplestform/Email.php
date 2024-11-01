<?php

/**
 * Email class.
 * 
 * Method and property for send email.
 * The mail will be sent via *authenticated* method.
 * 
 * @since 1.0
 * 
 * @version 1.7
 * 
 */

namespace Simplestform;

class Email extends \Simplestform\Base {
	
	/**
	 * 
	 * The body email, with substituted variables
	 */
	 private $_body_email;
	
	/**
	 * 
	 * Base dir of plugin
	 * 
	 * @var string
	 * 
	 * @since 1.0
	 */
	 private $_plugin_base_directory;
	 
	/**
	 * 
	 * Page submitted via form
	 * 
	 * @var string
	 * 
	 * @since 1.5.5
	 */
	 private $_submitted_page_from_form;
	
	/**
	 * 
	 * Name submitted via form
	 * 
	 * @var string
	 * 
	 * @since 1.0
	 */
	 private $_submitted_name_from_form;
	 
	/**
	 * 
	 * Phone submitted via form
	 * 
	 * @var string
	 * 
	 * @since 1.2
	 */
	 private $_submitted_phone_from_form;
	
	/**
	 * 
	 * Email submitted via form
	 * 
	 * @var string
	 * 
	 * @since 1.0
	 */
	 private $_submitted_email_from_form;
	 
	/**
	 * 
	 * Message submitted via form
	 * 
	 * @var string
	 * 
	 * @since 1.0
	 */
	 private $_submitted_message_from_form;
	 
	/**
	 * 
	 * Privacy submitted via form
	 * 
	 * @var string
	 * 
	 * @since 1.1
	 */
	 private $_submitted_privacy_from_form;
	 
	/**
	 * 
	 * Privacy submitted via form
	 * 
	 * @var string
	 * 
	 * @since 1.6
	 */
	 private $_submitted_privacy_art_4_from_form;
	 
	/**
	 * 
	 * Privacy submitted via form
	 * 
	 * @var string
	 * 
	 * @since 1.6
	 */
	 private $_submitted_privacy_art_5_from_form;
	 
	 /**
	 * 
	 * Privacy submitted via form
	 * 
	 * @var string
	 * 
	 * @since 1.6
	 */
	 private $_submitted_ip_address_from_form;
	 
	 /**
	 * 
	 * Placeholder for eventually error on sending the email
	 * 
	 * @var string
	 * 
	 * @since 1.0
	 */
	 private $_on_send_error;
	
	/**
	 * Our constructor.
	 * 
	 * @param mixed[] $post An array containing the submitted data from form.
	 * @param string $base_directory_plugin THe base plugin directory
	 * @param mixed[] the id post if exists, to send via email
	 * @param bool if email is called internal (e.g. from base.php) or no
	 * 
	 * @since 1.0
	 */
	 public function __construct($post = null, $base_plugin_directory , $id_post = null , $internal_call = false) {
	 	
		$this->set_base_dir($base_plugin_directory);
	 	
		if ( ( is_array ( $post ) ) || ( count ( $post ) > 0 ) ) {
			
			if ( isset ( $post['name'] ) ) {
				
				$this->set_submitted_name_from_form($post['name']);
				
			}
			
			if ( isset ( $post['email'] ) ) {
				
				$this->set_submitted_email_from_form($post['email']);
				
			}
			
			if ( isset ( $post['phone'] ) ) {
				
				$this->set_submitted_phone_from_form($post['phone']);
				
			}
			
			if ( isset ( $post['message'] ) ) {
				
				$this->set_submitted_message_from_form($post['message']);
				
			}
			
			if ( isset ( $post['page'] ) ) {
				
				$this->set_submitted_page_from_form($post['page']);
				
			}
			
			if ( isset ( $post['privacy'] ) ) {
				
				$this->set_submitted_privacy_from_form($post['privacy']);
				
			}
			
			if ( isset ( $post['privacy_art_4'] ) ) {
				
				$this->set_submitted_privacy_art_4_from_form($post['privacy_art_4']);
				
			}
			
			if ( isset ( $post['privacy_art_5'] ) ) {
				
				$this->set_submitted_privacy_art_5_from_form($post['privacy_art_5']);
				
			}
			
			if ( isset ( $post['ip_address'] ) ) {
				
				$this->set_submitted_ip_address_from_form($post['ip_address']);
				
			}
			
		}

		/*
		 * Prepare the body of email.
		 * We get the template and substitute variables
		 */ 
			$this->prepare_body_email( $id_post , $internal_call );
	 	
	 }
	 
	 /**
	  * 
	  * Set the main data for headers. Many options got from wp_options
	  */
	 private function prepare_body_email( $id_post = null , $internal_call = false ) {
	 	
		// 1 -get file of email template
		
		if ( $internal_call === false ) {
			
			$email = file_get_contents( $this->get_base_dir() .'/views/email/email-basic-template.php');
		
			// 2 - substistute text
			$preheader = _x( 'Copia della richiesta di informazioni' , $this->get_language_domain() );
			$p_1 = _x( 'grazie per averci scritto.' , $this->get_language_domain() );
			//$p_2 = _x( 'Nel più breve tempo possibile sarai ricontattato con le risposte alle tue domande.' , $this->get_language_domain() );
			//$p_2 = _x('Nel rispetto della normativa sulla privacy, ti chiediamo di voler confermare l\'inserimento della tua richiesta cliccando il bottone seguente' , $this->get_language_domain() );
			$p_2 = _x('Per registrare la tua richiesta devi confermare l\'operazione cliccando sul bottone seguente' , $this->get_language_domain() );
			
			$p_3 = _x('Se non si clicca sul link entro 24 ore, la tua richiesta verrà cancellata. Dovrai pertanto eseguire una nuova richiesta' , $this->get_language_domain() );
			
			$p_4 = _x( 'Nel frattempo, qui sotto trovi una copia delle informazioni inserite. Se qualcuna di esse fosse errata, inviaci una nuova richiesta!' , $this->get_language_domain() );
			
			$confirm = _x('Conferma la tua richiesta' , $this->get_language_domain() );
			
			if ( ( isset ( $id_post ) ) && ( $id_post != null ) ) {
				
				$url_confirm = get_home_url().'?id_submission='.$id_post;
				
			} else {
				
				$url_confirm = get_home_url().'?confirm_email_submission='.urlencode($this->get_submitted_email_from_form());
				
			}
			
		} else {
			
			$preheader = '';
			$p_1 = '';
			$p_2 = '';
			$p_3 = '';
			$p_4 = '';
			$confirm = '';
			$url_confirm = '';
			
			
			$confirmed_on = get_post_meta ( $id_post , $this->get_custom_post_name().'-confirmed-on' , true);
			
			$name = get_post_meta ( $id_post , $this->get_custom_post_name().'-name' , true );
			
			$this->set_submitted_name_from_form($name);
			
			$phone = get_post_meta ( $id_post , $this->get_custom_post_name().'-phone' , true );
			$this->set_submitted_phone_from_form($phone);
			
			$mail = get_post_meta ( $id_post , $this->get_custom_post_name().'-email' , true );
			$this->set_submitted_email_from_form($mail);
			
			$page = get_post_meta ( $id_post , $this->get_custom_post_name().'-page' , true );
			$this->set_submitted_page_from_form($page);
			
			$message = get_post_meta ( $id_post , $this->get_custom_post_name().'-message' , true );
			$this->set_submitted_message_from_form($message);
			
			$privacy_art_4 = get_post_meta ( $id_post , $this->get_custom_post_name().'-privacy-art-4' , true );
			$this->set_submitted_privacy_art_4_from_form($privacy_art_4);
			
			$privacy_art_5 = get_post_meta ( $id_post , $this->get_custom_post_name().'-privacy-art-5' , true );
			$this->set_submitted_privacy_art_5_from_form($privacy_art_5); 
			
			
			
			$email = file_get_contents( $this->get_base_dir() .'/views/email/email-basic-template-for-admin.php');
			
			//name
			$label_confirmed_on = _x( 'Richiesta confermata in data' , $this->get_language_domain() );
			$email = str_replace('{label_confirmed_on}' , $label_confirmed_on , $email);
			$email = str_replace('{confirmed_on}' , $confirmed_on , $email);
			
			
		}
		
		// 3- substistute variables
		
		$email = str_replace('{preheader}' , $preheader , $email);
		$email = str_replace('{p_1}' , $p_1 , $email);
		$email = str_replace('{p_2}' , $p_2 , $email);
		$email = str_replace('{p_3}' , $p_3 , $email);
		$email = str_replace('{p_4}' , $p_4 , $email);
		
		$email = str_replace('{url_confirm}' , $url_confirm , $email);
		$email = str_replace('{confirm}' , $confirm , $email);
		
		
		//message
		$email = str_replace('{message}' , $this->get_submitted_message_from_form() , $email);
		
		//name
		$label_name = _x( 'Nome' , $this->get_language_domain() );
		$email = str_replace('{label_name}' , $label_name , $email);
		$email = str_replace('{name}' , $this->get_submitted_name_from_form() , $email);
		
		//phone
		$label_phone = _x( 'Telefono' , $this->get_language_domain() );
		$email = str_replace('{label_phone}' , $label_phone , $email);
		$email = str_replace('{phone}' , $this->get_submitted_phone_from_form() , $email);
		
		//email
		$label_email = _x( 'Email' , $this->get_language_domain() );
		$email = str_replace('{label_email}' , $label_email , $email);
		$email = str_replace('{email}' , $this->get_submitted_email_from_form() , $email);
		
		
		//email
		$label_page = _x( 'Pagina' , $this->get_language_domain() );
		$email = str_replace('{label_page}' , $label_page , $email);
		$email = str_replace('{page}' , $this->get_submitted_page_from_form() , $email);
		
		// privacy
		$label_privacy = _x( 'Accettazione privacy' , $this->get_language_domain() );
		$yes = _x('Si' , $this->get_language_domain() );
		$email = str_replace('{label_privacy}' , $label_privacy , $email);
		$email = str_replace('{privacy}' , $yes , $email);
		
		// privacy
		$label_privacy = _x( 'Accettazione privacy ART 4' , $this->get_language_domain() );
		
		$answer = _x('Si' , $this->get_language_domain() );
		
		if ( $this->get_submitted_privacy_art_4_from_form() == '' ) {
			
			$answer = _x('No' , $this->get_language_domain() );
			
		}
		
		$email = str_replace('{label_privacy_art_4}' , $label_privacy , $email);
		$email = str_replace('{privacy_art_4}' , $answer , $email);
		
		// privacy
		$label_privacy = _x( 'Accettazione privacy ART 5' , $this->get_language_domain() );
		
		$answer = _x('Si' , $this->get_language_domain() );
		
		if ( $this->get_submitted_privacy_art_5_from_form() == '' ) {
			
			$answer = _x('No' , $this->get_language_domain() );
			
		}
		
		$email = str_replace('{label_privacy_art_5}' , $label_privacy , $email);
		$email = str_replace('{privacy_art_5}' , $answer , $email);
		
		$this->set_body_email($email);
		
		//file_get_contents(WPTRESF__PLUGIN_DIR.'/email-templates/simple-contact.php');
		
	 } 
	 
	 /**
	  * Prepare email (template) and send to the recipients (and eventually the BCCs). Main recipient probably will be site admin.
	  * 
	  * THIS IS **NOT** THE METHOD TO SEND A COPY TO THE POSTER!
	  * THIS IS **NOT** THE METHOD TO SEND A COPY TO THE POSTER!!
	  * THIS IS **NOT** THE METHOD TO SEND A COPY TO THE POSTER!!!
	  * 
	  */
	 public function send_email_to_main_recipients() {
	 	
		// main recipient. Admin of website. Not the poster
		$to = get_option( $this->get_prefix().'option_to_email' );
		
		// the subject
		$subject = get_option( $this->get_prefix().'option_subject_for_admin' );
		
		// the message
		$message = $this->get_body_email();
		
		// from is by website. To deny spam
		//$from = 'From: '.get_site_url().' <'.$to.'>';
		$from = get_option( $this->get_prefix().'option_from_name' );
		$from = 'From: '.$from.' <'.$to.'>';
		
		// reply to. So the administrator can answer directly to the poster
		$reply_to = 'Reply-To: '.$this->get_submitted_name_from_form().' <'.$this->get_submitted_email_from_form().'>';
		
		// construct the errors
		$headers = array(
		
							'Content-Type: text/html; charset=UTF-8',
							$from,
							$reply_to
		
						);
						
		// add bcc.
		// from 1.5.3
		
		$bcc = $this->get_bcc();
		
		if ( $bcc != false ) {
			
			foreach ($bcc as $b) {
			
				$headers[] = 'BCC: '.$b;
			
			}
			
		}
		
		$sent = wp_mail( $to , $subject , $message , $headers );
		
		// there was an error
		if ( $sent==false ) {
			
			if ( isset ( $GLOBALS['phpmailer']->ErrorInfo ) ) {
				
				$this->set_on_send_error = $GLOBALS['phpmailer']->ErrorInfo;
				
			}
			
		}
		
	 }


	/**
	  * Prepare email (template) and send to the poster.
	  * 
	  * THIS IS THE METHOD TO SEND A COPY TO THE POSTER!
	  * THIS IS THE METHOD TO SEND A COPY TO THE POSTER!!
	  * THIS IS THE METHOD TO SEND A COPY TO THE POSTER!!!
	  * 
	  */
	 public function send_email_to_poster() {
			
		// Main email sender from website
		$main_email = get_option( $this->get_prefix().'option_to_email' );
	 	
		// main recipient. The poster
		$to = $this->get_submitted_email_from_form();
		
		// the subject
		$subject = get_option( $this->get_prefix().'option_subject_for_admin' );
		
		// the message
		$message = $this->get_body_email();
		
		// from is by website. To deny spam
		//$from = 'From: '.get_site_url().' <'.$main_email.'>';
		$from = get_option( $this->get_prefix().'option_from_name' );
		$from = 'From: '.$from.' <'.$main_email.'>';
		
		// reply to. So the POSTER can answer directly to website
		$reply_to = 'Reply-To: '. get_option( $this->get_prefix().'option_from_name' ) .' <'. get_option( $this->get_prefix().'option_to_email' ) .'>';
		
		// construct the errors
		$headers = array(
		
							'Content-Type: text/html; charset=UTF-8',
							$from,
							$reply_to
		
						);
						
		$sent = wp_mail( $to , $subject , $message , $headers );
		
		if ( $sent==false ) {
			
			if ( isset ( $GLOBALS['phpmailer']->ErrorInfo ) ) {
				
				$this->set_on_send_error = $GLOBALS['phpmailer']->ErrorInfo;
				
			}
			
		}
		
	 }
	 
	/**
	 * Get BCC recipients (if any).
	 * 
	 * @return false if nobody found, or array
	 * 
	 * @since 1.5.3
	 */
	 
	private function get_bcc() {
			
		$return_bcc = false;
		
		// get option return the option as string or false if not found
		$bcc = get_option( $this->get_prefix().'option_bcc_recipient' );
		
		if ( $bcc!=false ) {
			
			$bcc_array = explode(',' , $bcc);
			
		}

		// check every single email if is valid or no.
		
		if ( ( isset($bcc_array) ) && (count($bcc_array)>0) ) {
			
			foreach ( $bcc_array as $key => $value ) {
				
				if ( !is_email( $value ) ) {
					
					unset($bcc_array[$key]);
					
				}
				
			}
			
		}
		
		// last check.
		
		if ( ( isset($bcc_array) ) && (count($bcc_array)>0) ) {
			
			$return_bcc = $bcc_array;
			
		}
		
		return $return_bcc;
		
	}
	 
	 /**
	  * 
	  * Set the body email
	  * 
	  * @param string The html email body
	  * 
	  * @since 1.0
	  * 
	  */
	  private function set_body_email($email) {
	  	
		$this->_body_email = $email;
		
	  }
	  
	  /**
	   * 
	   * Get the body html email
	   * 
	   * @return string The HTML Email
	   */
	private function get_body_email() {
	   	
		return $this->_body_email;
		
	}
	
	/**
	  * 
	  * Set the submitted IP from form
	  * 
	  * @param string the IP submitted
	  * 
	  * @since 1.7
	  */
	  private function set_submitted_ip_address_from_form($ip_address) {
	  	
		$this->_submitted_ip_address_from_form = $ip_address;
		
	  }
	  
	/**
	  * 
	  * Get the submitted IP from form
	  * 
	  * @return string the IP submitted
	  * 
	  * @since 1.7
	  */
	  private function get_submitted_ip_address_from_form() {
	  	
		return $this->_submitted_ip_address_from_form;
		
	  }
	
	/**
	  * 
	  * Set the submitted privacy from form
	  * 
	  * @param string the privacy submitted
	  * 
	  * @since 1.1
	  */
	  private function set_submitted_privacy_from_form($privacy) {
	  	
		$this->_submitted_privacy_from_form = $privacy;
		
	  }
	  
	/**
	  * 
	  * Set the submitted privacy art 4 from form
	  * 
	  * @param string the privacy submitted
	  * 
	  * @since 1.6
	  */
	  private function set_submitted_privacy_art_4_from_form($privacy) {
	  	
		$this->_submitted_privacy_art_4_from_form = $privacy;
		
	  }
	  
	/**
	  * 
	  * Get the submitted privacy from form
	  * 
	  * @return string 
	  * 
	  * @since 1.6
	  */
	  private function get_submitted_privacy_art_4_from_form() {
	  	
		return $this->_submitted_privacy_art_4_from_form;
		
	  }
	  
	/**
	  * 
	  * Set the submitted privacy art 5 from form
	  * 
	  * @param string the privacy submitted
	  * 
	  * @since 1.6
	  */
	  private function set_submitted_privacy_art_5_from_form($privacy) {
	  	
		$this->_submitted_privacy_art_5_from_form = $privacy;
		
	  }
	  
	/**
	  * 
	  * Get the submitted privacy from form
	  * 
	  * @return string 
	  * 
	  * @since 1.6
	  */
	  private function get_submitted_privacy_art_5_from_form() {
	  	
		return $this->_submitted_privacy_art_5_from_form;
		
	  }
	
	/**
	  * 
	  * Set the submitted message from form
	  * 
	  * @param string the message submitted
	  * 
	  * @since 1.0
	  */
	  private function set_submitted_message_from_form($message) {
	  	
		$this->_submitted_message_from_form = $message;
		
	  }
	  
	/**
	  * 
	  * Set the submitted page from form
	  * 
	  * @param string the page submitted
	  * 
	  * @since 1.5.5
	  */
	  private function set_submitted_page_from_form($page) {
	  	
		$this->_submitted_page_from_form = $page;
		
	  }
	  
	/**
	  * 
	  * Get the submitted page from form
	  * 
	  * @param string the page submitted
	  * 
	  * @since 1.5.5
	  */
	  private function get_submitted_page_from_form() {
	  	
		return $this->_submitted_page_from_form;
		
	  }
	 
	 /**
	  * 
	  * Get the submitted message from form
	  * 
	  * @return string
	  * 
	  * @since 1.0
	  */
	  private function get_submitted_message_from_form() {
	  	
		return $this->_submitted_message_from_form;
		
	  }
	 
	 /**
	  * 
	  * Set the submitted name from form
	  * 
	  * @param string the name submitted
	  * 
	  * @since 1.0
	  */
	  private function set_submitted_name_from_form($name) {
	  	
		$this->_submitted_name_from_form = $name;
		
	  }
	 
	 /**
	  * 
	  * Get the submitted name from form
	  * 
	  * @return string
	  * 
	  * @since 1.0
	  */
	  private function get_submitted_name_from_form() {
	  	
		return $this->_submitted_name_from_form;
		
	  }
	  
	  
	  /**
	  * 
	  * Set the submitted phone from form
	  * 
	  * @param string the phone submitted
	  * 
	  * @since 1.2
	  */
	  private function set_submitted_phone_from_form($phone) {
	  	
		$this->_submitted_phone_from_form = $phone;
		
	  }
	 
	 /**
	  * 
	  * Get the submitted phone from form
	  * 
	  * @return string
	  * 
	  * @since 1.2
	  */
	  private function get_submitted_phone_from_form() {
	  	
		return $this->_submitted_phone_from_form;
		
	  }
	  
	  /**
	  * 
	  * Set the submitted email from form
	  * 
	  * @param string the email submitted
	  * 
	  * @since 1.0
	  */
	  private function set_submitted_email_from_form($email) {
	  	
		$this->_submitted_email_from_form = $email;
		
	  }
	 
	 /**
	  * 
	  * Get the submitted email from form
	  * 
	  * @return string
	  * 
	  * @since 1.0
	  */
	  private function get_submitted_email_from_form() {
	  	
		return $this->_submitted_email_from_form;
		
	  }
	  
	/**
	 * 
	 * Set the error on send
	 * 
	 * @param string
	 * 
	 * @since 1.0
	 */
	private function set_on_send_error($error) {
	   	
		$this->_on_send_error = $error;	
		
	}
	
	/**
	 * 
	 * Get the error on send
	 * 
	 * @return string
	 * 
	 * @since 1.0
	 */
	private function get_on_send_error() {
	   	
		return $this->_on_send_error;	
		
	}
	
}	