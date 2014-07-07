<?php
if( ! defined("STB_VERSION") ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit;
}

class STB_Admin {

	/**
	 * @var string
	 */
	private $plugin_file = '';

	public function __construct() {

		$this->plugin_file = plugin_basename( STB_PLUGIN_FILE );

		// action hooks
		add_action( 'init', array( $this, 'load_textdomain' ) );

		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'load_assets' ) );

		add_action( 'save_post', array( $this, 'save_meta_options' ), 20 );
		add_action( 'trashed_post', array( $this, 'flush_rules') );
		add_action( 'untrashed_post', array( $this, 'flush_rules') );

		add_filter( 'plugin_action_links', array( $this, 'add_plugin_settings_link' ), 10, 2 );
		add_filter( 'plugin_row_meta', array( $this, 'add_plugin_meta_links'), 10, 2 );

		// filter hooks
		add_filter( 'tiny_mce_before_init', array($this, 'tinymce_init') );
	}

	/**
	 * Load the plugin textdomain
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'scroll-triggered-boxes', false, dirname( plugin_basename( STB_PLUGIN_FILE ) ) . '/languages/' );
	}
	
	public function tinymce_init($args) {

		if( get_post_type() !== 'scroll-triggered-box') {
			return $args;
		}

		$args['setup'] = 'function(ed) { if(typeof STB === \'undefined\') { return; } ed.onInit.add(STB.onTinyMceInit); }';

		return $args;
	}

	public function load_assets() {
		if ( get_post_type() !== 'scroll-triggered-box' ) {
			return;
		}

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
		if ( !isset( $_POST['stb_options_nonce'] ) || ! wp_verify_nonce( $_POST['stb_options_nonce'], 'stb_options' ) ) {
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

		$post = get_post( $post_id );
		$opts = $_POST['stb'];
		unset( $_POST['stb'] );

		// sanitize settings
		$opts['css']['width'] = absint( sanitize_text_field( $opts['css']['width'] ) );
		$opts['css']['border_width'] = absint( sanitize_text_field( $opts['css']['border_width'] ) );
		$opts['cookie'] = absint( sanitize_text_field( $opts['cookie'] ) );
		$opts['trigger_percentage'] = absint( sanitize_text_field( $opts['trigger_percentage'] ) );
		$opts['trigger_element'] = sanitize_text_field( $opts['trigger_element'] );

		// save box settings
		update_post_meta( $post_id, 'stb_options', $opts );

		$this->flush_rules();
	}

	/**
	 * Add the settings link to the Plugins overview
	 * @param array $links
	 * @return array
	 */
	public function add_plugin_settings_link( $links, $file )
	{
		if( $file !== $this->plugin_file ) {
			return $links;
		}

		$settings_link = '<a href="' . admin_url( 'edit.php?post_type=scroll-triggered-box' ) . '">'. __( 'Boxes' ) . '</a>';
		array_unshift( $links, $settings_link );
		return $links;
	}

	/**
	 * Adds meta links to the plugin in the WP Admin > Plugins screen
	 *
	 * @param array $links
	 * @param string $file
	 *
	 * @return array
	 */
	public function add_plugin_meta_links( $links, $file ) {
		if( $file !== $this->plugin_file ) {
			return $links;
		}

		$links[] = '<a href="http://wordpress.org/plugins/scroll-triggered-boxes/faq/">FAQ</a>';
		return $links;
	}

	/**
	* Flush all box rules
	*
	* Loops through all published boxes and fills the rules option
	*/
	public function flush_rules() {

		// get all published boxes
		$boxes = get_posts(
			array(
				'post_type' => 'scroll-triggered-box',
				'post_status' => 'publish'
			)
		);

		// setup empty array of rules
		$rules = array();

		// fill rules array
		if( $boxes && is_array( $boxes ) ) {

			foreach( $boxes as $box ) {
				// get box meta data
				$box_meta = get_post_meta( $box->ID, 'stb_options', true );

				// add box rules to all rules
				$rules[ $box->ID ] = $box_meta['rules'];

			}

		}

		update_option( 'stb_rules', $rules );
	}

}
