<?php //phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * Handles server-side registration and use of all blocks and plugins available in Jetpack for the block editor, aka Gutenberg.
 * Works in tandem with client-side block registration via `index.json`
 *
 * @package Jetpack
 */

use Automattic\Jetpack\Constants;

/**
 * Wrapper function to safely register a gutenberg block type
 *
 * @param string $slug Slug of the block.
 * @param array  $args Arguments that are passed into register_block_type.
 *
 * @see register_block_type
 *
 * @since 6.7.0
 *
 * @return WP_Block_Type|false The registered block type on success, or false on failure.
 */
function jetpack_register_block( $slug, $args = array() ) {
	if ( 0 !== strpos( $slug, 'jetpack/' ) && ! strpos( $slug, '/' ) ) {
		_doing_it_wrong( 'jetpack_register_block', 'Prefix the block with jetpack/ ', '7.1.0' );
		$slug = 'jetpack/' . $slug;
	}

	// Checking whether block is registered to ensure it isn't registered twice.
	if ( Jetpack_Gutenberg::is_registered( $slug ) ) {
		return false;
	}

	return register_block_type( $slug, $args );
}

/**
 * Helper function to register a Jetpack Gutenberg plugin
 *
 * @deprecated 7.1.0 Use Jetpack_Gutenberg::set_extension_available() instead
 *
 * @param string $slug Slug of the plugin.
 *
 * @since 6.9.0
 *
 * @return void
 */
function jetpack_register_plugin( $slug ) {
	_deprecated_function( __FUNCTION__, '7.1', 'Jetpack_Gutenberg::set_extension_available' );

	Jetpack_Gutenberg::register_plugin( $slug );
}

/**
 * Set the reason why an extension (block or plugin) is unavailable
 *
 * @deprecated 7.1.0 Use Jetpack_Gutenberg::set_extension_unavailable() instead
 *
 * @param string $slug Slug of the block.
 * @param string $reason A string representation of why the extension is unavailable.
 *
 * @since 7.0.0
 *
 * @return void
 */
function jetpack_set_extension_unavailability_reason( $slug, $reason ) {
	_deprecated_function( __FUNCTION__, '7.1', 'Jetpack_Gutenberg::set_extension_unavailable' );

	Jetpack_Gutenberg::set_extension_unavailability_reason( $slug, $reason );
}

/**
 * General Gutenberg editor specific functionality
 */
class Jetpack_Gutenberg {

	/**
	 * Only these extensions can be registered. Used to control availability of beta blocks.
	 *
	 * @var array Extensions whitelist
	 */
	private static $extensions = array();

	/**
	 * Keeps track of the reasons why a given extension is unavailable.
	 *
	 * @var array Extensions availability information
	 */
	private static $availability = array();

	/**
	 * Prepend the 'jetpack/' prefix to a block name
	 *
	 * @param string $block_name The block name.
	 *
	 * @return string The prefixed block name.
	 */
	private static function prepend_block_prefix( $block_name ) {
		return 'jetpack/' . $block_name;
	}

	/**
	 * Remove the 'jetpack/' or jetpack-' prefix from an extension name
	 *
	 * @param string $extension_name The extension name.
	 *
	 * @return string The unprefixed extension name.
	 */
	private static function remove_extension_prefix( $extension_name ) {
		if ( wp_startswith( $extension_name, 'jetpack/' ) || wp_startswith( $extension_name, 'jetpack-' ) ) {
			return substr( $extension_name, strlen( 'jetpack/' ) );
		}
		return $extension_name;
	}

	/**
	 * Whether two arrays share at least one item
	 *
	 * @param array $a An array.
	 * @param array $b Another array.
	 *
	 * @return boolean True if $a and $b share at least one item
	 */
	protected static function share_items( $a, $b ) {
		return count( array_intersect( $a, $b ) ) > 0;
	}

