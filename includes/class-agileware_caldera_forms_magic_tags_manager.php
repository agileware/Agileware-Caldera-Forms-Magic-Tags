<?php

/**
 * Out put the content of magic tags
 *
 * @link       https://agileware.com.au/
 * @since      1.0.0
 *
 * @package    Agileware_caldera_forms_magic_tags
 * @subpackage Agileware_caldera_forms_magic_tags/includes
 */

/**
 * Out put the content of magic tags
 *
 *
 * @since      1.0.0
 * @package    Agileware_caldera_forms_magic_tags
 * @subpackage Agileware_caldera_forms_magic_tags/includes
 * @author     Agileware <support@agileware.com.au>
 */
class Agileware_caldera_forms_magic_tags_Manager {
	const PREFIX = 'agileware_magic_tags_callback_';

	// Magic tags goes to here
	static $tags = [];

	/**
	 * Agileware_caldera_forms_magic_tags_Manager constructor.
	 */
	public function __construct() {
		require plugin_dir_path( dirname( __FILE__ ) ) . 'tags_collection.php';
	}

	public function register_tags( $tags ) {
		$magic_tags = $tags['system']['tags'];

		foreach ( self::$tags as $tag => $callback ) {
			$magic_tags[] = $tag;
		}

		$tags['system'] = [
			'type' => __( 'System Tags', 'caldera-form' ),
			'tags' => $magic_tags,
			'wrap' => array( '{', '}' )
		];
		return $tags;
	}

	public function dispatch_callback( $value, $caller ) {
		$tag = str_replace( [ '{', '}' ], '', $caller );

		$callback = self::$tags[ $tag ];
		if ( is_callable( $callback ) ) {
			// Autoloading will be invoked to load the class "ClassName" if it's not
			// yet defined, and PHP will check that the class has a method
			// "someStaticMethod". Note that is_callable() will NOT verify that the
			// method can safely be executed in static context.

			return call_user_func( $callback, $value );
		}

		return $value;
	}
}