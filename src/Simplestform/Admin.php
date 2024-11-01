<?php

/**
 * Admin class.
 * 
 * Method and property for admin section.
 * 
 * @since 1.0
 * 
 * @version 1.5
 * 
 */

namespace Simplestform;

class Admin extends \Simplestform\Base {
	
	/**
     * Page title on the backend.
	 * The page title is visible when we click on menu label
	 * 
	 * @since 1.0
	 * 
     * @return string
     */
	private $_page_title = 'Form contatti - Impostazioni';
	
	/**
     * Priority.
	 * Where the menu need to display
	 * 
	 * @since 1.0
	 * 
     * @return string
     */
	private $_priority_on_menu = 90;
	
	
	/**
     * Menu label on the backend.
	 * The page title is visible when we click on menu label
	 * 
	 * @since 1.0
	 * 
     * @return string
     */
	private $_menu_label = 'Simplestform';
	
	/**
     * Menu label on the backend.
	 * When you click here, you can view the submissions
	 * 
	 * @since 1.0
	 * 
     * @return string
     */
	private $_menu_label_for_submission = 'Email ricevute';
	
	/**
     * Slug for the admin
	 * 
	 * @since 1.0
	 * 
     * @return string
     */
	private $_slug;
	
	/**
     * Settings group for form
	 * 
	 * @since 1.0
	 * 
     * @return string
     */
	private $_option_group;
	
	/**
	 * Our constructor.
	 * 
	 * Accept the base_dir of the plugin (see simplestform.php)
	 * 
	 * @since 1.0
	 * 
	 * @param $base_dir		The directory base
	 * 
	 */
	 
	 public function __construct($base_dir = null , $plugin_base_name = null) {
	 	
		parent::__construct($base_dir , $plugin_base_name);
		
		/*
		 * Create the slug
		 * 
		 */
		
		$this->_slug = strtolower ( $this->get_plugin_name() ).'-settings';
		
		/*
		 * Create the option_group
		 * 
		 */
		
		$this->_option_group = $this->get_prefix() .'_settings_group';
		
		
		/*
		 * Initialize the backend
		 */
		
		$this->create_admin_settings_menu();
		
		/*
		 * Activate the filter hooks
		 */
		$this->perform_filter_hooked_action();
		
		/*
		 * Activate the action hooks
		 */
		$this->perform_action_hooked_action();
		
		/*
		 * Register the settings.
		 * See: https://codex.wordpress.org/Creating_Options_Pages
		 */
		
		$this->register_settings();
		
		/*
		 * Register custom post.
		 * 
		 */
		$this->register_custom_post();
		
		
		/**
		 * 
		 * Add the meta boxes.
		 */
		$this->add_meta_boxes();
		
		/**
		 * Perform the saving of options
		 */
		$this->save_options_to_database();
		
	 }
	 
	 /**
	  * 
	  * Save the options in database.
	  * 
	  * @since 1.7
	  * 
	  */
	  
	private function save_options_to_database() {
		
		if ( isset ( $_POST['submit'] ) ) {
			
			delete_option ( $this->get_prefix().'option_api_last_check' );
			delete_option ( $this->get_prefix().'option_api_is_premium' );
			
			$option_array = $this->get_array_admin_key_form();
			
			foreach ( $_POST as $key => $value ) {
				
				foreach ( $option_array as $subkey => $subvalue ) {
					
					if ( $key === $subvalue ) {
						
						update_option ( $key , $value );
						
					}
					
				}
				
			}
			
		} // submit
		
	}
	 
	 /**
	  * Register Admin CSS.
	  * 
	  * @since 1.5.2
	  * 
	  */
	  
	/**
	 * 
	 * Render the nav tab menu on admin
	 * 
	 * @since 1.5.6
	 * 
	 */
	 
