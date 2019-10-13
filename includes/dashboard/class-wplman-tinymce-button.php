<?php

class Wplman_Tinymce_Button{

	public function __construct() {
		add_filter( "mce_external_plugins", array( $this, 'enqueue_plugin_scripts' ) );
		add_filter( "mce_buttons", array( $this, 'register_buttons_editor' ) );
	}


	public function enqueue_plugin_scripts($plugin_array)
	{
		//enqueue TinyMCE plugin script with its ID.
		$plugin_array["wplman_button_plugin"] =   WPLMAN_URL . 'assets/js/wplman-tinymce-button.js';
		return $plugin_array;
	}

	public function register_buttons_editor($buttons)
	{
		//register buttons with their id.
		array_push($buttons, "wplman");
		return $buttons;
	}


}
new Wplman_Tinymce_Button();