	/**
	 * Register a block
	 *
	 * @deprecated 7.1.0 Use jetpack_register_block() instead
	 *
	 * @param string $slug Slug of the block.
	 * @param array  $args Arguments that are passed into register_block_type().
	 */
	public static function register_block( $slug, $args ) {
		_deprecated_function( __METHOD__, '7.1', 'jetpack_register_block' );

		jetpack_register_block( 'jetpack/' . $slug, $args );
	}

	/**
	 * Register a plugin
	 *
	 * @deprecated 7.1.0 Use Jetpack_Gutenberg::set_extension_available() instead
	 *
	 * @param string $slug Slug of the plugin.
	 */
	public static function register_plugin( $slug ) {
		_deprecated_function( __METHOD__, '7.1', 'Jetpack_Gutenberg::set_extension_available' );

		self::set_extension_available( $slug );
	}

	/**
	 * Register a block
	 *
	 * @deprecated 7.0.0 Use jetpack_register_block() instead
	 *
	 * @param string $slug Slug of the block.
	 * @param array  $args Arguments that are passed into the register_block_type.
	 * @param array  $availability array containing if a block is available and the reason when it is not.
	 */
	public static function register( $slug, $args, $availability ) {
		_deprecated_function( __METHOD__, '7.0', 'jetpack_register_block' );

		if ( isset( $availability['available'] ) && ! $availability['available'] ) {
			self::set_extension_unavailability_reason( $slug, $availability['unavailable_reason'] );
		} else {
			self::register_block( $slug, $args );
		}
	}

	/**
	 * Set a (non-block) extension as available
	 *
	 * @param string $slug Slug of the extension.
	 */
	public static function set_extension_available( $slug ) {
		self::$availability[ self::remove_extension_prefix( $slug ) ] = true;
	}

	/**
	 * Set the reason why an extension (block or plugin) is unavailable
	 *
	 * @param string $slug Slug of the extension.
	 * @param string $reason A string representation of why the extension is unavailable.
	 * @param array  $details A free-form array containing more information on why the extension is unavailable.
	 */
	public static function set_extension_unavailable( $slug, $reason, $details = array() ) {
		if (
			// Extensions that require a plan may be eligible for upgrades.
			'missing_plan' === $reason
			/**
			 * Filter 'jetpack_block_editor_enable_upgrade_nudge' with `true` to enable or `false`
			 * to disable paid feature upgrade nudges in the block editor.
			 *
			 * @since 7.7.0
			 *
			 * @param boolean
			 */
			&& ! apply_filters( 'jetpack_block_editor_enable_upgrade_nudge', false )
		) {
			// The block editor may apply an upgrade nudge if `missing_plan` is the reason.
			// Add a descriptive suffix to disable behavior but provide informative reason.
			$reason .= '__nudge_disabled';
		}

		self::$availability[ self::remove_extension_prefix( $slug ) ] = array(
			'reason'  => $reason,
			'details' => $details,
		);
	}

	/**
	 * Set the reason why an extension (block or plugin) is unavailable
	 *
	 * @deprecated 7.1.0 Use set_extension_unavailable() instead
	 *
	 * @param string $slug Slug of the extension.
	 * @param string $reason A string representation of why the extension is unavailable.
	 */
	public static function set_extension_unavailability_reason( $slug, $reason ) {
		_deprecated_function( __METHOD__, '7.1', 'Jetpack_Gutenberg::set_extension_unavailable' );

		self::set_extension_unavailable( $slug, $reason );
	}

