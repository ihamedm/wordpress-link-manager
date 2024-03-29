<?php

/**
 *  - Define shortcodes
 *  - enqueue needed script and styles for frontend
 *  - ajax functions fro frontend
 *
 * Class Wplman_Frontend
 */
class Wplman_Frontend{

	public function __construct() {
		add_shortcode('shortlinks_list',    array($this, 'display_shortlinks_shortcode_markup'));
		add_shortcode('shortlink',          array( $this, 'shortlink_insert_shortcode_markup' ) );

		add_action('wp_enqueue_scripts',        array( $this , 'register_custom_frontend_assets' ) );

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


	/**
	 * Display shortlinks list in frontend with shortcode
	 */
	public function display_shortlinks_shortcode_markup(){
		?>


        <div id="wplman-list-shortlinks-frontend">
			<form id="shortlink-posts-filter">
				<div class="search-box">
					<input type="search" id="post-search-input" name="s" placeholder="<?php _e('Search ...', WPLMAN_TEXTDOMAIN);?>">
				</div>

				<div class="nav-box">
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



	public function shortlink_insert_shortcode_markup($atts){
		if(isset($atts['id'])){
			$shortlink_meta = get_post_meta($atts['id']);

			$shortlink = array(
				'target'        => $shortlink_meta['shortlink_target'][0],
				'nofollow'      => $shortlink_meta['shortlink_nofollow'][0],
			);

			$nofollow_attr = ($shortlink['nofollow'] == 'yes') ? 'nofollow' : '';
			$text = ($atts['text'] == '') ? get_the_title($atts['id']) : $atts['text'];
			$target_attr = ($shortlink['target'] == 'blank') ? 'target="_blank"' : '';

			echo '<a href="'.get_permalink($atts['id']).'" '.$target_attr.'  '.$nofollow_attr.'>'.$text.'</a>';
		}else{
		    echo 'shortcode not defiend properly';
        }
	}
}
new Wplman_Frontend();