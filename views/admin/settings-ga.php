<!-- GOOGLE ANALYTICS -->
<div class="alert alert-info">
	
	<h1><?php _e('Come impostare un obiettivo su Google Analytics' , $this->get_language_domain() ); ?></h1>
	<h2><?php _e('Vuoi monitorare quante conversioni e quindi contatti ricevi dal tuo sito internet? Imposta un obiettivo su Google Analytics' , $this->get_language_domain() ); ?></h2>
	<ol>
		<li><?php _e('Amministratore > Vista > Obiettivi' , $this->get_language_domain() ); ?></li>
		<li><?php _e('Premere "Nuovo obiettivo"' , $this->get_language_domain() ); ?></li>
		<li><?php _e('IMPOSTAZIONE OBIETTIVO > Personalizzato > Continua' , $this->get_language_domain() ); ?></li>
		<li><?php _e('IMPOSTAZIONE OBIETTIVO > Nome > "Contatti"' , $this->get_language_domain() ); ?></li>
		<li><?php _e('IMPOSTAZIONE OBIETTIVO > Tipo > Evento > Continua' , $this->get_language_domain() ); ?></li>
		<li><?php _e('IMPOSTAZIONE OBIETTIVO > Dettagli > Condizione' , $this->get_language_domain() ); ?>
			<ul>
				<li><?php _e('CATEGORIA > Deve essere lo stesso valore che usi qui' , $this->get_language_domain() ); ?></li>
				<li><?php _e('AZIONE > Deve essere lo stesso valore che usi qui' , $this->get_language_domain() ); ?></li>
				<li><?php _e('ETICHETTA > Deve essere lo stesso valore che usi qui' , $this->get_language_domain() ); ?></li>
			</ul>
		</li>
		<li><?php _e('Al termine premere "Salva"' , $this->get_language_domain() ); ?></li>
	</ol>
	
</div>
<tr>
	<th scope="row"><?php _e('UA Google Analytics (ID Monitoraggio)' , $this->get_language_domain()) ?></th>
	<td>
		<?php
		
			$value = $form_array['ga_ua_code'];
			$saved_option = esc_attr( get_option ( $value ) );
			$default = '';
			$explain = _x( 'Il codice del tuo Google Analytics (ID monitoraggio). Simile a UA-12345678-90' , $this->get_language_domain() );
						
			if ( $saved_option == '' ) {
							
				$saved_option = $default;
							
			}
						
		    		
		?>
		    		
		<input type="text" name="<?php echo $value; ?>" class="regular-text" value="<?php echo $saved_option; ?>" />
		<p class="description"><?php printf( esc_html( $explain ), $default , $default ); ?></p>
		    		
	</td>
</tr>
<tr>
	
	<th scope="row"><?php _e('Dettagli obiettivo > Categoria' , $this->get_language_domain()) ?></th>
	<td>
		<?php
		
			$value = $form_array['ga_category'];
			$saved_option = esc_attr( get_option ( $value ) );
			$default = '';
			$explain = _x( 'Potresti usare "Form"' , $this->get_language_domain() );
						
			if ( $saved_option == '' ) {
							
				$saved_option = $default;
							
			}
						
		    		
		?>
		    		
		<input type="text" name="<?php echo $value; ?>" class="regular-text" value="<?php echo $saved_option; ?>" />
		<p class="description"><?php printf( esc_html( $explain ), $default , $default ); ?></p>
		    		
	</td>
	
</tr>
<tr>
	
	<th scope="row"><?php _e('Dettagli obiettivo > Azione' , $this->get_language_domain()) ?></th>
	<td>
		<?php
		
			$value = $form_array['ga_action'];
			$saved_option = esc_attr( get_option ( $value ) );
			$default = '';
			$explain = _x( 'Potresti usare "Submit"' , $this->get_language_domain() );
						
			if ( $saved_option == '' ) {
							
				$saved_option = $default;
							
			}
						
		    		
		?>
		    		
		<input type="text" name="<?php echo $value; ?>" class="regular-text" value="<?php echo $saved_option; ?>" />
		<p class="description"><?php printf( esc_html( $explain ), $default , $default ); ?></p>
		    		
	</td>
	
</tr>
<tr>
	
	<th scope="row"><?php _e('Dettagli obiettivo > Etichetta' , $this->get_language_domain()) ?></th>
	<td>
		<?php
		
			$value = $form_array['ga_label'];
			$saved_option = esc_attr( get_option ( $value ) );
			$default = '';
			$explain = _x( 'Potresti usare "Contatti"' , $this->get_language_domain() );
						
			if ( $saved_option == '' ) {
							
				$saved_option = $default;
							
			}
						
		    		
		?>
		    		
		<input type="text" name="<?php echo $value; ?>" class="regular-text" value="<?php echo $saved_option; ?>" />
		<p class="description"><?php printf( esc_html( $explain ), $default , $default ); ?></p>
		    		
	</td>
</tr>