	public function create_admin_nav_menu() {
		
		
		$current = 'generic';
		
		if ( isset ( $_GET['tab'] ) ) {
			
			$current = $_GET['tab'];
			
		}
		
		// add all tabs
		
		$tabs = array(
		
			'generic'		=>	__( 'Generali' , $this->get_language_domain() ),
			'ga'			=>	__( 'Google Analytics' , $this->get_language_domain() ),
			'api'			=>	__( 'API' , $this->get_language_domain() )
		
		);
		
		$html = '<h2 class="nav-tab-wrapper">';
		
    	foreach( $tabs as $tab => $name ){
    		
        	$class = ( $tab == $current ) ? 'nav-tab-active' : '';
        	$html .= '<a class="nav-tab ' . $class . '" href="options-general.php?page='.$this->get_slug().'&tab=' . $tab . '">' . $name . '</a>';
			
    	}
		
    	$html .= '</h2>';
		
    	echo $html;
		
	}
	
		
	/**
     * Perform all actions
	 * 
	 * @since 1.4 
     */
	private function perform_action_hooked_action() {
		
		// add other columns to the table on edit.php
		add_action('manage_simplestform_cp_posts_custom_column' , array ( $this , 'add_content_to_columns_to_summary_custom_post'));
		// register the css
		add_action( 'admin_enqueue_scripts', array ( $this , 'load_admin_css' ) );
	
	}
	
	/**
	 * Load the css.
	 * Callback from perform_action_hooked_action
	 * 
	 * @since 1.5.2
	 */
	public function load_admin_css($hook) {
		
		// Load only on ?page=mypluginname
        if($hook != 'settings_page_simplestform-settings') {
        	return;
        }
        wp_enqueue_style( 'custom_wp_admin_css', plugins_url().'/simplest-form/assets/css/admin/simplestform.css' );
		
	}
	
	/**
	 * 
	 * Filter for add content to columns to summary custom post.
	 * 
	 * Callback from perform_action_hooked_action
	 * 
	 * @since 1.4
	 */
	public function add_content_to_columns_to_summary_custom_post( $columns ) {
			
		// Get the post object for this row so we can output relevant data
  		global $post;
  
  		// Check to see if $column matches our custom column names
  			switch ( $columns ) {
  				
				case $this->get_custom_post_name().'-name' :
      				// Retrieve post meta
      				$value = get_post_meta( $post->ID , $this->get_custom_post_name().'-name' , true );
      
      				// Echo output and then include break statement
      				if ( !empty($value) ) {
      					
						echo $value;
						
      				}
					
      			break;

    			case $this->get_custom_post_name().'-email' :
      				// Retrieve post meta
      				$value = get_post_meta( $post->ID , $this->get_custom_post_name().'-email' , true );
      
      				// Echo output and then include break statement
      				if ( !empty($value) ) {
      					
						echo $value;
						
      				}
					
      			break;
				
				case $this->get_custom_post_name().'-message' :
      				// Retrieve post meta
      				$value = get_post_meta( $post->ID , $this->get_custom_post_name().'-message' , true );
      
      				// Echo output and then include break statement
      				if ( !empty($value) ) {
      					
						echo $value;
						
      				}
					
      			break;
				
				case $this->get_custom_post_name().'-page' :
      				// Retrieve post meta
      				$value = get_post_meta( $post->ID , $this->get_custom_post_name().'-page' , true );
      
      				// Echo output and then include break statement
      				if ( !empty($value) ) {
      					
						echo $value;
						
      				}
					
      			break;
				
				case $this->get_custom_post_name().'-privacy' :
      				// Retrieve post meta
      				$value = get_post_meta( $post->ID , $this->get_custom_post_name().'-privacy' , true );
      
      				// Echo output and then include break statement
      				if ( !empty($value) ) {
      					
						if ( $value == 1) {
							
							$value = _e( 'Si' , $this->get_language_domain() );
							
						} else {
							
							$value = _e( 'No' , $this->get_language_domain() );
							
						}
						
      				}
					
      			break;
				
				case $this->get_custom_post_name().'-privacy-art-4' :
      				// Retrieve post meta
      				$value = get_post_meta( $post->ID , $this->get_custom_post_name().'-privacy-art-4' , true );
					
					// Echo output and then include break statement
      				if ( !empty($value) ) {
      					
						if ( $value == 1) {
							
							$value = _e( 'Si' , $this->get_language_domain() );
							
						} else {
							
							$value = _e( 'No' , $this->get_language_domain() );
							
						}
						
      				} else {
      					
						$value = _e( 'No' , $this->get_language_domain() );
						
      				}
					
      			break;
				
				case $this->get_custom_post_name().'-privacy-art-5' :
      				// Retrieve post meta
      				$value = get_post_meta( $post->ID , $this->get_custom_post_name().'-privacy-art-5' , true );
      
      				// Echo output and then include break statement
      				if ( !empty($value) ) {
      					
						if ( $value == 1) {
							
							$value = _e( 'Si' , $this->get_language_domain() );
							
						} else {
							
							$value = _e( 'No' , $this->get_language_domain() );
							
						} 
						
      				} else {
      					
						$value = _e( 'No' , $this->get_language_domain() );
						
      				}
					
      			break;
				
				case $this->get_custom_post_name().'-confirmed-on' :
      				// Retrieve post meta
      				$value = get_post_meta( $post->ID , $this->get_custom_post_name().'-confirmed-on' , true );
      
      				// Echo output and then include break statement
      				if ( !empty($value) ) {
      					
						echo $value;
						
      				}
					
      			break;
				
				
				case $this->get_custom_post_name().'-date' :
      				// Retrieve post meta
      				$value = get_the_date('d-m-Y H:i');
      
      				echo $value;
					
      			break;
      
  			}
		
	}
	 
