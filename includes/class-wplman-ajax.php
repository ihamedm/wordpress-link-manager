<?php

class Wplman_Ajax {

	public function __construct(){
		add_action('wp_ajax_wplman_shortlink_list',                 array($this , 'wplman_shortlink_list'));
		add_action('wp_ajax_wplman_pagnation_form',                 array($this , 'wplman_pagnation_form'));
	}


	public function make_query($get){
		$args = array(
			'post_type' => 'shortlink'
		);

		if(isset($get) && $get !== false){
			$query_data = $get;

			if(isset($query_data['s']) && $query_data['s'] !== "NaN")
				$args['s'] = $query_data['s'];

			if(isset($query_data['link_group']) && $query_data['link_group'] !== "0")
				$args['tax_query'] = array(
					array(
						'taxonomy' => 'link_group',
						'terms' => (int) $query_data['link_group']
					)
				);
			if(isset($query_data['paged']))
				$args['paged'] = (int) $query_data['paged'];

		}

//		var_dump($_GET['query_data']);
//		die();


		$query = new WP_Query($args);
		return $query;
	}


	public function wplman_shortlink_list(){
		$query = $this->make_query($_GET['query_data']);
		if($query->have_posts()) : while($query->have_posts()) : $query->the_post();

		$meta = get_post_meta(get_the_ID());
			?>
			<tr>
				<td>
					<strong>#<?php the_ID();?> - <?php the_title();?></strong>
					<div class="row-actions">
						<span class="detail"><a href="#"><?php _e('Details', WPLMAN_TEXTDOMAIN);?></a> | </span>
						<span class="edit"><a href="#"><?php _e('Edit', WPLMAN_TEXTDOMAIN);?></a> | </span>
						<span class="trash"><a href="#" class="submitdelete"><?php _e('Trash', WPLMAN_TEXTDOMAIN);?></a></span>
					</div>
				</td>

				<td>
					<?php echo $meta['shortlink_target_url'][0];?>
					<a href="<?php echo $meta['shortlink_target_url'][0];?>" target="_blank"><span class="dashicons dashicons-external"></span></a>

					<p>
						<?php echo __('Redirect Type: ', WPLMAN_TEXTDOMAIN) . '<strong>' .$meta['shortlink_redirect_type'][0] .'</strong>';?>&nbsp;&nbsp;|&nbsp;&nbsp;
						<?php echo ($meta['shortlink_nofollow'][0] == 'yes') ? '<strong>'.__('NoFollow', WPLMAN_TEXTDOMAIN).'</strong>' : '';?>
					</p>
				</td>


				<td><?php echo $meta['shortlink_hits'][0];?></td>
				<td><?php echo $meta['shortlink_description'][0];?></td>

				<td>
					<strong><?php echo get_post_status();?></strong>
					<p><?php printf( _x( '%s ago', '%s = human-readable time difference', WPLMAN_TEXTDOMAIN ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></p>
				</td>
			</tr>
			<?php
		endwhile;
		else:
			_e('Not found any data', WPLMAN_TEXTDOMAIN);
		endif;wp_reset_query();
		die();
	}

	public function wplman_pagnation_form(){
		$query = $this->make_query($_GET['query_data']);
		$current_page = (isset($_GET['query_data']) && (int) $_GET['query_data']['paged'] > 0) ? (int) $_GET['query_data']['paged'] : 1;
		$max_page = ($query->max_num_pages > 0 ) ? $query->max_num_pages : 1;
		$prev_disabled = ($current_page == 1) ? 'disabled' : '';
		$next_disabled = ($current_page == $max_page) ? 'disabled' : '';
	?>

		<span class="displaying-num"><?php echo $query->found_posts .'&nbsp;'. __('items', WPLMAN_TEXTDOMAIN);?></span>
		<span class="pagination-links">
            <span class="prev-page button <?php echo $prev_disabled;?>" aria-hidden="true">‹</span>
            <span class="paging-input">
	            <input class="current-page" id="current-page-selector" type="number" min="1" max="<?php echo $max_page;?>" value="<?php echo $current_page;?>" name="paged" aria-describedby="table-paging">
                <span class="tablenav-paging-text"> <?php _e('of', WPLMAN_TEXTDOMAIN );?> <span class="total-pages"><?php echo $max_page;?></span></span>
            </span>
            <a class="next-page button <?php echo $next_disabled;?>" href="#"><span aria-hidden="true">›</span></a>
		</span>

	<?php
		die();

	}
}
new Wplman_Ajax();