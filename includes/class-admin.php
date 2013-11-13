<?php

class STB_Admin {

	public function __construct() {
		// action hooks
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'load_assets' ) );
		add_action( 'save_post', array( $this, 'save_meta_options' ) );
		//add_action( 'admin_menu', array( $this, 'add_menu_items') );

		// filter hooks
		add_filter( 'tiny_mce_before_init', array($this, 'tinymce_init') );
	}
	
	/*public function add_menu_items()
	{
		add_submenu_page( 'edit.php?post_type=scroll-triggered-box', "Settings - Scroll Triggered Boxes", "Settings", 'manage_options', 'settings', array($this, 'show_settings_page') );
	}*/

	public function tinymce_init($args) {
		if(get_post_type() != 'scroll-triggered-box') { return $args; }

		$args['setup'] = 'function(ed) { ed.onInit.add(STB.onTinyMceInit); }';

		return $args;
	}

	public function load_assets() {
		if ( get_post_type() != 'scroll-triggered-box' ) { return; }

		// load stylesheets
		wp_enqueue_style( 'scroll-triggered-boxes', STB_PLUGIN_URL . 'assets/css/admin-styles.css', array( 'wp-color-picker' ), STB_VERSION );

		// load scripts
		wp_enqueue_script( 'scroll-triggered-boxes', STB_PLUGIN_URL . 'assets/js/admin-script.js', array( 'jquery', 'wp-color-picker' ), STB_VERSION, true );
	}

	public function add_meta_boxes() {
		add_meta_box(
			'stb-options',
			__( 'Box Options', 'scroll-triggered-boxes' ),
			array( $this, 'show_meta_options' ),
			'scroll-triggered-box',
			'normal',
			'core'
		);
	}

	public function show_meta_options( $post, $metabox ) {
		$opts = stb_get_box_options($post->ID);
		include STB_PLUGIN_DIR . 'includes/views/metabox-options.php';
	}

	public function save_meta_options( $post_id ) {		
		// Verify that the nonce is set and valid.
		if ( !isset( $_POST['stb_options_nonce'] ) || !wp_verify_nonce( $_POST['stb_options_nonce'], 'stb_options' ) )
			return $post_id;

		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return $post_id;

		// can user edit this post?
		if ( ! current_user_can( 'edit_post', $post_id ) )
			return $post_id;

		$opts = $_POST['stb'];

		// store rules in option
		$rules = get_option('stb_rules', array());
		$rules[$post_id] = $opts['rules'];
		update_option('stb_rules', $rules);

		// save box settings
		update_post_meta( $post_id, 'stb_options', $opts );
	}

}