	 /**
     * Perform all hooks for filters
	 * 
	 * @since 1.4 
     */
	private function perform_filter_hooked_action() {
		
		// add columns
		add_filter('manage_simplestform_cp_posts_columns' , array ( $this , 'add_columns_to_summary_custom_post'));
		
		// add sortable functions
		add_filter('manage_edit-simplestform_cp_sortable_columns' , array ( $this , 'add_sortable_to_summary_custom_post'));
		
		//add settings to link on plugin page
		add_filter('plugin_action_links_'.$this->get_base_plugin_name() , array ( $this , 'add_link_settings_to_plugin_page' ));
		
	}
	
	
	/**
	 * Add "settings link" to plugin page.
	 * 
	 * Callback from perform_filter_hooked_action
	 * 
	 * @since 1.5.3
	 */
	
	public function add_link_settings_to_plugin_page( $links ) {
			
		$settings_link = '<a href="options-general.php?page=simplestform-settings">' . __( 'Settings' ) . '</a>';
    	array_push( $links, $settings_link );
  		return $links;
		
	}
	
	/**
	 * 
	 * Add sortable functions to columns on summary custom post.
	 * 
	 * Callback from perform_filter_hooked_action
	 * 
	 * @since 1.5
	 */
	public function add_sortable_to_summary_custom_post( $columns ) {
		
		// Add our columns to $columns array
		$columns[$this->get_custom_post_name().'-name'] = $this->get_custom_post_name().'-name';
		$columns[$this->get_custom_post_name().'-email'] = $this->get_custom_post_name().'-email';
		$columns[$this->get_custom_post_name().'-date'] = $this->get_custom_post_name().'-date';
		$columns[$this->get_custom_post_name().'-page'] = $this->get_custom_post_name().'-page';

		return $columns;
		
	}
	
