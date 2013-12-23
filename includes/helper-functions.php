<?php

if ( ! defined( 'ABSPATH' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	header( 'X-Robots-Tag: noindex' );
	exit;
}


function stb_get_box_options($id)
{
	static $defaults = array(
		'css' => array(
			'background_color' => '',
			'color' => '',
			'width' => '',
			'border_color' => '',
			'border_width' => '',
			'position' => 'bottom-right'
		),
		'rules' => array(
			array('condition' => '', 'value' => '')
		),
		'cookie' => 0,
		'trigger' => 'percentage',
		'trigger_percentage' => 65,
		'trigger_element' => '',
		'animation' => 'fade',
		'test_mode' => 0
	);
	
	$opts = get_post_meta($id, 'stb_options', true);

	return wp_parse_args($opts, $defaults);
}