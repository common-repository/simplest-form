<!-- FROM NAME SECTION -->
			<tr>
		    	<th scope="row"><?php _e('Nome mittente' , $this->get_language_domain()) ?></th>
		    	<td>
		    		
		    		<?php
		    		
		    			$value = $form_array['from_name'];
						$saved_option = esc_attr( get_option ( $value ) );
						$default = get_bloginfo(); // get the blog name as default
						$explain = _x( 'Il nome che viene visualizzato nelle caselle email. Se vuoto, sarà utilizzato il nome del blog ("%s"). Ad esempio: %s <info@johndoe.com>' , $this->get_language_domain() );
						
						if ( $saved_option == '' ) {
							
							$saved_option = $default;
							
						}
						
		    		
		    		?>
		    		
		    		<input type="text" name="<?php echo $value; ?>" class="regular-text" value="<?php echo $saved_option; ?>" />
		    		<p class="description"><?php printf( esc_html( $explain ), $default , $default ); ?></p>
		    		
		    	</td>
			</tr>
			<!-- /NAME SECTION -->
			<!-- FROM EMAIL SECTION -->
			
			<tr>
		    	<th scope="row"><?php _e('Email mittente' , $this->get_language_domain()) ?></th>
		    	<td>
		    		
		    		<?php
		    		
		    			$value = $form_array['from_email'];
						$saved_option = esc_attr( get_option ( $value ) );
						$default = get_option( 'admin_email' ); // get the admin email as default
						$explain = _x( 'L\'email mittente. Se vuota, sarà utilizzata la mail amministrativa "%s". <strong>Da questa email</strong> parte la copia del modulo che la persona ha compilato (ed alla quale potrà anche rispondere). <strong>Assicurati di usarne una che leggi</strong>.' , $this->get_language_domain() );
						
						if ( $saved_option == '' ) {
							
							$saved_option = $default;
							
						}
						
		    		
		    		?>
		    		
		    		<input type="text" name="<?php echo $value; ?>" class="regular-text" value="<?php echo $saved_option; ?>" />
		    		<p class="description"><?php printf( $explain , $default ); ?></p>
		    		
		    	</td>
			</tr>
			
			<!-- /EMAIL SECTION -->
			
			<!-- TO EMAIL SECTION -->
			
			<tr>
		    	<th scope="row"><?php _e('Destinatario' , $this->get_language_domain()) ?></th>
		    	<td>
		    		
		    		<?php
		    		
		    			$value = $form_array['to_email'];
						$saved_option = esc_attr( get_option ( $value ) );
						$default = get_option( 'admin_email' ); // get the admin email as default
						$explain = _x( 'A chi inviare i form di contatto? Se vuota, sarà utilizzata la mail amministrativa "%s".' , $this->get_language_domain() );
						
						if ( $saved_option == '' ) {
							
							$saved_option = $default;
							
						}
						
		    		
		    		?>
		    		
		    		<input type="text" name="<?php echo $value; ?>" class="regular-text" value="<?php echo $saved_option; ?>" />
		    		<p class="description"><?php printf( $explain , $default ); ?></p>
		    		
		    	</td>
			</tr>
			
			<!-- /EMAIL SECTION -->
			
			<!-- SUBJECT FOR ADMIN/MAIN RECIPIENT SECTION -->
			
			<tr>
		    	<th scope="row"><?php _e('Oggetto (per l\'amministratore del sito / principali destinatari)' , $this->get_language_domain()) ?></th>
		    	<td>
		    		
		    		<?php
		    		
		    			$blog_home_page_url = get_site_url();
		    		
		    			$value = $form_array['email_subject_for_admin'];
						$saved_option = esc_attr( get_option ( $value ) );
						
						$default_subject = _x ( 'Richiesta di informazioni dal sito %s' , $this->get_language_domain() );
						
						$explain = _x( 'L\'oggetto della email che viene inviata ai destinatari principali ed ad eventuali destinatari in copia nascosta.' , $this->get_language_domain() );
						
						if ( $saved_option == '' ) {
							
							$saved_option = sprintf( $default_subject , $blog_home_page_url );
							
						}
						
		    		
		    		?>
		    		
		    		<input type="text" name="<?php echo $value; ?>" class="regular-text" value="<?php echo $saved_option; ?>" />
		    		<p class="description"><?php echo $explain ?></p>
		    		
		    	</td>
			</tr>
			
			<!-- /SUBJECT FOR ADMIN/MAIN RECIPIENT SECTION -->
			
			<!-- BCC EMAIL SECTION -->
			
			<tr>
		    	<th scope="row"><?php _e('Destinatari CCN' , $this->get_language_domain()) ?></th>
		    	<td>
		    		
		    		<?php
		    		
		    			$value = $form_array['bcc_recipient'];
						$saved_option = esc_attr( get_option ( $value ) );
						$explain = _x( 'Destinatari in CCN separati con "," (virgola).' , $this->get_language_domain() );
						
		    		
		    		?>
		    		
		    		<!--<input type="text" name="<?php echo $value; ?>" class="regular-text" value="<?php echo $saved_option; ?>" />-->
		    		<textarea class="regular-text" rows="4" name="<?php echo $value; ?>"><?php echo $saved_option; ?></textarea>
		    		<p class="description"><?php echo $explain ?></p>
		    		
		    	</td>
			</tr>
			
			<!-- /BCC EMAIL SECTION SECTION -->
			
			<!-- URL PRIVACY -->
			
			<tr>
		    	<th scope="row"><?php _e('Link alla pagina privacy' , $this->get_language_domain()) ?></th>
		    	<td>
		    		
		    		<?php
		    		
		    			$value = $form_array['url_privacy'];
						$saved_option = esc_attr( get_option ( $value ) );
						
						$explain = _x( 'Link alla pagina contenente la privacy.' , $this->get_language_domain() );
						
						if ( $saved_option == '' ) {
							
							$saved_option = '/privacy';
							
						}
						
		    		
		    		?>
		    		
		    		<input type="text" name="<?php echo $value; ?>" class="regular-text" value="<?php echo $saved_option; ?>" />
		    		<p class="description"><?php echo $explain ?></p>
		    		
		    	</td>
			</tr>
			
			<!-- /URL PRIVACY -->