	/**
	 * 
	 * Filter for add columns to summary custom post.
	 * 
	 * Callback from perform_filter_hooked_action
	 * 
	 * @since 1.4
	 */
	public function add_columns_to_summary_custom_post( $columns ) {
		
		// New columns to add to table
		$new_columns = array(
			$this->get_custom_post_name().'-name' => __( 'Nome', $this->get_language_domain() ),
			$this->get_custom_post_name().'-email' => __( 'Email', $this->get_language_domain() ),
			$this->get_custom_post_name().'-message' => __( 'Messaggio', $this->get_language_domain() ),
			$this->get_custom_post_name().'-page' => __( 'Pagina', $this->get_language_domain() ),
			$this->get_custom_post_name().'-privacy' => __( 'Privacy', $this->get_language_domain() ),
			$this->get_custom_post_name().'-privacy-art-4' => __( 'Privacy ART 4', $this->get_language_domain() ),
			$this->get_custom_post_name().'-privacy-art-5' => __( 'Privacy ART 5', $this->get_language_domain() ),
			$this->get_custom_post_name().'-date' => __( 'Data', $this->get_language_domain() ),
			$this->get_custom_post_name().'-confirmed-on' => __( 'Confermato il', $this->get_language_domain() ),
			
		);
		
		// Remove CB. The del box!
		//unset( $columns['cb'] );
		
		// Remove title column
		unset( $columns['title'] );
		
		// Remove unwanted publish date column
		unset( $columns['date'] );
		  
		// Combine existing columns with new columns
		$filtered_columns = array_merge( $columns, $new_columns );
		
		// Return our filtered array of columns
		return $filtered_columns;
		
	}
	
	 
	 
	/**
	  * 
	  * Add the meta box (for admin)
	  * 
	  * @since 1.0
	  */
	 private function add_meta_boxes() {
			
	 	add_action( 'add_meta_boxes' , array ( $this , 'add_meta_privacy_box' ) );
		add_action( 'add_meta_boxes' , array ( $this , 'add_meta_message_box' ) );
		add_action( 'add_meta_boxes' , array ( $this , 'add_meta_phone_box' ) );
		add_action( 'add_meta_boxes' , array ( $this , 'add_meta_page_box' ) );
		add_action( 'add_meta_boxes' , array ( $this , 'add_meta_privacy_art_4_box' ) );
		add_action( 'add_meta_boxes' , array ( $this , 'add_meta_privacy_art_5_box' ) );
		add_action( 'add_meta_boxes' , array ( $this , 'add_meta_ip_address_box' ) );
		add_action( 'add_meta_boxes' , array ( $this , 'add_meta_confirmed_on_box' ) );
		add_action( 'add_meta_boxes' , array ( $this , 'add_meta_confirmed_ip_box' ) );
		
	 }
	 
	 /**
	  * 
	  * Callback from add_meta_boxes
	  * 
	  * @since 2.0
	  */
	 public function add_meta_confirmed_ip_box() {
			
		$id = $this->get_custom_post_name().'-confirmed-ip';
		$title = __('IP conferma' , $this->get_language_domain() );
		$callback = array ( $this  , 'display_meta_confirmed_ip_box' );
		$screen = $this->get_custom_post_name();
		$context = 'side';  // normal, side or advanced
		$priority = 'high';
			
			
		add_meta_box($id,$title,$callback,$screen,$context,$priority);
			
	}
	 
	 /**
	  * 
	  * Callback from add_meta_boxes
	  * 
	  * @since 1.5.5
	  */
	 public function add_meta_confirmed_on_box() {
			
		$id = $this->get_custom_post_name().'-confirmed-on';
		$title = __('Data conferma email' , $this->get_language_domain() );
		$callback = array ( $this  , 'display_meta_confirmed_on_box' );
		$screen = $this->get_custom_post_name();
		$context = 'side';  // normal, side or advanced
		$priority = 'high';
			
			
		add_meta_box($id,$title,$callback,$screen,$context,$priority);
			
	}
	 
	 /**
	  * 
	  * Callback from add_meta_boxes
	  * 
	  * @since 1.5.5
	  */
	 public function add_meta_ip_address_box() {
			
		$id = $this->get_custom_post_name().'-ip-address';
		$title = __('IP' , $this->get_language_domain() );
		$callback = array ( $this  , 'display_meta_ip_address_box' );
		$screen = $this->get_custom_post_name();
		$context = 'side';  // normal, side or advanced
		$priority = 'high';
			
			
		add_meta_box($id,$title,$callback,$screen,$context,$priority);
			
	}
	 