	/**
	 * Set up a whitelist of allowed block editor extensions
	 *
	 * @return void
	 */
	public static function init() {
		if ( ! self::should_load() ) {
			return;
		}

		/**
		 * Alternative to `JETPACK_BETA_BLOCKS`, set to `true` to load Beta Blocks.
		 *
		 * @since 6.9.0
		 *
		 * @param boolean
		 */
		if ( apply_filters( 'jetpack_load_beta_blocks', false ) ) {
			Constants::set_constant( 'JETPACK_BETA_BLOCKS', true );
		}

		/**
		 * Filter the whitelist of block editor extensions that are available through Jetpack.
		 *
		 * @since 7.0.0
		 *
		 * @param array
		 */
		self::$extensions = apply_filters( 'jetpack_set_available_extensions', self::get_available_extensions() );

		/**
		 * Filter the whitelist of block editor plugins that are available through Jetpack.
		 *
		 * @deprecated 7.0.0 Use jetpack_set_available_extensions instead
		 *
		 * @since 6.8.0
		 *
		 * @param array
		 */
		self::$extensions = apply_filters( 'jetpack_set_available_blocks', self::$extensions );

		/**
		 * Filter the whitelist of block editor plugins that are available through Jetpack.
		 *
		 * @deprecated 7.0.0 Use jetpack_set_available_extensions instead
		 *
		 * @since 6.9.0
		 *
		 * @param array
		 */
		self::$extensions = apply_filters( 'jetpack_set_available_plugins', self::$extensions );
	}

	/**
	 * Resets the class to its original state
	 *
	 * Used in unit tests
	 *
	 * @return void
	 */
	public static function reset() {
		self::$extensions   = array();
		self::$availability = array();
	}

	/**
	 * Return the Gutenberg extensions (blocks and plugins) directory
	 *
	 * @return string The Gutenberg extensions directory
	 */
	public static function get_blocks_directory() {
		/**
		 * Filter to select Gutenberg blocks directory
		 *
		 * @since 6.9.0
		 *
		 * @param string default: '_inc/blocks/'
		 */
		return apply_filters( 'jetpack_blocks_directory', '_inc/blocks/' );
	}

	/**
	 * Checks for a given .json file in the blocks folder.
	 *
	 * @param string $preset The name of the .json file to look for.
	 *
	 * @return bool True if the file is found.
	 */
	public static function preset_exists( $preset ) {
		return file_exists( JETPACK__PLUGIN_DIR . self::get_blocks_directory() . $preset . '.json' );
	}

	/**
	 * Decodes JSON loaded from a preset file in the blocks folder
	 *
	 * @param string $preset The name of the .json file to load.
	 *
	 * @return mixed Returns an object if the file is present, or false if a valid .json file is not present.
	 */
	public static function get_preset( $preset ) {
		return json_decode(
			// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
			file_get_contents( JETPACK__PLUGIN_DIR . self::get_blocks_directory() . $preset . '.json' )
		);
	}

	/**
	 * Returns a whitelist of Jetpack Gutenberg extensions (blocks and plugins), based on index.json
	 *
	 * @return array A list of blocks: eg [ 'publicize', 'markdown' ]
	 */
	public static function get_jetpack_gutenberg_extensions_whitelist() {
		$preset_extensions_manifest = self::preset_exists( 'index' ) ? self::get_preset( 'index' ) : (object) array();

		$preset_extensions = isset( $preset_extensions_manifest->production ) ? (array) $preset_extensions_manifest->production : array();

		if ( Constants::is_true( 'JETPACK_BETA_BLOCKS' ) ) {
			$beta_extensions = isset( $preset_extensions_manifest->beta ) ? (array) $preset_extensions_manifest->beta : array();
			return array_unique( array_merge( $preset_extensions, $beta_extensions ) );
		}

		return $preset_extensions;
	}

	/**
	 * Returns a diff from a combined list of whitelisted extensions and extensions determined to be excluded
	 *
	 * @param  array $whitelisted_extensions An array of whitelisted extensions.
	 *
	 * @return array A list of blocks: eg array( 'publicize', 'markdown' )
	 */
	public static function get_available_extensions( $whitelisted_extensions = null ) {
		$exclusions             = get_option( 'jetpack_excluded_extensions', array() );
		$whitelisted_extensions = is_null( $whitelisted_extensions ) ? self::get_jetpack_gutenberg_extensions_whitelist() : $whitelisted_extensions;

		return array_diff( $whitelisted_extensions, $exclusions );
	}

