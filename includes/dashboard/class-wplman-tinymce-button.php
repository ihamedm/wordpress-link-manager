<?php
/**
 * Class Wplman_Tinymce_Button
 * - Create plugin for Tiny Mce (default wordpress text editor)
 * - add a button that create shortcode to show a shortlink in post/page content
 */

class Wplman_Tinymce_Button{

	public function __construct() {
		add_filter( "mce_external_plugins", array( $this, 'enqueue_plugin_scripts' ) );
		add_filter( "mce_buttons", array( $this, 'register_buttons_editor' ) );
	}


	/**
	 *
	 * Tinymce plugin creting with javascript
	 *
	 * @param $plugin_array
	 *
	 * @return mixed
	 */
	public function enqueue_plugin_scripts($plugin_array){
		$plugin_array["wplman_button_plugin"] =   WPLMAN_URL . 'assets/js/wplman-tinymce-button.js';
		return $plugin_array;
	}


	/**
	 *
	 * Register ab button for our plugin
	 * @param $buttons
	 *
	 * @return mixed
	 */
	public function register_buttons_editor($buttons){
		array_push($buttons, "wplman");
		return $buttons;
	}


}
new Wplman_Tinymce_Button();
