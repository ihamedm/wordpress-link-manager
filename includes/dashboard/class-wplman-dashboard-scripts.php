<?php


class Wplman_Dashboard_Scripts{


	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this , 'enqueue_dashboard_scripts' ) );
	}


	public static function enqueue_dashboard_scripts($hook) {

		if(!is_admin()){
			return;
		}
		if(
			(isset($_GET['page']) && (in_array($_GET['page'] , array('wplman-dashboard', 'minapp-about')))) ||
			(isset($_GET['post_type']) && (in_array($_GET['post_type'] , array('shortlink'))))
		) {

			/**
			 * enqueue style files
			 */
			wp_enqueue_style( 'wplman',     WPLMAN_URL . 'assets/css/wplman.css',             '',WPLMAN_VERSION, 'all' );
			wp_enqueue_style( 'confirm',    WPLMAN_URL . 'assets/css/jquery-confirm.min.css', '',WPLMAN_VERSION, 'all' );
			wp_enqueue_style( 'select2',    WPLMAN_URL . 'assets/css/select2.min.css',        '',WPLMAN_VERSION, 'all' );
			wp_enqueue_style( 'fontawesome',WPLMAN_URL . 'assets/css/fontawesome-all.min.css','',WPLMAN_VERSION, 'all' );


			/**
			 * add style data (rtl css file)
			 */
			wp_style_add_data('wplman-rtl', 'rtl', 'replace');


			/**
			 * enqueue script files
			 */
			wp_enqueue_script( 'wplman',    WPLMAN_URL . 'assets/js/wplman.js',                     array( 'jquery' ), WPLMAN_VERSION, true );
			wp_enqueue_script( 'confirm',   WPLMAN_URL . 'assets/js/jquery-confirm.min.js',         array( 'jquery' ), WPLMAN_VERSION, true );
			wp_enqueue_script( 'select2',   WPLMAN_URL . 'assets/js/select2.full.min.js',           array( 'jquery' ), WPLMAN_VERSION, true );
			wp_enqueue_script( 'select2-fa',WPLMAN_URL . 'assets/js/select2-fa.js',                 array( 'select2'), WPLMAN_VERSION, true );
			wp_enqueue_script( 'mediamodal',WPLMAN_URL . 'assets/js/init-mediamodal.js',            array( 'jquery' ), WPLMAN_VERSION, true );

			wp_enqueue_media();
		}



		/**
		 * Create js Obj that holds some needed dynamic data in js files
		 */
		wp_localize_script( 'wplman', 'WP',
			array(
				'nonce' => wp_create_nonce('ajax-nonce')
			)
		);


		/**
		 * Create js Obj that holds translated strings
		 */
		wp_localize_script( 'mediamodal' , 'i18n' ,
			array(
				'choose_image_btn' =>               __('Choose Image'           , WPLMAN_TEXTDOMAIN),
				'mediamodal_title' =>               __('Upload or choose image' , WPLMAN_TEXTDOMAIN),
			)
		);

		wp_localize_script( 'wplman' , 'i18nStr' ,
			array(
				'__get_locale'                      => get_locale(),
				'__error'                           => __('Error' , WPLMAN_TEXTDOMAIN),
				'__cancel'                          => __('Cancel' , WPLMAN_TEXTDOMAIN),
				'__ok'                              => __('Ok' , WPLMAN_TEXTDOMAIN),
				'__done'                            => __('Done' , WPLMAN_TEXTDOMAIN),
				'__delete'                          => __('Delete' , WPLMAN_TEXTDOMAIN),
				'__update'                          => __('Update' , WPLMAN_TEXTDOMAIN),
				'__save'                            => __('Save' , WPLMAN_TEXTDOMAIN),
				'__settings_saved'                  => __('Settings saved.' , WPLMAN_TEXTDOMAIN),
				'__save_error'                      => __('Problem occurred during dave options!' , WPLMAN_TEXTDOMAIN),
				'__ajax_error'                      => __('We have problem to do this, reload page and try again!' , WPLMAN_TEXTDOMAIN),
				'__error_form_validation'           => __('Fill all necessary fields' , WPLMAN_TEXTDOMAIN),
			)
		);

	}


}
new Wplman_Dashboard_Scripts();