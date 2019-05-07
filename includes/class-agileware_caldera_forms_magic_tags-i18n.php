<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://agileware.com.au/
 * @since      1.0.0
 *
 * @package    Agileware_caldera_forms_magic_tags
 * @subpackage Agileware_caldera_forms_magic_tags/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Agileware_caldera_forms_magic_tags
 * @subpackage Agileware_caldera_forms_magic_tags/includes
 * @author     Agileware <support@agileware.com.au>
 */
class Agileware_caldera_forms_magic_tags_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'agileware_caldera_forms_magic_tags',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