	 /**
	  * 
	  * Callback from add_meta_boxes
	  * 
	  * @since 1.5.5
	  */
	 public function add_meta_page_box() {
			
		$id = $this->get_custom_post_name().'-page';
		$title = __('Pagina' , $this->get_language_domain() );
		$callback = array ( $this  , 'display_meta_page_box' );
		$screen = $this->get_custom_post_name();
		$context = 'normal';  // normal, side or advanced
		$priority = 'high';
			
			
		add_meta_box($id,$title,$callback,$screen,$context,$priority);
			
	}

	
	 
	 /**
	  * 
	  * Callback from add_meta_boxes
	  * 
	  * @since 1.0
	  */
	 public function add_meta_message_box() {
			
		$id = $this->get_custom_post_name().'-message';
		$title = __('Messaggio' , $this->get_language_domain() );
		$callback = array ( $this  , 'display_meta_message_box' );
		$screen = $this->get_custom_post_name();
		$context = 'normal';  // normal, side or advanced
		$priority = 'high';
			
			
		add_meta_box($id,$title,$callback,$screen,$context,$priority);
			
	}
	 
	 /**
	  * 
	  * Callback from add_meta_boxes
	  * 
	  * @since 1.1
	  */
	 public function add_meta_privacy_box() {
			
		$id = $this->get_custom_post_name().'-privacy';
		$title = __('Accettazione privacy' , $this->get_language_domain() );
		$callback = array ( $this  , 'display_meta_privacy_box' );
		$screen = $this->get_custom_post_name();
		$context = 'side';  // normal, side or advanced
		$priority = 'high';
			
			
		add_meta_box($id,$title,$callback,$screen,$context,$priority);
			
	}
	 
	 /**
	  * 
	  * Callback from add_meta_boxes
	  * 
	  * @since 1.9
	  */
	 public function add_meta_privacy_art_5_box() {
			
		$id = $this->get_custom_post_name().'-privacy-art-5';
		$title = __('Accettazione privacy ART 5' , $this->get_language_domain() );
		$callback = array ( $this  , 'display_meta_privacy_art_5_box' );
		$screen = $this->get_custom_post_name();
		$context = 'side';  // normal, side or advanced
		$priority = 'high';
			
			
		add_meta_box($id,$title,$callback,$screen,$context,$priority);
			
	}
	 
	 /**
	  * 
	  * Callback from add_meta_boxes
	  * 
	  * @since 1.9
	  */
	 public function add_meta_privacy_art_4_box() {
			
		$id = $this->get_custom_post_name().'-privacy-art-4';
		$title = __('Accettazione privacy ART 4' , $this->get_language_domain() );
		$callback = array ( $this  , 'display_meta_privacy_art_4_box' );
		$screen = $this->get_custom_post_name();
		$context = 'side';  // normal, side or advanced
		$priority = 'high';
			
			
		add_meta_box($id,$title,$callback,$screen,$context,$priority);
			
	}
	 
	 /**
	  * 
	  * Callback from add_meta_boxes
	  * 
	  * @since 1.2
	  */
	 public function add_meta_phone_box() {
			
		$id = $this->get_custom_post_name().'-phone';
		$title = __('Telefono' , $this->get_language_domain() );
		$callback = array ( $this  , 'display_meta_phone_box' );
		$screen = $this->get_custom_post_name();
		$context = 'normal';  // normal, side or advanced
		$priority = 'high';
			
			
		add_meta_box($id,$title,$callback,$screen,$context,$priority);
			
	}
	 
	 /**
	  * 
	  * Callback from add_meta_confirmed_on_box
	  * 
	  * @since 1.6
	  */
	public function display_meta_confirmed_ip_box() {
				
		$post = get_post();
				
		$message  = get_post_meta( $post->ID, $this->get_custom_post_name().'-confirmed-ip', true );
				
		echo $message;
				
	}
	 
