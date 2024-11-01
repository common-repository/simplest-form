<div class="wrap">

	<h1><?php echo $this->get_plugin_name() ?></h1>
	<h2><?php _e('Impostazioni' , $this->get_language_domain() ) ?></h2>
	
	<p><?php _e('Usa il seguente shortcode' , $this->get_language_domain()) ?></p>
	<div class="admin-shortcode-container">
		<div class="alert alert-info">
			<p>[<?php echo $this->get_shortcode_tag();?>]</p>
		</div>
	</div>
	
	<?php

		/*
		 * It's better send email via authenticated SMTP.
		 * Use the WP MAIL SMTP plugin by Callum Macdonald.
		 * 
		 */
	
		if ( !is_plugin_active('wp-mail-smtp/wp_mail_smtp.php') ) {
			
			$notice = _x( 'Invia le email in modalità sicura. Installa il plugin <strong>%s</strong>.' , $this->get_language_domain() );
			
			?>
			
				<div class="notice notice-warning">
					<p><strong><?php printf( $notice , 'WP Mail SMTP by WP Forms' ); ?></strong></p>
				</div>
			
			<?php
			
		}

	?>
	
	<!-- display error or success -->
	<!-- /not necessary under general settings
	<?php settings_errors(); ?>
	-->
	
	
	
	<?php
	
		$tab = 'generic';
			
		if ( isset ( $_GET['tab'] ) ) {
				
			$tab = $_GET['tab'];
				
		}
		
		$url = 'options-general.php?page='.$this->get_slug().'&tab=' . $tab.'&settings-updated=true';
		
		
		if( isset($_GET['settings-updated']) ) {
	
	?>
	<div id="message" class="updated">
		<p><strong><?php _e('Settings saved.') ?></strong></p>
	</div>
	
	<?php
		}
	?>
	
	<form method="post" action="<?php echo $url; ?>">
		<?php settings_fields( $this->get_option_group() ); ?>
		<?php do_settings_sections( $this->get_option_group() ); ?>
		
		<?php
		
			// get the array for form keys
			$form_array = $this->get_array_admin_key_form();
		
		?>
		
		<table class="form-table">
			
			<?php
			
				$this->create_admin_nav_menu();
			
				$base = $this->get_base_dir();
				
				$tab = ( ! empty( $_GET['tab'] ) ) ? esc_attr( $_GET['tab'] ) : 'generic';
				
				switch ($tab) {
				
					case 'generic':
						
						include( $base.'views/admin/settings-generic.php' );
						
					break;
					
					case 'ga':
						
						include( $base.'views/admin/settings-ga.php' );
						
					break;
					
					case 'api':
						
						include( $base.'views/admin/settings-api.php' );
						
					break;
					
				}
			
			?>
			
		</table>
		
		<?php submit_button(); ?>
		
	</form>

</div>