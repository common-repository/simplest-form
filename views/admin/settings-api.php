<!-- API SECTION -->
	<tr>
		<th scope="row"><?php _e('API key' , $this->get_language_domain()) ?></th>
		<td>
			<?php
		    	$value = $form_array['sf_api_key'];
				$saved_option = esc_attr( get_option ( $value ) );
				$default = '';
				$explain = __( 'Chiave API' , $this->get_language_domain() );
						
				if ( $saved_option == '' ) {
							
					$saved_option = $default;
							
				}
						
		    		
		    ?>
		    		
		    <input type="text" name="<?php echo $value; ?>" class="regular-text" value="<?php echo $saved_option; ?>" />
		    <p class="description"><?php printf( esc_html( $explain ), $default , $default ); ?></p>
		    		
		</td>
	</tr>
<!-- /API SECTION -->