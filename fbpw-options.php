<?php
defined('ABSPATH') or die("No script kiddies please!");

add_action( 'admin_menu', 'fbpw_options' );

function fbpw_options() {
	add_options_page( 'Facebook Pinwall Options', 'FB Pinwall', 'manage_options', 'fbpw', 'plugin_options' );
}

function plugin_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	echo '<div class="wrap">';
	?>

	<form method="post" action="options.php"> 
		<?php
		settings_fields( 'myoption-group' );
		do_settings_sections( 'myoption-group' );
		?>

		<?php submit_button('Save Settings'); ?>
	</form>

	<?php
	echo '</div>';
}

/*
add_action( 'admin_init', 'fbpw_options_init');

function fbpw_options_init(){

}
*/