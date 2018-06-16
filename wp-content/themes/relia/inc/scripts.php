<?php
function gups_enqueue()
	{
	global $current_user;
	$current_user = wp_get_current_user();
	if (!is_admin()) {
		wp_register_style( 'gups-styles', get_stylesheet_directory_uri() . '/library/css/app.css', array(), '', 'all' );
		wp_register_script( 'app', get_template_directory_uri() . '/library/js/app.js', array('jquery'), '102', true );
		wp_enqueue_style( 'gups-styles' );
		}
		wp_enqueue_script( 'app' );
		wp_localize_script('app', 'theUser', array (
			'userid' => $current_user->id,
		));
	}
	add_action('wp_enqueue_scripts', 'gups_enqueue');
?>
