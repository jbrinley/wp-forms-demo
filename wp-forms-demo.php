<?php
/*
Plugin Name: WP Forms Demo
Plugin URI: https://github.com/jbrinley/wp-forms-demo
Description: Demoing WP Forms
Author: Flightless
Author URI: http://flightless.us/
Version: 0.1
*/
/*
Copyright (c) 2013 Flightless, Inc. http://flightless.us/

Permission is hereby granted, free of charge, to any person obtaining
a copy of this software and associated documentation files (the
"Software"), to deal in the Software without restriction, including
without limitation the rights to use, copy, modify, merge, publish,
distribute, sublicense, and/or sell copies of the Software, and to
permit persons to whom the Software is furnished to do so, subject to
the following conditions:

The above copyright notice and this permission notice shall be included
in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY
CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,
TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/


if ( !function_exists('WP_FormsDemo_load') ) { // Play nice
	/**
	 * Load all the plugin files and initialize appropriately
	 *
	 * @return void
	 */
	function WP_FormsDemo_load() {
		spl_autoload_register('wp_forms_demo_autoloader');
		add_action( 'wp_enqueue_scripts', 'wp_forms_demo_enqueue_scripts' );
		add_action('wp_forms_register', 'wp_forms_demo_register_forms');
		add_shortcode('wp_forms_demo', 'wp_forms_demo_shortcode');
	}

	/**
	 * Autoload callback
	 *
	 * @param string $class_name
	 * @return void
	 */
	function wp_forms_demo_autoloader( $class_name ) {
		if ( strpos($class_name, 'WP_FormsDemo_') === 0 && file_exists(dirname(__FILE__)."/classes/$class_name.php") ) {
			include_once("classes/$class_name.php");
		}
	}

	/**
	 * Include CSS/JS resources for the plugin
	 *
	 * @return void
	 */
	function wp_forms_demo_enqueue_scripts() {
		wp_enqueue_style('wp-forms-demo', plugins_url('resources/forms.css', __FILE__));
	}

	/**
	 * Register the forms that we'll define
	 *
	 * @return void
	 */
	function wp_forms_demo_register_forms() {
		$definitions = new WP_FormsDemo_Definitions();
		wp_register_form('kitchen-sink', array($definitions, 'build_form'));
		wp_register_form('kitchen-table', array($definitions, 'build_form'));
	}

	/**
	 * Handle the wp_forms_demo shortcode
	 *
	 * @param $atts
	 * @return string
	 */
	function wp_forms_demo_shortcode( $atts ) {
		if ( !is_singular() ) {
			return sprintf('<p><a href="%s">%s</a></p>', get_permalink(), __('View the form'));
		}
		$atts = shortcode_atts(
			array(
				'id' => '',
			),
			$atts
		);
		if ( empty($atts['id']) ) {
			return '';
		}
		return wp_get_form($atts['id'])->render();
	}

	// Fire it up!
	WP_FormsDemo_load();
}
