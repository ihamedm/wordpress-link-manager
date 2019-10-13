<?php

/**
 * handle redirect actions
 *
 * Class Wplman_Redirect
 */
class Wplman_Redirect{

	public function __construct() {
		add_action( 'template_redirect', array( $this, 'handle_redirect' ) );
	}


	public function handle_redirect() {
		$post = get_post();
		if ( $post && 'shortlink' === $post->post_type ) {
			$target_url = get_post_meta($post->ID, 'shortlink_target_url', true);
			$redirect_type = get_post_meta($post->ID, 'shortlink_redirect_type', true);
			$hits = get_post_meta($post->ID, 'shortlink_hits', true);
			update_post_meta($post->ID, 'shortlink_hits', $hits + 1);

			wp_redirect( $target_url, intval( $redirect_type ) );
			exit();
		}
	}
}

new Wplman_Redirect();

