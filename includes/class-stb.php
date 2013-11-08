<?php

class STB
{
	public function __construct()
	{
		add_action('init', array($this, 'register_post_type'));
	}

	public function register_post_type()
	{
		$args = array(
      		'public' => false,
      		'labels'  =>  array(
			    'name'               => 'Scroll Triggered Boxes',
			    'singular_name'      => 'Scroll Triggered Box',
			    'add_new'            => 'Add New',
			    'add_new_item'       => 'Add New Box',
			    'edit_item'          => 'Edit Box',
			    'new_item'           => 'New Box',
			    'all_items'          => 'All Boxes',
			    'view_item'          => 'View Box',
			    'search_items'       => 'Search Boxes',
			    'not_found'          => 'No Boxes found',
			    'not_found_in_trash' => 'No Boxes found in Trash',
			    'parent_item_colon'  => '',
			    'menu_name'          => 'Scroll Triggered Boxes'
			  ),
      		'show_ui' => true,
      		'menu_position' => 100
    	);

    	register_post_type( 'scroll-triggered-box', $args );
	}
}