	/**
	 * Get availability of each block / plugin.
	 *
	 * @return array A list of block and plugins and their availablity status
	 */
	public static function get_availability() {
		/**
		 * Fires before Gutenberg extensions availability is computed.
		 *
		 * In the function call you supply, use `jetpack_register_block()` to set a block as available.
		 * Alternatively, use `Jetpack_Gutenberg::set_extension_available()` (for a non-block plugin), and
		 * `Jetpack_Gutenberg::set_extension_unavailable()` (if the block or plugin should not be registered
		 * but marked as unavailable).
		 *
		 * @since 7.0.0
		 */
		do_action( 'jetpack_register_gutenberg_extensions' );

		$available_extensions = array();

		foreach ( self::$extensions as $extension ) {
			$is_available = self::is_registered( 'jetpack/' . $extension ) ||
			( isset( self::$availability[ $extension ] ) && true === self::$availability[ $extension ] );

			$available_extensions[ $extension ] = array(
				'available' => $is_available,
			);

			if ( ! $is_available ) {
				$reason  = isset( self::$availability[ $extension ] ) ? self::$availability[ $extension ]['reason'] : 'missing_module';
				$details = isset( self::$availability[ $extension ] ) ? self::$availability[ $extension ]['details'] : array();
				$available_extensions[ $extension ]['unavailable_reason'] = $reason;
				$available_extensions[ $extension ]['details']            = $details;
			}
		}

		return $available_extensions;
	}

	/**
	 * Check if an extension/block is already registered
	 *
	 * @since 7.2
	 *
	 * @param string $slug Name of extension/block to check.
	 *
	 * @return bool
	 */
	public static function is_registered( $slug ) {
		return WP_Block_Type_Registry::get_instance()->is_registered( $slug );
	}

	/**
	 * Check if Gutenberg editor is available
	 *
	 * @since 6.7.0
	 *
	 * @return bool
	 */
	public static function is_gutenberg_available() {
		return true;
	}

	/**
	 * Check whether conditions indicate Gutenberg Extensions (blocks and plugins) should be loaded
	 *
	 * Loading blocks and plugins is enabled by default and may be disabled via filter:
	 *   add_filter( 'jetpack_gutenberg', '__return_false' );
	 *
	 * @since 6.9.0
	 *
	 * @return bool
	 */
	public static function should_load() {
		if ( ! Jetpack::is_active() && ! Jetpack::is_development_mode() ) {
			return false;
		}

		/**
		 * Filter to disable Gutenberg blocks
		 *
		 * @since 6.5.0
		 *
		 * @param bool true Whether to load Gutenberg blocks
		 */
		return (bool) apply_filters( 'jetpack_gutenberg', true );
	}

	/**
	 * Only enqueue block assets when needed.
	 *
	 * @param string $type Slug of the block.
	 * @param array  $script_dependencies Script dependencies. Will be merged with automatically
	 *                                    detected script dependencies from the webpack build.
	 *
	 * @return void
	 */
	public static function load_assets_as_required( $type, $script_dependencies = array() ) {
		if ( is_admin() ) {
			// A block's view assets will not be required in wp-admin.
			return;
		}

		$type = sanitize_title_with_dashes( $type );
		self::load_styles_as_required( $type );
		self::load_scripts_as_required( $type, $script_dependencies );
	}

	/**
	 * Only enqueue block sytles when needed.
	 *
	 * @param string $type Slug of the block.
	 *
	 * @since 7.2.0
	 *
	 * @return void
	 */
	public static function load_styles_as_required( $type ) {
		if ( is_admin() ) {
			// A block's view assets will not be required in wp-admin.
			return;
		}

		// Enqueue styles.
		$style_relative_path = self::get_blocks_directory() . $type . '/view' . ( is_rtl() ? '.rtl' : '' ) . '.css';
		if ( self::block_has_asset( $style_relative_path ) ) {
			$style_version = self::get_asset_version( $style_relative_path );
			$view_style    = plugins_url( $style_relative_path, JETPACK__PLUGIN_FILE );
			wp_enqueue_style( 'jetpack-block-' . $type, $view_style, array(), $style_version );
		}

	}

