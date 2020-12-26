<?php
/*
  Plugin Name: Chapter 11 - Hello World V1
  Plugin URI: 
  Description: Companion to recipe 'Adapting default user settings for translation'
  Author: ylefebvre
  Version: 1.0
  Author URI: http://ylefebvre.ca/
 */

register_activation_hook( __FILE__, 'ch11hw_set_default_options_array' );

function ch11hw_set_default_options_array() {
	if ( false === get_option( 'ch11hw_options' ) ) {
		$new_options = array();
		$new_options['default_text'] = __( 'Hello World', 'ch11hw_hello_world' );
		add_option( 'ch11hw_options', $new_options );
	}
}