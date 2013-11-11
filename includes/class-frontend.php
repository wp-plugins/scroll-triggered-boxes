<?php

class STB_Frontend {
	private $matched_box_ids = array();

	public function __construct() {
		add_action( 'wp', array( $this, 'filter_boxes' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'load_assets' ) );
		add_action( 'wp_footer', array( $this, 'load_boxes' ), 1 );
	}

	public function filter_boxes() {
		$rules = get_option( 'stb_rules' );

		if ( !$rules ) { return; }

		global $post;

		foreach ( $rules as $box_id => $box_rules ) {

			// check if cookie is set for this box
			if ( !current_user_can( 'edit_post', $box_id ) && isset( $_COOKIE['stb_box_' . $box_id] ) ) {
				continue;
			}

			$matched = false;

			foreach ( $box_rules as $rule ) {

				$condition = $rule['condition'];
				$value = trim( $rule['value'] );

				if ( $condition != 'manual' ) {
					$value = array_filter( array_map( 'trim', explode( ',', $value ) ) );
				}

				switch ( $condition ) {
				case 'everywhere';
					$matched = true;
					break;

				case 'is_post_type':
					$matched = in_array( get_post_type(), $value );
					break;

				case 'is_single':
					$matched = is_single( $value );
					break;

				case 'is_page':
					$matched = is_page( $value );
					break;

				case 'manual':
					// eval for now...
					$matched = eval( "return (" . $value . ");" );
					break;

				}
			}

			// if matched, box should be loaded on this page
			if ( $matched ) {
				$this->matched_box_ids[] = $box_id;
			}

		}

	}

	public function load_assets() {
		if ( empty( $this->matched_box_ids ) ) { return; }

		// load stylesheets
		wp_enqueue_style( 'scroll-triggered-boxes', STB_PLUGIN_URL . 'assets/css/styles.css', array(), STB_VERSION );

		// load scripts
		wp_enqueue_script( 'scroll-triggered-boxes', STB_PLUGIN_URL . 'assets/js/script.js', array( 'jquery' ), STB_VERSION, true );
	}

	public function load_boxes() {
		if ( empty( $this->matched_box_ids ) ) { return; }

		foreach ( $this->matched_box_ids as $box_id ) {

			$box = get_post( $box_id );

			if ( !$box ) { continue; }

			$opts = stb_get_box_options( $box->ID );
			$css = $opts['css'];
			$content = $box->post_content;

			// run filters
			$content = wpautop( $content );
			$content = do_shortcode( $content );
			$content = shortcode_unautop($content);
			$content = apply_filters( 'stb_content', $content, $box );

?>
			<style type="text/css">
				#stb-<?php echo $box->ID; ?> {
					padding: 25px;
					background: <?php echo ( !empty( $css['background_color'] ) ) ? $css['background_color'] : 'white'; ?>;
					<?php if ( !empty( $css['color'] ) ) { ?>color: <?php echo $css['color']; ?>;<?php } ?>
					<?php if ( !empty( $css['border_color'] ) && !empty( $css['border_width'] ) ) { ?>border: <?php echo $css['border_width'] . 'px' ?> solid <?php echo $css['border_color']; ?>;<?php } ?>
					width: <?php echo ( !empty( $css['width'] ) ) ? $css['width'] . 'px': 'auto'; ?>;
				}

				@media(max-width: <?php echo ( !empty( $css['width'] ) ) ? $css['width'] : '480'; ?>px) {
					#stb-<?php echo $box->ID; ?> { display: none !important; }
				}
			</style>
			<div class="scroll-triggered-box stb stb-<?php echo esc_attr( $opts['css']['position'] ); ?>" id="stb-<?php echo $box->ID; ?>" style="display: none;" <?php
			?> data-box-id="<?php echo esc_attr( $box->ID ); ?>" data-trigger="<?php echo esc_attr( $opts['trigger'] ); ?>" data-trigger-percentage="<?php echo esc_attr( $opts['trigger_percentage'] ); ?>" data-trigger-element="<?php echo esc_attr( $opts['trigger_element'] ); ?>" data-cookie="<?php echo esc_attr( $opts['cookie'] ); ?>">

				<div class="stb-content"><?php echo $content; ?></div>
				<span class="stb-close">&times;</span>
			</div>
			<?php
		}
	}


}
