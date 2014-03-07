<?php

if( ! defined("STB_VERSION") ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit;
}

/**
* Get the box options for box with given ID.
*
* @param int $id
*
* @return array Array of box options
*/
function stb_get_box_options( $id )
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
		'test_mode' => 0,
		'auto_hide' => 0
	);
	
	$opts = get_post_meta($id, 'stb_options', true);

	return wp_parse_args($opts, $defaults);
}