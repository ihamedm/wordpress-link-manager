<?php

include_once dirname( __FILE__ ) . '/wplman-helpers.php';
class Wplman_Field_Generator {

	public function __construct(){

	}

	static function create_field_markup( $args ){


		if($args){

			$type = $args['type'];
			$name = $args['name'];
			$class = isset($args['input_class']) ? $args['input_class'] : '';
			$description = $args['description'];

			if(isset($args['options']) && is_array($args['options'])){

				$select_options = $args['options'];

			}

			if(get_option($name)){
				$value = get_option($name);

				// check if options available $value null = array()
				if(isset($args['options']) && is_array($args['options'])){
					$value = !empty($value) ? $value : array();

				}else{
					$value = isset($value) ? $value : '';
				}

			}elseif(isset($args['default'])){
				$value = $args['default'];
			}else{
				$value = '';
			}

		}



		$html = '';
		switch ($type){
			case 'hidden':
				$html  = '<input type="hidden" name="'.$name.'" id="'.$name.'" value="'.$value.'" class="hidden-row" >';
				break;
			case 'text':
				$html  = '<input type="text" name="'.$name.'" id="'.$name.'" class="regular-text '.$class.'" value="'.$value.'" >';
				break;
			case 'email':
				$html  = '<input type="email" name="'.$name.'" id="'.$name.'" class="regular-text '.$class.'" value="'.$value.'" >';
				break;

			case 'multiselect' :
				$html  = '<select name="'.$name.'[]" id="'.$name.'" class="'.$class.'" multiple >';
				foreach ($select_options as $option) {
					$html .= sprintf('<option %1$s value="%2$s"  >%3$s</option>' , in_array($option['value'] , $value ) ? '  selected="selected"' : '' , $option['value'] , $option['label']);
				}
				$html .= '</select>';
				break;

			case 'select' :
				$html  = '<select name="'.$name.'" id="'.$name.'" class="'.$class.'">';
				foreach ($select_options as $option) {
					$html .= sprintf('<option %1$s value="%2$s"  >%3$s</option>' , ($option['value'] == $value ) ? '  selected="selected"' : '' , $option['value'] , $option['label']);
				}
				$html .= '</select>';
				break;

			case 'multicheck' :
				foreach ($select_options as $option) {
					$html .= sprintf('<label><input type="checkbox" %1$s value="%2$s" name="%3$s[]" class="'.$class.'" >%4$s</label><br>' , in_array($option['value'] , $value ) ? '  checked' : '' , $option['value'] , $name , $option['label']);
				}
				break;
			case 'textarea':
				$html = '<textarea rows="5" cols="30" name="'.$name.'" id="'.$name.'" class="regular-text '.$class.'">'.$value.'</textarea>';
				break;

			case 'image':
				$html  = '<div class="wpba-field-image-wrap">
                            <div class="image-preview">'.wp_get_attachment_image($value).'</div>
                            <input type="hidden" id="'.$name.'" name="'.$name.'" value="'.$value.'" />
                            <button type="button" class="button upload-image"><span class="dashicons-before dashicons-format-image"></span>'.__('Choose Image' , 'wp_minapp').'</button>
                            <button type="button" class="button delete-image hidden" ><span class="dashicons-before dashicons-dismiss"></span>'.__('Delete Image' , 'wp_minapp').'</button>
                          </div>';
				break;
			case 'image_src':
				$src = wp_get_attachment_image_src($value);
				$html  = '<div class="wpba-field-image-wrap">
                            <div class="image-preview">'.wp_get_attachment_image($value).'</div>
                            <input type="hidden" id="'.$name.'" name="'.$name.'" value="'.$value.'" data-src="'.$src[0].'" />
                            <button type="button" class="button upload-image"><span class="dashicons-before dashicons-format-image"></span>'.__('Choose Image' , 'wp_minapp').'</button>
                            <button type="button" class="button delete-image hidden" ><span class="dashicons-before dashicons-dismiss"></span>'.__('Delete Image' , 'wp_minapp').'</button>
                          </div>';
				break;
			case 'radio' :
				$html = '<ul class="radio-group '.$class.'">';
				foreach ($select_options as $option) {
					$html .= sprintf('<li><label><input type="radio" %1$s value="%2$s" name="%3$s"  >%4$s</label></li>' , $option['value'] == $value  ? '  checked' : '' , $option['value'] , $name , $option['label']);
				}
				$html .= '</ul>';
				break;
			case ('editor'):
				wp_editor( $value , $name , array(
					'wpautop'       => true,
					'media_buttons' => true,
					'textarea_rows' => 10,
					'teeny'         => false,
				) );
				break;
		}


		$html .= '<p class="description">'.$description.'</p>';
		return $html;
	}
}