<div class="main-simplestform-container">

	<?php
	
		if ( count ( $this->get_frontend_error() ) > 0 ) {
		
	?>
	
	<div class="alert alert-danger">
		
		<?php
		
			foreach ( $this->get_frontend_error() as $error ) {
				
			?>
				
				<p><?php echo $error; ?></p>	
				
			<?php
				
			}
		
		?>
		
	</div>
	
	<?php
		}
	?>
	
	
	<?php
	
		if ( $this->get_frontend_success() != '' ) {
			
			?>
			
				<div class="alert alert-success">
					
					<p><?php _e( $this->get_frontend_success() ) ?></p>
					
				</div>
			
			<?php
			
		}
	
	?>
	
	
	
	
	<?php
		// get the array for form keys
		$form_array = $this->get_array_frontend_key_form();
			
	?>
	
	
	<form method="post" action="<?php esc_url( $_SERVER['REQUEST_URI'] ) ?>">
		
		<!-- nonce -->
		<?php wp_nonce_field( $this->get_prefix().'nonce_field' ); ?>
		<!-- /nonce -->
		
		<!-- page -->
		
		<?php
		
			$value = $form_array['page'];
		?>
		
		<input type="hidden" name="<?php echo $value; ?>" value="<?php echo get_permalink(); ?>" />
		
		<!-- /page -->
		
		
		<!--  name -->
		
		<?php
		
			$value = $form_array['name'];
		?>
		
		<div class="form-group">
			<label for="<?php echo $value; ?>"><?php _e('Nome' , $this->get_language_domain() ) ?></label>
		    <input type="text" class="form-control" name="<?php echo $value; ?>" />
		</div>
		
		<!--  phone -->
		
		<?php
		
			$value = $form_array['phone'];
		?>
		
		<div class="form-group">
			<label for="<?php echo $value; ?>"><?php _e('Telefono' , $this->get_language_domain() ) ?></label>
		    <input type="text" class="form-control" name="<?php echo $value; ?>" />
		</div>
		
		<!-- email -->
		
		<?php
		
			$value = $form_array['email'];
		?>
		
		<div class="form-group">
			<label for="<?php echo $value; ?>"><?php _e('Email' , $this->get_language_domain() ) ?></label>
		    <input type="text" class="form-control" name="<?php echo $value; ?>" />
		</div>
		
		<!-- message -->
		
		<?php
		
			$value = $form_array['message'];
		?>
		
		<div class="form-group">
			<label for="<?php echo $value; ?>"><?php _e('Richiesta' , $this->get_language_domain() ) ?></label>
		    <textarea class="form-control" name="<?php echo $value; ?>"></textarea>
		</div>
		
		<!-- /message -->
		
		
		<!-- privacy -->
		
		<?php
		
			$value = $form_array['privacy'];
			$url = esc_attr( get_option ( $this->get_prefix().'option_url_privacy' ) );
		?>
		
		<div class="checkbox" style="margin-top:10px">
			<label for="privacy">
				<input type="checkbox" name="<?php echo $value; ?>" value="1" />
				<a href="<?php echo $url; ?>"><?php _e('Autorizzo il trattamento dei miei dati, come riportato alla lett. A punti 1,2 e 3 della policy privacy' , $this->get_language_domain() ) ?></a>
			</label>
		</div>
		
		<!-- /privacy -->
		
		<?php
		
		if ( $this->get_is_premium() ) {
			
			?>
		
			<!-- privacy ART. 4 -->
			
			<?php
			
				$value = $form_array['privacy_art_4'];
				$url = esc_attr( get_option ( $this->get_prefix().'option_url_privacy' ) );
			?>
			
			<div class="checkbox" style="margin-top:10px">
				<label for="privacy_art_4">
					<input type="checkbox" name="<?php echo $value; ?>" value="1" />
					<a href="<?php echo $url; ?>"><?php _e('Ho letto l\'informativa privacy ed accetto le finalità previste (Lett. A,  punto 4)' , $this->get_language_domain() ) ?></a>
				</label>
			</div>
			
			<!-- /privacy -->
			
			<!-- privacy ART. 4 -->
			
			<?php
			
				$value = $form_array['privacy_art_5'];
				$url = esc_attr( get_option ( $this->get_prefix().'option_url_privacy' ) );
			?>
			
			<div class="checkbox" style="margin-top:10px">
				<label for="privacy_art_5">
					<input type="checkbox" name="<?php echo $value; ?>" value="1" />
					<a href="<?php echo $url; ?>"><?php _e('Ho letto l\'informativa privacy ed accetto le finalità previste (Lett. A,  punto 5)' , $this->get_language_domain() ) ?></a>
				</label>
			</div>
			
			<!-- /privacy -->
			
		<?php
		
			}
		
		?>
		
		<!-- trap -->
		
		<div class="form-group invisible-field">
			<label for="trap"><?php _e('Non inserire nulla in questo campo' , $this->get_language_domain() ) ?></label>
		    <input type="text" class="form-control" name="trap" />
		</div>
		
		<!-- /trap -->
		
		<button type="submit" class="btn btn-default btn-custom" name="<?php echo $this->get_prefix() ?>submit"><?php _e('Invia' , $this->get_language_domain()) ?></button>
		
	</form>
	
</div><!-- /main simplestform container -->