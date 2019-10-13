<?php
/**
 * Markup of dropdown select of link_group taxonomy terms
 * show top of list shortlinks table
 */
function wplman_dropdown_shortlink_groups(){
	$link_groups = get_terms( array(
		'taxonomy' => 'link_group',
		'hide_empty' => false,
	) );
	if(is_wp_error($link_groups))
		return;

	$term_options = '<option value="0">'.__('All Groups' , WPLMAN_TEXTDOMAIN).'</option>';
	foreach ($link_groups as $term){
		$term_options .= '<option value="'.$term->term_id.'">'.$term->name.'['.$term->count.']</option>';
	}

	echo '<select name="link_group" class="postform">'.$term_options.'</select>';
	echo '<input type="submit" name="filter_action" id="post-query-submit" class="button" value="'.__('Filter' , WPLMAN_TEXTDOMAIN).'">';
}


