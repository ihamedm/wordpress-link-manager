<?php


class Wplman_Configurations_Page extends Wplman_Field_Generator {
	/**
	 * Create Group settings for each pages and tabs that have option fields
	 *
	 */
	public function __construct() {
		add_action('admin_init', array($this, 'create_section_fields') );
	}

	/**
	 * Create fields for each section
	 */
	public function create_section_fields(){
		/**
		 * First - create section
		 */
		add_settings_section(
			'main_section_id',
			__( 'null', WPLMAN_TEXTDOMAIN),
			array( $this , 'header_display_markup' ),
			'wplman-configurations'
		);



		/**
		 * Second - Create fields for section
		 */
		add_settings_field(
			'about_text',
			__( 'About text', WPLMAN_TEXTDOMAIN),
			array($this , 'about_text_callback'),
			'wplman-configurations',
			'main_section_id',
			array(
				'type' => 'editor',
				'name' => WPLMAN_PREFIX.'about_text',
				'description' => __( 'Text that show in app about page.', WPLMAN_TEXTDOMAIN)
			)
		);
		register_setting(WPLMAN_PREFIX.'main_configs', WPLMAN_PREFIX.'about_text', array($this, 'about_validate_callback'));





	}


	public function header_display_markup() {
		echo '<p>'.__('All plugin Configurations placed in below form', WPLMAN_TEXTDOMAIN).'</p>';
	}



	public function about_validate_callback($input){
		$old_option =  get_option(WPLMAN_PREFIX.'about_text');
		if(empty($input)){
			add_settings_error('error','about_null_error', __('About can not be blank!',WPLMAN_TEXTDOMAIN));
			return $old_option;
		}
		return $input;
	}
	public function about_text_callback($args){
		$html = self::create_field_markup($args);
		echo $html;
	}


}

new Wplman_Configurations_Page();