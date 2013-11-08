<?php

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
		'trigger_percentage' => 0,
		'trigger_element' => ''
	);
	
	$opts = get_post_meta($id, 'stb_options', true);

	return wp_parse_args($opts, $defaults);
}