	/**
	 * Only enqueue block scripts when needed.
	 *
	 * @param string $type Slug of the block.
	 * @param array  $dependencies Script dependencies. Will be merged with automatically
	 *                             detected script dependencies from the webpack build.
	 *
	 * @since 7.2.0
	 *
	 * @return void
	 */
	public static function load_scripts_as_required( $type, $dependencies = array() ) {
		if ( is_admin() ) {
			// A block's view assets will not be required in wp-admin.
			return;
		}

		// Enqueue script.
		$script_relative_path = self::get_blocks_directory() . $type . '/view.js';
		$script_deps_path     = JETPACK__PLUGIN_DIR . self::get_blocks_directory() . $type . '/view.deps.json';

		$script_dependencies = file_exists( $script_deps_path )
			// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
			? json_decode( file_get_contents( $script_deps_path ) )
			: array();
		$script_dependencies = array_merge( $script_dependencies, $dependencies, array( 'wp-polyfill' ) );

		if ( ( ! class_exists( 'Jetpack_AMP_Support' ) || ! Jetpack_AMP_Support::is_amp_request() ) && self::block_has_asset( $script_relative_path ) ) {
			$script_version = self::get_asset_version( $script_relative_path );
			$view_script    = plugins_url( $script_relative_path, JETPACK__PLUGIN_FILE );
			wp_enqueue_script( 'jetpack-block-' . $type, $view_script, $script_dependencies, $script_version, false );
		}

		wp_localize_script(
			'jetpack-block-' . $type,
			'Jetpack_Block_Assets_Base_Url',
			plugins_url( self::get_blocks_directory(), JETPACK__PLUGIN_FILE )
		);
	}

	/**
	 * Check if an asset exists for a block.
	 *
	 * @param string $file Path of the file we are looking for.
	 *
	 * @return bool $block_has_asset Does the file exist.
	 */
	public static function block_has_asset( $file ) {
		return file_exists( JETPACK__PLUGIN_DIR . $file );
	}

	/**
	 * Get the version number to use when loading the file. Allows us to bypass cache when developing.
	 *
	 * @param string $file Path of the file we are looking for.
	 *
	 * @return string $script_version Version number.
	 */
	public static function get_asset_version( $file ) {
		return Jetpack::is_development_version() && self::block_has_asset( $file )
			? filemtime( JETPACK__PLUGIN_DIR . $file )
			: JETPACK__VERSION;
	}