	 /**
	  * 
	  * Callback from add_meta_confirmed_on_box
	  * 
	  * @since 1.6
	  */
	public function display_meta_confirmed_on_box() {
				
		$post = get_post();
				
		$message  = get_post_meta( $post->ID, $this->get_custom_post_name().'-confirmed-on', true );
				
		echo $message;
				
	}
	 
	 /**
	  * 
	  * Callback from add_meta_ip_address_box
	  * 
	  * @since 1.6
	  */
	public function display_meta_ip_address_box() {
				
		$post = get_post();
				
		$message  = get_post_meta( $post->ID, $this->get_custom_post_name().'-ip-address', true );
				
		echo $message;
				
	}
	 
	 /**
	  * 
	  * Callback from add_meta_page_box
	  * 
	  * @since 1.5.5
	  */
	public function display_meta_page_box() {
				
		$post = get_post();
				
		$message  = get_post_meta( $post->ID, $this->get_custom_post_name().'-page', true );
				
		echo $message;
				
	}
	 
	/**
	  * 
	  * Callback from add_meta_phone_box
	  * 
	  * @since 1.2
	  */
	public function display_meta_phone_box() {
				
		$post = get_post();
				
		$message  = get_post_meta( $post->ID, $this->get_custom_post_name().'-phone', true );
				
		echo $message;
				
	}
	
	/**
	  * 
	  * Callback from add_privacy_box
	  * 
	  * @since 1.9
	  */
	public function display_meta_privacy_art_5_box() {
				
		$post = get_post();
				
		$message  = get_post_meta( $post->ID, $this->get_custom_post_name().'-privacy-art-5', true );
		
		if ( $message == 1 ) {
			
			$message = _x('Si' , $this->get_language_domain() );
			
		} else {
			
			$message = _x('No' , $this->get_language_domain() );
			
		}
				
		echo $message;
				
	}
	
	/**
	  * 
	  * Callback from add_privacy_box
	  * 
	  * @since 1.9
	  */
	public function display_meta_privacy_art_4_box() {
				
		$post = get_post();
				
		$message  = get_post_meta( $post->ID, $this->get_custom_post_name().'-privacy-art-4', true );
		
		if ( $message == 1 ) {
			
			$message = _x('Si' , $this->get_language_domain() );
			
		} else {
			
			$message = _x('No' , $this->get_language_domain() );
			
		}
				
		echo $message;
				
	}
	 
	 /**
	  * 
	  * Callback from add_privacy_box
	  * 
	  * @since 1.1
	  */
	public function display_meta_privacy_box() {
				
		$post = get_post();
				
		$message  = get_post_meta( $post->ID, $this->get_custom_post_name().'-privacy', true );
		
		if ( $message == 1 ) {
			
			$message = _x('Si' , $this->get_language_domain() );
			
		} else {
			
			$message = _x('No' , $this->get_language_domain() );
			
		}
				
		echo $message;
				
	}

	/**
	  * 
	  * Callback from add_message_box
	  * 
	  * @since 1.0
	  */
	public function display_meta_message_box() {
				
		$post = get_post();
				
		$message  = get_post_meta( $post->ID, $this->get_custom_post_name().'-message', true );
				
		echo $message;
				
	}
	 
	 /**
     * Register custom post.
	 * 
	 * @since 1.0 
     */
     private function register_custom_post() {
	 	
		// Inizializzazione della funzione
        add_action( 'init', array( $this , 'callback_create_custom_post_type' ) );
		
	 }
	 
	 /**
     * Callback from register_register_custom_post()
	 * 
	 * @since 1.0 
     */
     
