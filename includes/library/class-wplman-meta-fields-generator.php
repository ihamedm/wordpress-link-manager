<?php

/**
 * Class Wplman_Meta_Fields_Generator
 */
class Wplman_Meta_Fields_Generator{

    public $post_type;
    public $meta_prefix;
    public $meta_fields;
    public $meta_box_title;
    public $meta_box_context;
    public $meta_box_priority;


	public function __construct( $args ) {
		/**
         * get data from $args &  define needed variables
		 */
		$this->post_type            = $args['post_type'];
		$this->meta_fields          = $args['fields'];
		$this->meta_box_title       = $args['meta_box_title'];
		$this->meta_prefix          = !empty($args['meta_prefix']) ? $args['meta_prefix'] : $args['post_type'] . '_';
        $this->meta_box_context     = !empty($args['meta_box_context']) ? $args['meta_box_context'] : 'normal';
        $this->meta_box_priority    = !empty($args['meta_box_priority']) ? $args['meta_box_priority'] : 'high';


		/**
		 * - add meta box
         * - save fields when save_post triggered
		 */
		add_action( 'add_meta_boxes',   array( &$this , 'add_meta_box_callback') );
		add_action( 'save_post',        array( &$this , 'save_post_meta_callback') );
	}


	/**
	 * Add a meta box
	 */
	public function add_meta_box_callback() {
		global $post;

        add_meta_box(
           $this->post_type . '_meta_box',
            $this->meta_box_title,
            array(  &$this , 'meta_box_display_callback'),
            $this->post_type,
	        $this->meta_box_context,
            $this->meta_box_priority
        );


		/**
		 * Add styles & scripts
		 */
        if( $post->post_type == $this->post_type ){
	        add_action('admin_print_scripts'    , array( &$this , 'registerScripts' ) );
	        add_action('admin_head'             , array( &$this , 'simpulMetaUpload' ), 11);
	        add_action('admin_print_styles'     , array( &$this , 'registerStyles' ) );
        }
	}


	/**
     * Create table that wrap fields
	 * @param $post
     *
	 */
	public function meta_box_display_callback( $post ) {
		global $post;


		/**
		 * Create hidden input field - wordpress uses nonce field to validate form on save
		 */
		echo '<input type="hidden" name="'. $this->meta_prefix . 'meta_box_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';


		/**
		 * HTML Table to hold fields
		 */
		?>
        <table class="form-table widefat" id="post">
<!--            <span id="bloginfo" stylesheet_dir_uri="' . --><?php //echo get_template_directory_uri();?><!-- . '" style="display: none"></span>-->

            <?php
                foreach($this->meta_fields as $field):
	                $field['id'] = $this->meta_prefix . $field['id'];
	                $field['value'] = get_post_meta($post->ID, $field['id'] ,true);
                    $this->generate_field_row($field);
                endforeach;
		    ?>
		</table>
        <?php

	}


	/**
     * Generate markup for each input field
	 * @param $field
	 */
	public function generate_field_row($field){
        $required = (isset($field['required']) && $field['required'] === true) ? 'required' : '';
        $readonly = (isset($field['readonly']) && $field['readonly'] === true) ? 'readonly' : '';
		$field['value'] = (isset($field['default']) && empty($field['value'])) ? $field['default'] : $field['value'];

		echo '<tr><th><label for="' .$field['id']. '">'. $field['label'] . '</label></th><td>';

		switch($field['type']):

//			@todo add fields [datetime, time, image, file, video, multi check, ]

            case "checkbox":
				$checked = ($field['value'] == '1') ? 'checked' : '';
				echo '<input type="checkbox" id="' .  $field['id'] . '" name="' .  $field['id'] . '" value="1" ' . $checked . ' ' . $required . ' '. $readonly .'/>';
				break;


            case "select":
				if(!empty( $field['values'])):
                    echo '<select id="' .  $field['id'] . '" name="' .  $field['id'] . '" ' . $required . ' '. $readonly .'>';
                    foreach( $field['values'] as $key => $label):
	                    $selected = ($field['value'] == $key) ? 'selected' : '';
                        echo '<option value="' . $key . '" '.$selected.'>' . $label . '</option>';
                    endforeach;
                    echo '</select>';
                endif;
				break;


			case "radio":
				if(!empty( $field['values'])):
					foreach( $field['values'] as $key => $label):
						$checked = ($field['value'] == $key) ? 'checked' : '';
						echo '<label for=""><input id="' .  $field['id'] . '-' . $key. '" name="' .  $field['id'] . '" type="radio" value="' . $key . '" '.$checked.'>' . $label .'</label>';
					endforeach;
				endif;
				break;


			case "textarea":
				echo '<textarea rows="4" id="' .  $field['id'] . '" name="' .  $field['id'] . '" ' . $required . ' '. $readonly .'>' .  $field['value'] . '</textarea>';
				break;


			case ('editor'):
				wp_editor( $field['value'] , $field['id'] , array(
					'wpautop'       => true,
					'media_buttons' => true,
					'textarea_rows' => 10,
					'teeny'         => isset($field['teeny']) ? $field['teeny'] : true,
				) );
				break;

            case "url":
	            echo '<input type="url" id="' .  $field['id'] . '" name="' .  $field['id'] . '" value="' .  $field['value'] . '" ' . $required . ' '. $readonly .'/>';
	            break;


            case "number":
	            echo '<input type="number" id="' .  $field['id'] . '" name="' .  $field['id'] . '" value="' .  $field['value'] . '" ' . $required . ' '. $readonly .'/>';
	            break;


            default:
				echo '<input type="text" id="' .  $field['id'] . '" name="' .  $field['id'] . '" value="' .  $field['value'] . '" ' . $required . ' '. $readonly .'/>';
				break;
		endswitch;

        if(isset($field['help_text']))
		    echo '<br/><span class="description">'. $field['help_text'] .'</span></td></tr>';
	}