	/**
	 * Load Gutenberg editor assets
	 *
	 * @since 6.7.0
	 *
	 * @return void
	 */
	public static function enqueue_block_editor_assets() {
		if ( ! self::should_load() ) {
			return;
		}

		// Required for Analytics. See _inc/lib/admin-pages/class.jetpack-admin-page.php.
		if ( ! Jetpack::is_development_mode() && Jetpack::is_active() ) {
			wp_enqueue_script( 'jp-tracks', '//stats.wp.com/w.js', array(), gmdate( 'YW' ), true );
		}

		$rtl        = is_rtl() ? '.rtl' : '';
		$beta       = Constants::is_true( 'JETPACK_BETA_BLOCKS' ) ? '-beta' : '';
		$blocks_dir = self::get_blocks_directory();

		$editor_script = plugins_url( "{$blocks_dir}editor{$beta}.js", JETPACK__PLUGIN_FILE );
		$editor_style  = plugins_url( "{$blocks_dir}editor{$beta}{$rtl}.css", JETPACK__PLUGIN_FILE );

		$editor_deps_path = JETPACK__PLUGIN_DIR . $blocks_dir . "editor{$beta}.deps.json";
		$editor_deps      = file_exists( $editor_deps_path )
			// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
			? json_decode( file_get_contents( $editor_deps_path ) )
			: array();
		$editor_deps[] = 'wp-polyfill';

		$version = Jetpack::is_development_version() && file_exists( JETPACK__PLUGIN_DIR . $blocks_dir . 'editor.js' )
			? filemtime( JETPACK__PLUGIN_DIR . $blocks_dir . 'editor.js' )
			: JETPACK__VERSION;

		if ( method_exists( 'Jetpack', 'build_raw_urls' ) ) {
			$site_fragment = Jetpack::build_raw_urls( home_url() );
		} elseif ( class_exists( 'WPCOM_Masterbar' ) && method_exists( 'WPCOM_Masterbar', 'get_calypso_site_slug' ) ) {
			$site_fragment = WPCOM_Masterbar::get_calypso_site_slug( get_current_blog_id() );
		} else {
			$site_fragment = '';
		}

		wp_enqueue_script(
			'jetpack-blocks-editor',
			$editor_script,
			$editor_deps,
			$version,
			false
		);

		wp_localize_script(
			'jetpack-blocks-editor',
			'Jetpack_Block_Assets_Base_Url',
			plugins_url( $blocks_dir . '/', JETPACK__PLUGIN_FILE )
		);

		if ( defined( 'IS_WPCOM' ) && IS_WPCOM ) {
			$user      = wp_get_current_user();
			$user_data = array(
				'userid'   => $user->ID,
				'username' => $user->user_login,
			);
			$blog_id   = get_current_blog_id();
		} else {
			$user_data = Jetpack_Tracks_Client::get_connected_user_tracks_identity();
			$blog_id   = Jetpack_Options::get_option( 'id', 0 );
		}

		wp_localize_script(
			'jetpack-blocks-editor',
			'Jetpack_Editor_Initial_State',
			array(
				'available_blocks' => self::get_availability(),
				'jetpack'          => array( 'is_active' => Jetpack::is_active() ),
				'siteFragment'     => $site_fragment,
				'tracksUserData'   => $user_data,
				'wpcomBlogId'      => $blog_id,
			)
		);

		wp_set_script_translations( 'jetpack-blocks-editor', 'jetpack' );

		wp_enqueue_style( 'jetpack-blocks-editor', $editor_style, array(), $version );
	}

	/**
	 * Some blocks do not depend on a specific module,
	 * and can consequently be loaded outside of the usual modules.
	 * We will look for such modules in the extensions/ directory.
	 *
	 * @since 7.1.0
	 */
	public static function load_independent_blocks() {
		if ( self::should_load() ) {
			/**
			 * Look for files that match our list of available Jetpack Gutenberg extensions (blocks and plugins).
			 * If available, load them.
			 */
			foreach ( self::$extensions as $extension ) {
				$extension_file_glob = glob( JETPACK__PLUGIN_DIR . 'extensions/*/' . $extension . '/' . $extension . '.php' );
				if ( ! empty( $extension_file_glob ) ) {
					include_once $extension_file_glob[0];
				}
			}
		}
	}

	/**
	 * Get CSS classes for a block.
	 *
	 * @since 7.7.0
	 *
	 * @param string $slug  Block slug.
	 * @param array  $attr  Block attributes.
	 * @param array  $extra Potential extra classes you may want to provide.
	 *
	 * @return string $classes List of CSS classes for a block.
	 */
	public static function block_classes( $slug = '', $attr, $extra = array() ) {
		if ( empty( $slug ) ) {
			return '';
		}

		// Basic block name class.
		$classes = array(
			'wp-block-jetpack-' . $slug,
		);

		// Add alignment if provided.
		if (
			! empty( $attr['align'] )
			&& in_array( $attr['align'], array( 'left', 'center', 'right', 'wide', 'full' ), true )
		) {
			array_push( $classes, 'align' . $attr['align'] );
		}

		// Add custom classes if provided in the block editor.
		if ( ! empty( $attr['className'] ) ) {
			array_push( $classes, $attr['className'] );
		}

		// Add any extra classes.
		if ( is_array( $extra ) && ! empty( $extra ) ) {
			$classes = array_merge( $classes, $extra );
		}

		return implode( $classes, ' ' );
	}
}