     public function callback_create_custom_post_type() {
	 	
		// Labels of Custom Post Type
		
        $labels = array(
        
            // Nome plurale del post type
            // H1 + meta title
            'name' => _x( $this->_menu_label_for_submission , 'Post Type General Name', $this->get_language_domain() ),
        
		    // Nome singolare del post type
            'singular_name' => _x( 'Form', 'Post Type Singular Name', $this->get_language_domain() ),
        
		    // Testo per pulsante Aggiungi
            'add_new' => __( 'Aggiungi', $this->get_language_domain() ),
            
            // Testo per pulsante Tutti gli articoli
            'all_items' => __( 'Tutte le richieste', $this->get_language_domain() ),
            
            // Testo per pulsante Aggiungi nuovo articolo
            //'add_new_item' => __( 'Aggiungi Nuovo Contatto', WPTRESF__PLUGIN_DOMAIN ),
            
            // Testo per pulsante Modifica
            'edit_item' => __( 'Modifica', $this->get_language_domain() ),
            
            // Testo per pulsante Nuovo
            'new_item' => __( 'Nuovo', $this->get_language_domain() ),
            
            // Testo per pulsante Visualizza
            'view_item' => __( 'Dettaglio', $this->get_language_domain() ),
            
            // Testo per pulsante Cerca articoli
            'search_items' => __( 'Cerca', $this->get_language_domain() ),
            
            // Testo per nessun articolo trovato
            'not_found' => __( 'Nessun risultato', $this->get_language_domain() ),
            
            // Testo per nessun articolo trovato nel cestino
            'not_found_in_trash' => __( 'Nessun risultato nel cestino', $this->get_language_domain() ),
            
            // Testo per articolo genitore
            'parent_item_colon' => __( 'Genitore:', $this->get_language_domain() ),
            
            // Testo per Menù
            'menu_name' => __( $this->_menu_label_for_submission , $this->get_language_domain() )
        );
		
        $args = array(
            'labels' => $labels,
            
            // Descrizione
            'description' => _x( $this->_menu_label_for_submission , $this->get_language_domain() ),
            
            // Rende visibile o meno da front-end il post type
            'public' => false, // here
            
            // Esclude o meno il post type dai risultati di ricerca
            'exclude_from_search' => true, // here
            
            // Rende richiamabile o meno da front-end il post type tramite una query
            'publicly_queryable' => false, // here
            
            // Rende disponibile l'interfaccia grafica del post type da back-end
            'show_ui' => true,
            
            // Rende disponibile o meno il post type per il menù di navigazione
            'show_in_nav_menus' => false,
            
            // Definisce dove sarà disponibile l'interfaccia grafica del post type nel menù di amministrazione
            
            // if is false, doesn't show.
            // @see https://shellcreeper.com/how-to-add-wordpress-cpt-admin-menu-as-sub-menu/
            
            'show_in_menu' => true,
            
            // Rende disponibile o meno il post type per l'admin bar di wordpress
            'show_in_admin_bar' => true,
            
            // La posizione nel menù
            'menu_position' => 30,
            
            // L'icona del post type nel menù
            'menu_icon' => 'dashicons-admin-comments',
            
            // I permessi che servono per editare il post type
            'capability_type' => 'post',
            
			// ADD / EDIT SECTION
			
			'capabilities' => array(
    			'create_posts' => false, // Removes support for the "Add New" function ( use 'do_not_allow' instead of false for multisite set ups )
  			),
  			'map_meta_cap' => true, // Set to `false`, if users are not allowed to edit/delete existing posts
            
            // Definisce se il post type è gerarchico o meno
            'hierarchical' => true,
            
            // I meta box che il post type supporta nell'interfaccia di editing
            //'supports' => array( 'title', 'editor', 'page-attributes' ),
            'supports'	=>	array('title'),
            
            // Le tassonomie supportate dal post type
            'taxonomies' => array( 'contact_form' ),
            
            // Definisce se il post type ha o meno un archivio
            'has_archive' => false,
            
            // Imposta lo slug del post type
            'rewrite' => array( 'slug' => $this->get_prefix().'inbox', 'with_front' => false ),
        );
                // Registra il Custom Post Type
        register_post_type( $this->get_custom_post_name() , $args );
		
	 }
	 
