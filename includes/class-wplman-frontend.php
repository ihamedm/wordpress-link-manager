<?php

class Wplman_Frontend{

	public function __construct() {
		add_shortcode('shortlinks_list', array($this, 'display_shortlinks_shortcode_markup'));
		add_action( 'wp_enqueue_scripts', array( $this , 'register_custom_frontend_assets' ) );

		require_once dirname( __FILE__ ) . '/class-wplman-ajax-frontend.php';

	}


	/**
	 * Register Minapp script and styles
	 */
	public function register_custom_frontend_assets(){

		if ( ! wp_script_is( 'jquery', 'enqueued' )) {
		    wp_enqueue_script( 'jquery' );
		}

		wp_enqueue_style(   'wplman-frontend'   ,WPLMAN_URL . 'assets/css/wplman-frontend.css'   ,'',WPLMAN_VERSION , 'all');
		wp_enqueue_script(  'wplman-frontend'   ,WPLMAN_URL . 'assets/js/wplman-frontend.js'     , array('jquery'),WPLMAN_VERSION, TRUE );


		wp_localize_script( 'wplman-frontend', 'frontendObj',
			array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) )
        );
	}

	public function display_shortlinks_shortcode_markup(){
		?>


        <div id="wplman-list-shortlinks-frontend">
			<form id="shortlink-posts-filter">
				<p class="search-box">
					<label class="screen-reader-text" for="post-search-input">Search Short links:</label>
					<input type="search" id="post-search-input" name="s" value="">
					<input type="submit" id="search-submit" class="button" value="Search Short links">
				</p>

				<div class="tablenav top">
					<div class="alignleft actions">

						<?php wplman_dropdown_shortlink_groups();?>

					</div>



					<div class="tablenav-pages" id="pagination-shortlinks">
						<!-- Ajax pagination placed here -->
					</div>

				</div>
			</form>

			<div id="mask">
				<div class="loading-msg">
					<span class="spinner is-active"></span>
					<p><?php _e('Loading data ...', WPLMAN_TEXTDOMAIN);?></p>
				</div>
			</div>

			<table class="wp-list-table widefat fixed striped posts">
				<thead>
				<tr>
					<th class="wplman-table-label"><?php _e('Link', WPLMAN_TEXTDOMAIN);?></th>
					<th class="wplman-table-label"><?php _e('Target link', WPLMAN_TEXTDOMAIN);?></th>
					<th class="wplman-table-label"><?php _e('Hits', WPLMAN_TEXTDOMAIN);?></th>
					<th class="wplman-table-label"><?php _e('Description', WPLMAN_TEXTDOMAIN);?></th>
					<th style="width: 10%;" class="wplman-table-label"><?php _e('Date', WPLMAN_TEXTDOMAIN);?></th>
				</tr>

				</thead>

				<tbody>

				<!-- data placed with ajax request here -->
				</tbody>

			</table>
		</div>

	<?
	}

}
new Wplman_Frontend();