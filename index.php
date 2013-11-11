<?php
/*
Plugin Name: Scroll Triggered Boxes
Version: 1.0.1
Plugin URI: http://codetothechase.com/
Description: Add beautiful scroll triggered boxes to your WordPress website with ease.
Author: Danny van Kooten
Author URI: http://codetothechase.com/
Text Domain: scroll-triggered-boxes
Domain Path: /languages/
License: GPL v3

Scroll Triggered Boxes Plugin
Copyright (C) 2013, Danny van Kooten, hi@dannyvankooten.com

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

if ( ! defined( 'ABSPATH' ) ) exit;

define("STB_VERSION", "1.0.1");
define("STB_PLUGIN_DIR", plugin_dir_path(__FILE__)); 
define("STB_PLUGIN_URL", plugins_url( '/' , __FILE__ ));

// FRONTEND + BACKEND
require STB_PLUGIN_DIR . 'includes/class-stb.php';
require STB_PLUGIN_DIR . 'includes/helper-functions.php';
new STB();

if(!is_admin()) {

	// FRONTEND
	require STB_PLUGIN_DIR . 'includes/class-frontend.php';
	new STB_Frontend();

} elseif(!defined("DOING_AJAX") || !DOING_AJAX) {
	
	// BACKEND (NOT AJAX)
	require STB_PLUGIN_DIR . 'includes/class-admin.php';
	new STB_Admin();

} else {
	// BACKEND (AJAX)

}