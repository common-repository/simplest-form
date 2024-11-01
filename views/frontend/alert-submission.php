<?php

	$class = 'alert-danger';
	
	if ( $alert_type == 'success' ) {
		
		$class = 'alert-success';
		
	}

?>


<div class="alert <?php echo $class; ?>">
	<p class="text-center"><?php echo $this->get_answer_from_check_code() ?></p>
</div>