	 /**
     * Register the settings.
	 * See: https://codex.wordpress.org/Creating_Options_Pages
	 * 
	 * @since 1.0 
     */
     
     private function register_settings() {
     
	 	if ( is_admin() ) {
	 	
			add_action( 'admin_init' , array ( $this , 'callback_register_settings'));
			
		}
	 	
	 }
	 
	 /**
	  * Callback from register_settings
	  * 
	  * @since 1.0
	  * 
	  */
	  
	public function callback_register_settings() {
	  	
		// get the array with form names
		foreach ( $this->get_array_admin_key_form() as $key => $value ) {
			
			register_setting( $this->get_option_group(), $value );
			
		}
		
		
	}
	 
	 /**
     * Create the page for the admin settings in backend
	 * Add also a filter to order the posts
	 * 
	 * @since 1.0
	 * 
     */
	 
	 private function create_admin_settings_menu() {
	 	
		/** Step 2 (from text above). HOOK, @FUNCTION */
		add_action( 'admin_menu' , array ( $this , 'add_admin_settings_menu' ) );
		
		// we did add sortable on v. 1.5, so we don't need anymore
		add_filter( 'pre_get_posts' , array ( $this , 'set_custom_post_order' ) );
		
	 }
	 
	 /**
	  * Add the admin menu.
	  * Called from create_admin_settings_menu
	  * 
	  * @since 1.0
	  * 
	  */
	 
	 public function add_admin_settings_menu() {
	 	
		$page_title = __( $this->_page_title , $this->get_language_domain() );
		$menu_title = __( $this->_menu_label , $this->get_language_domain() ); // The label visible in the menu tree
		$capable_option = 'manage_options'; // permission needed
		$slug = $this->_slug;
		$function_callback = array ( $this , 'callback_load_admin_settings_page' );
		$icon = 'dashicons-welcome-write-blog';
		$priority = $this->_priority_on_menu;
		
		$parent_slug = 'options-general.php';
				
		//add_menu_page( $page_title , $menu_title , $capable_option , $slug , $function_callback , $icon , $priority  );
		
		//add_submenu_page( string $parent_slug, string $page_title, string $menu_title, string $capability, string $menu_slug, callable $function = '' );
		
		add_submenu_page(
							$parent_slug,
							$page_title,
							$menu_title,
							$capable_option,
							$slug,
							$function_callback
						);
		
	 }
	 
	 /**
	  * Callback from add_admin_menu.
	  * Called from create_admin_settings_menu
	  * 
	  * @since 1.0
	  * 
	  */
	  
	public function callback_load_admin_settings_page() {
	  	
		if ( !current_user_can( 'manage_options' ) )  {
				
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		
		}
		
		include_once ( $this->get_base_dir().'/views/admin/settings.php' );
		
	}
	
	
	/**
	  * Order the custom post. 
	  * Called from create_admin_settings_menu
	  * 
	  * @since 1.0
	  * 
	  */
	
	
	public function set_custom_post_order ($wp_query) {
			
		if (is_admin()) {
				
			// Get the post type from the query  
		    $post_type = $wp_query->query['post_type'];  
		  
		    if ( $post_type == $this->get_custom_post_name() ) {  
		  
		      	// 'orderby' value can be any column name  
		      	$wp_query->set('orderby', 'post_date_gmt');  
		  
		      	// 'order' value can be ASC or DESC  
		      	$wp_query->set('order', 'DESC');  
		    
			}
			
		}

	}

	/**
     * Return slug
	 * 
	 * @return string
	 * 
	 * @since 1.0
	 * 
     */
	protected function get_slug() {
		
		return $this->_slug;
		
	}
	
	/**
     * Return option group
	 * 
	 * @return string
	 * 
	 * @since 1.0
	 * 
     */
	protected function get_option_group() {
		
		return $this->_option_group;
		
	}
	
	
}	