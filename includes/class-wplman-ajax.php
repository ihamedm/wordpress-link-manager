<?php

class Wplman_Ajax {

	public function __construct(){
		add_action('wp_ajax_wplman_shortlink_list',                 array($this , 'wplman_shortlink_list'));
		add_action('wp_ajax_wplman_pagnation_form',                 array($this , 'wplman_pagnation_form'));
		add_action('wp_ajax_wplman_delete_shortlink',               array($this , 'wplman_delete_shortlink'));
		add_action('wp_ajax_wplman_edit_form_shortlink',            array($this , 'wplman_edit_form_shortlink'));
		add_action('wp_ajax_wplman_detail_shortlink',               array($this , 'wplman_detail_shortlink'));
		add_action('wp_ajax_wplman_add_form_shortlink',             array($this , 'wplman_add_form_shortlink'));
		add_action('wp_ajax_wplman_save_shortlink',                 array($this , 'wplman_save_shortlink'));

	}

	/**
	 * @param $get
	 *
	 * @return WP_Query
	 */
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

		$query = new WP_Query($args);
		return $query;
	}

    public function shortlink_form($shortlink_id = false){

	    $shortlink = array(
	            'ID' => '',
	            'title' => '',
	            'post_name' => '',
	            'description'   => '',
	            'target_url'    => '',
	            'slug'          => '',
	            'target'        => 'blank',
	            'redirect_type' => 301,
	            'nofollow'      => 'yes'
        );

	    if(isset($shortlink_id) && is_numeric($shortlink_id)){
		    $shortlink_query = new WP_Query(array('p' => $shortlink_id, 'post_type'=>'shortlink'));
		    $shortlink_query->the_post();
		    global $post;
		    $shortlink['post_name'] = $post->post_name;
		    $shortlink['title'] = $post->post_title;
		    $shortlink['ID'] = $shortlink_id;
		    wp_reset_postdata();

		    $shortlink_meta = get_post_meta($shortlink_id);


		    $shortlink = array_merge($shortlink, array(
	                'description'   => $shortlink_meta['shortlink_description'][0],
	                'target_url'    => $shortlink_meta['shortlink_target_url'][0],
	                'target'        => $shortlink_meta['shortlink_target'][0],
	                'redirect_type' => $shortlink_meta['shortlink_redirect_type'][0],
	                'nofollow'      => $shortlink_meta['shortlink_nofollow'][0],
            ));
	    }

	    ?>

        <form id="shortlink-edit-form">
            <div class="alert-area"></div>

            <div class="mask">
                <span class="spinner is-active"></span>
            </div>
            <input name="ID" value="<?php echo $shortlink['ID'];?>" type="hidden">
            <input name="post_type" value="shortlink" type="hidden">
            <input name="post_status" value="publish" type="hidden">
        <table class="form-table widefat" id="post">
            <tbody>
                <tr>
                    <th>
                        <label for="shortlink_title">Title</label>
                    </th>
                    <td>
                        <input type="text" id="shortlink_title" name="post_title" value="<?php echo $shortlink['title'];?>" required="required"><br>
                        <span class="description">redirect user to this link</span>
                    </td>
                </tr>

                <tr>
                    <th>
                        <label for="shortlink_description">Description</label>
                    </th>
                    <td>
                        <textarea rows="4" id="shortlink_description" name="shortlink_description"><?php echo $shortlink['description'];?></textarea><br>
                        <span class="description">This text only show in admin area</span>
                    </td>
                </tr>

                <tr>
                    <th>
                        <label for="shortlink_target_url">Destination URL</label>
                    </th>
                    <td>
                        <input type="url" id="shortlink_target_url" name="shortlink_target_url" value="<?php echo $shortlink['target_url'];?>" required=""><br>
                        <span class="description">redirect user to this link</span>
                    </td>
                </tr>

                <tr>
                    <th>
                        <label for="shortlink_slug">Short link slug</label>
                    </th>
                    <td>
                        <input type="text" id="shortlink_slug" name="post_name" value="<?php echo $shortlink['post_name'];?>" required="required">
                    </td>
                </tr>

                <tr>
                    <th>
                        <label for="link_group">Short link group</label>
                    </th>
                    <td>
                        <div class="link-group-wrap">
                            <ul class="categorychecklist form-no-clear">
                            <?php
                            $all_terms = get_terms('link_group', array('hide_empty' => 0));
                            $post_term_ids = wp_get_post_terms($post->ID, 'link_group', array('fields' => 'ids'));

                            foreach($all_terms as $term){
                                $checked = (in_array($term->term_id,$post_term_ids)) ? 'checked' : '';
                                echo '<li><label><input type="checkbox" name="link_group[]" value="'.$term->term_id.'" '.$checked.'>'.$term->name.'</label></li>';
                            }
                            ?>
                            </ul>
                         </div>
                        <a target="_blank" href="<?php echo admin_url('edit-tags.php?taxonomy=link_group&post_type=shortlink');?>" title="<?php _e('Add New', WPLMAN_TEXTDOMAIN);?>">
                            <span class="dashicons dashicons-plus"></span><?php _e('Add new link group', WPLMAN_TEXTDOMAIN);?>
                        </a>
                    </td>
                </tr>

                <tr>
                    <th>
                        <label for="shortlink_target">Target</label>
                    </th>
                    <td>
                        <label for=""><input id="shortlink_target-blank" name="shortlink_target" type="radio" value="blank" <?php echo ($shortlink['target'] == 'blank' ) ? 'checked' : '';?>>Open in new tab</label>
                        <label for=""><input id="shortlink_target-none" name="shortlink_target" type="radio" value="none" <?php echo ($shortlink['target'] == 'none' ) ? 'checked' : '';?>>Open in same tab</label>
                    </td>
                </tr>

                <tr>
                    <th>
                        <label for="shortlink_redirect_type">Redirect Type</label>
                    </th>
                    <td>
                        <label for=""><input id="shortlink_redirect_type-301" name="shortlink_redirect_type" type="radio" value="301" <?php echo ($shortlink['redirect_type'] == 301 ) ? 'checked' : '';?>>Permanent 301</label>
                        <label for=""><input id="shortlink_redirect_type-302" name="shortlink_redirect_type" type="radio" value="302" <?php echo ($shortlink['redirect_type'] == 302 ) ? 'checked' : '';?>>Temporary 302</label>
                    </td>
                </tr>

                <tr>
                    <th>
                        <label for="shortlink_nofollow">Nofollow attribute</label>
                    </th>
                    <td>
                        <label for=""><input id="shortlink_nofollow-yes" name="shortlink_nofollow" type="radio" value="yes" <?php echo ($shortlink['nofollow'] == 'yes' ) ? 'checked' : '';?>>Add Nofollow attribute</label>
                        <label for=""><input id="shortlink_nofollow-no" name="shortlink_nofollow" type="radio" value="no" <?php echo ($shortlink['nofollow'] == 'no' ) ? 'checked' : '';?>>Do not add Nofollow attribute</label>
                    </td>
                </tr>

            </tbody>
        </table>

            <button type="submit" class="button button-primary button-hero"><?php _e('Save date', WPLMAN_TEXTDOMAIN);?></button>

        </form>

        <?php

    }



    /**
	 * Ajax methods
	 */

	public function wplman_shortlink_list(){
		$query = $this->make_query($_GET['query_data']);
		if($query->have_posts()) : while($query->have_posts()) : $query->the_post();

		$meta = get_post_meta(get_the_ID());
			?>
			<tr id="shortlink-<?php the_ID();?>" data-id="<?php the_ID();?>" class="shortlink">
				<td>
					<strong>#<?php the_ID();?> - <?php the_title();?></strong>
					<div class="row-actions">
						<span class="wplman-detail"><a href="#"><?php _e('Detail', WPLMAN_TEXTDOMAIN);?></a> | </span>
						<span class="wplman-edit"><a href="#"><?php _e('Edit', WPLMAN_TEXTDOMAIN);?></a> | </span>
						<span class="wplman-trash trash"><a href="#" class="submitdelete"><?php _e('Trash', WPLMAN_TEXTDOMAIN);?></a></span>
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
		$max_page = ($query->max_num_pages > 0 ) ? $query->max_num_pages : 1;
		$current_page = (isset($_GET['query_data']) && (int) $_GET['query_data']['paged'] > 0 && (int) $_GET['query_data']['paged'] <= $max_page) ? (int) $_GET['query_data']['paged'] : 1;
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


	public function wplman_delete_shortlink(){
	    if(isset($_POST['post_id'])){
		    $removed = wp_trash_post($_POST['post_id']);
		    if($removed){
		        wp_send_json_success($removed);
            }else{
		        wp_send_json_error($removed);
            }
        }else{
		    wp_send_json_error('post id not valid');
        }
	    die();
    }


    public function wplman_edit_form_shortlink(){
        $this->shortlink_form((int) $_GET['shortlink_id']);
        die();
    }


    public function wplman_add_form_shortlink(){
        $this->shortlink_form();
        die();
    }


    public function wplman_detail_shortlink(){
	    if(isset($_GET['shortlink_id']) && is_numeric($_GET['shortlink_id'])){
		    $shortlink_query = new WP_Query(array('p' => $_GET['shortlink_id'], 'post_type'=>'shortlink'));
		    $shortlink_query->the_post();
		    $shortlink['title'] = get_the_title($_GET['shortlink_id']);
		    wp_reset_postdata();

		    $shortlink_meta = get_post_meta($_GET['shortlink_id']);


		    $shortlink = array_merge($shortlink, array(
			    'description'   => $shortlink_meta['shortlink_description'][0],
			    'target_url'    => $shortlink_meta['shortlink_target_url'][0],
			    'target'        => $shortlink_meta['shortlink_target'][0],
			    'slug'          => $shortlink_meta['shortlink_slug'][0],
			    'redirect_type' => $shortlink_meta['shortlink_redirect_type'][0],
			    'nofollow'      => $shortlink_meta['shortlink_nofollow'][0],
		    ));

		    foreach($shortlink as $key => $value ){
		        echo '<p><strong>'.$key.'  :</strong> '.$value.'</p>';
            }
		    echo '<a href="'.get_permalink($shortlink['ID']).'" target="_blank">'.get_permalink($shortlink['ID']).'</a>';
	    }
        die();
    }


    public function wplman_save_shortlink(){
	    $form_data = $_POST['form_data'];
	    if(isset($form_data['ID']) && (int) $form_data['ID'] > 0 ){
		    $result = wp_update_post($form_data, true);
		    if(is_wp_error($result)){
			    echo '<div class="notice notice-error notice-alt"><p>'.__('Something wrong, refresh page and try again!').'</p></div>';
			    die();
            }
        }else{
	        unset($form_data['ID']);
	        $new_id = wp_insert_post($form_data, true);
		    if(is_wp_error($new_id)){
			    echo '<div class="notice notice-error notice-alt"><p>'.__('Something wrong, refresh page and try again!').'</p></div>';
			    die();
		    }
	        $form_data['ID'] = $new_id;
        }

	    foreach ($form_data as $key => $value){
		    if(substr($key, 0, 10) === 'shortlink_'){
			    update_post_meta($form_data['ID'], $key, $value);
		    }
	    }

	    if(isset($form_data['link_group']) && is_array($form_data['link_group'])){
		    wp_set_post_terms($form_data['ID'], $form_data['link_group'], 'link_group');
        }else{
		    wp_set_object_terms($form_data['ID'], NULL, 'link_group');
        }


        echo '<div class="notice notice-success notice-alt"><p>'.__('Update shortlink was successful.').'</p></div>';
	    die();
    }
}
new Wplman_Ajax();