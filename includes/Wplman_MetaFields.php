<?php
include dirname( __FILE__ ) . '/library/class-wplman-meta-fields-generator.php';

$shortlink_meta_args =
	array(
		'post_type'         => 'shortlink',
		'meta_box_title'    => __('Short link data', WPLMAN_TEXTDOMAIN),
		'fields'            => array(
			array(
				'label'     => __( 'Description', WPLMAN_TEXTDOMAIN),
				'id'        => 'description',
				'type'      => 'textarea',
				'help_text' => __( 'This text only show in admin area', WPLMAN_TEXTDOMAIN)
			),
			array(
				'label'     => __( 'Destination URL', WPLMAN_TEXTDOMAIN),
				'id'        => 'target_url',
				'type'      => 'url',
				'help_text' => 'redirect user to this link',
				'required'  => true,
			),
			array(
				'label'     => __( 'Short link slug', WPLMAN_TEXTDOMAIN),
				'id'        => 'slug',
				'type'      => 'text',
				'required'  => true,
			),
			array(
				'label'     => __( 'Target', WPLMAN_TEXTDOMAIN),
				'id'        => 'target',
				'type'      => 'radio',
				'values'    => array(
					'blank'     => __('Open in new tab', WPLMAN_TEXTDOMAIN ),
					'none'      => __('Open in same tab', WPLMAN_TEXTDOMAIN ),
				),
				'default' => 'blank',
			),
			array(
				'label'     => __( 'Redirect Type', WPLMAN_TEXTDOMAIN),
				'id'        => 'redirect_type',
				'type'      => 'radio',
				'values'    => array(
					'301'     => __('Permanent 301', WPLMAN_TEXTDOMAIN ),
					'302'     => __('Temporary 302', WPLMAN_TEXTDOMAIN ),
				),
				'default' => '301',
			),
			array(
				'label'     => __( 'Nofollow attribute', WPLMAN_TEXTDOMAIN),
				'id'        => 'nofollow',
				'type'      => 'radio',
				'values'    => array(
					'yes'     => __('Add Nofollow attribute', WPLMAN_TEXTDOMAIN ),
					'no'      => __('Do not add Nofollow attribute', WPLMAN_TEXTDOMAIN ),
				),
				'default' => 'yes',
			),
			array(
				'label'     => __( 'Hits', WPLMAN_TEXTDOMAIN),
				'id'        => 'hits',
				'type'      => 'number',
				'readonly'  => true,
				'default'   => 0
			),
		)
);
$shortlink_meta = new Wplman_Meta_Fields_Generator( $shortlink_meta_args );