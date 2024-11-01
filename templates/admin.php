<div class="wrap wordsteem-admin-settings">
	<h1>WordSteem</h1>
	<?php settings_errors(); ?>

	<form method="post" action="options.php">
		<?php
			settings_fields('wordsteem_options_group');
			do_settings_sections('wordsteem');
			submit_button();
		?>
	</form>
</div>