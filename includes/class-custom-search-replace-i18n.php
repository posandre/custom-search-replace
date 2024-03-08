<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://index.html
 * @since      1.0.0
 *
 * @package    Custom_Search_Replace
 * @subpackage Custom_Search_Replace/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Custom_Search_Replace
 * @subpackage Custom_Search_Replace/includes
 * @author     Andrii Postoliuk <an.postoliuk@gmail.com>
 */
class Custom_Search_Replace_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'custom-search-replace',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
