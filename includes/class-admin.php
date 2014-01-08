<?php
if ( ! defined( 'ABSPATH' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	header( 'X-Robots-Tag: noindex' );
	exit;
}

class STB_Admin {

	public function __construct() {
		// action hooks
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'load_assets' ) );
		add_action( 'save_post', array( $this, 'save_meta_options' ) );

		// filter hooks
		add_filter( 'tiny_mce_before_init', array($this, 'tinymce_init') );
	}
	
	public function tinymce_init($args) {
		if(get_post_type() != 'scroll-triggered-box') { return $args; }

		$args['setup'] = 'function(ed) { if(typeof STB === \'undefined\') { return; } ed.onInit.add(STB.onTinyMceInit); }';

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

		add_meta_box(
			'stb-dvk-info-support',
			__( 'Need support?', 'scroll-triggered-boxes' ),
			array( $this, 'show_dvk_info_support' ),
			'scroll-triggered-box',
			'side'
		);

		add_meta_box(
			'stb-dvk-info-donate',
			__( 'Donate $10, $20 or $50', 'scroll-triggered-boxes' ),
			array( $this, 'show_dvk_info_donate' ),
			'scroll-triggered-box',
			'side'
		);

		add_meta_box(
			'stb-dvk-info-links',
			__( 'About the developer', 'scroll-triggered-boxes' ),
			array( $this, 'show_dvk_info_links' ),
			'scroll-triggered-box',
			'side'
		);
	}

	public function show_meta_options( $post, $metabox ) {
		$opts = stb_get_box_options($post->ID);
		include STB_PLUGIN_DIR . 'includes/views/metabox-options.php';
	}

	public function show_dvk_info_donate( $post, $metabox ) {
		include STB_PLUGIN_DIR . 'includes/views/metabox-dvk-donate.php';
	}

	public function show_dvk_info_support( $post, $metabox ) {
		include STB_PLUGIN_DIR . 'includes/views/metabox-dvk-support.php';
	}

	public function show_dvk_info_links( $post, $metabox ) {
		include STB_PLUGIN_DIR . 'includes/views/metabox-dvk-links.php';
	}


	/**
	* Saves box options and rules
	*/
	public function save_meta_options( $post_id ) {		
		// Verify that the nonce is set and valid.
		if ( !isset( $_POST['stb_options_nonce'] ) || !wp_verify_nonce( $_POST['stb_options_nonce'], 'stb_options' ) ) {
			return $post_id;
		}

		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
        	return $post_id;
		}

    	if ( defined( 'DOING_CRON' ) && DOING_CRON ) {
       	 	return $post_id;
    	}

    	if ( wp_is_post_revision( $post_id ) ) {
        	return $post_id; 
    	}

		// can user edit this post?
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}

		$post = get_post($post_id);
		$opts = $_POST['stb'];

		// sanitize settings
		$opts['css']['width'] = absint($opts['css']['width']);
		$opts['css']['border_width'] = absint($opts['css']['border_width']);
		$opts['cookie'] = absint($opts['cookie']);
		$opts['trigger_percentage'] = absint($opts['trigger_percentage']);
		$opts['trigger_element'] = trim($opts['trigger_element']);

		// store rules in option
		$rules = get_option('stb_rules', array());

		// only save box rules if the status is publish
		if($post->post_status != 'publish') {
			if(isset($rules[$post_id])) {
				unset($rules[$post_id]);
			}
		} else {
			$rules[$post_id] = $opts['rules'];
		}
		
		update_option('stb_rules', $rules);

		// save box settings
		update_post_meta( $post_id, 'stb_options', $opts );
	}


}
