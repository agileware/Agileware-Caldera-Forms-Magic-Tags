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
	/**
	 * The callback prefix
	 */
	const PREFIX = 'agileware_magic_tags_callback_';

	// Magic tags goes to here
	/**
	 * @var array
	 */
	static $tags = [];

	/**
	 * Agileware_caldera_forms_magic_tags_Manager constructor.
	 */
	public function __construct() {
		require plugin_dir_path( dirname( __FILE__ ) ) . 'master_helper_functions.php';
		require plugin_dir_path( dirname( __FILE__ ) ) . 'custom_helper_functions.php';

		$this->read_tags();
	}

	// Hook in cf get magic tags

	/**
	 * @param $tags
	 *
	 * @return mixed
	 */
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

	// Hook in cf do magic tag

	/**
	 * @param $value
	 * @param $caller
	 *
	 * @return mixed
	 */
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


	/**
	 * Loop through tags directory and parse the tag name, record its callback
	 */
	private function read_tags() {
		// TODO loop the tags directory
		foreach ( scandir( plugin_dir_path( dirname( __FILE__ ) ) . '/tags' ) as $filename ) {
			if ( $this->check_and_require_file( $filename ) ) {
				$this->register_tag_from_filename( $filename );
			}
		}
	}

	/**
	 * @param string $filename
	 */
	private function register_tag_from_filename( $filename = '' ) {
		// TODO register tag name and its callback function
		$file_info     = pathinfo( $filename );
		$callback_part = $this->replace_with_underscore( $file_info['filename'] );

		$tag                = str_replace( '.', ':', $file_info['filename'] );
		$callback           = self::PREFIX . $callback_part;
		self::$tags[ $tag ] = $callback;
	}

	/**
	 * @param $string
	 *
	 * @return mixed
	 */
	private function replace_with_underscore( $string ) {
		return str_replace( [ '-', '.' ], '_', $string );
	}

	/**
	 * @param string $filename
	 * @param string $directory
	 *
	 * @return bool
	 */
	private function check_and_require_file( $filename = '', $directory = 'tags' ) {
		// TODO require the file under plugin directory
		$path      = plugin_dir_path( dirname( __FILE__ ) ) . '/' . $directory . '/' . $filename;
		$file_info = pathinfo( $filename );
		if ( is_file( $path ) &&
		     $file_info['extension'] == 'php' &&
		     count( explode( '.', $file_info['filename'] ) ) == 2 ) {
			require $path;

			return true;
		}

		return false;
	}
}