	public function save_post_meta_callback( $post_id ) {
		global $post;
		/**
		 * Some check before save post meta
         * - verify nonce created at meta box form
         * - save only if update/publish button submitted
         * - check current user permission
         * - check post type
		 */

		if (!wp_verify_nonce($_POST[$this->meta_prefix . 'meta_box_nonce'], basename(__FILE__)))
			return $post_id;


		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return $post_id;


		if ( !current_user_can( 'edit_post', $post->ID) )
			return $post_id;


		if( $this->post_type !== $_POST['post_type'])
		    return $post_id;


		/**
		 * Loop around field names and save posted data to db
		 */
		foreach($this->meta_fields as $field):
			$field['id'] = $this->meta_prefix . $field['id'];

		    $clear_value = sanitize_meta($field['id'], $_POST[$field['id']], 'post');
			if(isset($_POST[$field['id']])):
				switch( $field['type'] ):
					case "datetime":
						update_post_meta($post->ID, $field['id'], date("Y-m-d H:i:s", strtotime( $clear_value ) ) );
						break;
					case "date":
						update_post_meta($post->ID, $field['id'], date("Y-m-d", strtotime( $clear_value ) ) );
						break;
					case "text":
						update_post_meta($post->ID, $field['id'], sanitize_text_field($clear_value));
						break;
					case "textarea":
						update_post_meta($post->ID, $field['id'], sanitize_textarea_field($clear_value));
						break;
					case "number":
						update_post_meta($post->ID, $field['id'], (int)($clear_value));
						break;
					case "email":
						update_post_meta($post->ID, $field['id'], sanitize_email($clear_value));
						break;
					case "editor":
						update_post_meta($post->ID, $field['id'], sanitize_textarea_field($clear_value));
						break;
					default:
						update_post_meta($post->ID, $field['id'], sanitize_text_field($clear_value));
						break;
				endswitch;

            else:
				if( get_post_meta($post->ID, $field['id'], true) ):
					delete_post_meta($post->ID, $field['id']);
				endif;
			endif;
		endforeach;;

	}


	public function registerScripts()
	{
		if(!wp_script_is('media-upload')):
			wp_enqueue_script('media-upload');
		endif;
		if(!wp_script_is('thickbox')):
			wp_enqueue_script('thickbox');
		endif;
	}
	public function registerStyles()
	{
		wp_enqueue_style('thickbox');
		//wp_register_style('jquery-ui-custom-css', WP_PLUGIN_URL . '/simpulevents/css/jquery-ui-1.8.16.custom.css');
		//wp_enqueue_style('jquery-ui-custom-css');
	}
	public function simpulMetaUpload(){
		$GLOBALS['simpul_meta_upload'] = true;
		?>
        <script type="text/javascript">
            var original_send_to_editor = "";
            var modified_send_to_editor = "";
            var formfield = '';
            var hrefurl = '';

            jQuery(document).ready( function() {

                original_send_to_editor = window.send_to_editor;

                modified_send_to_editor = function(html) {
                    hrefurl = jQuery('img',html).attr('src');
                    console.log(jQuery(html));
                    if(!hrefurl) {
                        hrefurl = jQuery(html).attr('href'); // We do this to get Links like PDF's
                    }
                    hrefurl = hrefurl.substr(hrefurl.indexOf('/',8)); // Skips "https://" and extracts after first instance of "/" for relative URL, ex. "/wp-content/themes/currentheme/images/etc.jpg"
                    console.log(hrefurl);
                    jQuery('#' + formfield).val(hrefurl);
                    tb_remove();
                    window.send_to_editor = original_send_to_editor;
                };

                jQuery('.simpul_meta_upload').click(function() {
                    window.send_to_editor = modified_send_to_editor;
                    formfield = jQuery(this).attr('data-input');
                    tb_show('Add File', 'media-upload.php?TB_iframe=true');
                    console.log(formfield);
                    return false;
                });
            } );
        </script>
		<?php
	}


}

// EOF
