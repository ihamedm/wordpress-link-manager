<?php


class Wplman_Dashboard_Pages{


	public function __construct() {
		add_action( 'admin_menu', array( $this , 'define_dashboard_pages' ) );
	}


	/**
	 * Define menu items and pages in wordpress
	 *
	 */
	public function define_dashboard_pages()
	{
		add_menu_page(
			__('WP Link Manager' , WPLMAN_TEXTDOMAIN ),
			__('WP Link Manager' , WPLMAN_TEXTDOMAIN ),
			'manage_options' ,
			'wplman-dashboard',
			array($this , 'main_page_markup') ,
			'dashicons-external',
			25
		);


		add_submenu_page(
			'wplman-dashboard',
			__('Wp Link Manager Configurations', WPLMAN_TEXTDOMAIN),
			__('Configurations', WPLMAN_TEXTDOMAIN),
			'manage_options',
			'wplman-configurations',
			array($this, 'configurations_page_markup')
		);


		add_submenu_page(
			'wplman-dashboard',
			__('Wp Link Manager About', WPLMAN_TEXTDOMAIN),
			__('Help & Support', WPLMAN_TEXTDOMAIN),
			'manage_options',
			'wplman-about',
			array($this, 'about_page_markup')
		);

	}


	/**
	 * Dashboard page markup methods
	 * - configurations
	 * - about
	 */

	public function main_page_markup(){
		?>
		<div id="wplman-dashboard" class="wrap">

			<h1><?php _e('Wp Link Manager Dashboard', WPLMAN_TEXTDOMAIN);?> <sup><?php echo WPLMAN_VERSION; ?></sup></h1>

            <?php include_once dirname( __FILE__ ) . '/class-wplman-page-main.php';?>

		</div>
		<?php
	}


	/**
	 * Create Tabs & sections Nav and logic for loading each content
	 */
	public function configurations_page_markup() {
		?>
		<div class="wrap">

			<h1><?php _e( 'Wp Link Manager Configurations', WPLMAN_TEXTDOMAIN); ?></h1>

			<?php
				settings_errors();

				echo '<form method="post" action="options.php">';
				settings_fields( WPLMAN_PREFIX . 'main_configs' );
				do_settings_sections( 'wplman-configurations' );
				submit_button();
				echo '</form>';
			?>

		</div>
		<?php
	}



	public function about_page_markup(){
	    ?>
        <div class="wrap">
            <h1><?php _e( 'Wp Link Manager Help & Support', WPLMAN_TEXTDOMAIN); ?></h1>

            <div class="content">
                <ul>
                    <li>How to use?</li>
                    <li>License</li>
                    <li>Github pages</li>
                    <li>Support</li>
                </ul>
            </div>
        </div>

    <?php
	}
}

new Wplman_Dashboard_Pages();