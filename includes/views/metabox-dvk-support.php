<?php 
if( ! defined("STB_VERSION") ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit;
} ?>
<p><?php printf( __( 'Please use the <a href="%s">plugin support forums</a> on WordPress.org.', 'scroll-triggered-boxes' ), 'http://wordpress.org/support/plugin/scroll-triggered-boxes/' ); ?></p>

