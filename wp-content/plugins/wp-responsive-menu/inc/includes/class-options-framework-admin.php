<?php
/**
 * @package   Wpr_Options_Framework
 * @author    Devin Price <devin@wptheming.com>
 * @license   GPL-2.0+
 * @link      http://wptheming.com
 * @copyright 2010-2014 WP Theming
 */

class Wpr_Options_Framework_Admin {

	/**
     * Page hook for the options screen
     *
     * @since 1.7.0
     * @type string
     */
    protected $options_screen = null;

    /**
     * Hook in the scripts and styles
     *
     * @since 1.7.0
     */
    public function init() {

		// Gets options to load
    	$options = & Wpr_Options_Framework::_wpr_optionsframework_options();

		// Checks if options are available
    	if ( $options ) {

			// Add the options page and menu item.
			add_action( 'admin_menu', array( $this, 'add_custom_options_page' ) );

			// Add the required scripts and styles
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

			// Settings need to be registered after admin_init
			add_action( 'admin_init', array( $this, 'settings_init' ) );

			// Adds options menu to the admin bar
			add_action( 'wp_before_admin_bar_render', array( $this, 'wpr_optionsframework_admin_bar' ) );

			

		}

    }

	/**
     * Registers the settings
     *
     * @since 1.7.0
     */
    function settings_init() {

    	// Load Options Framework Settings
        $wpr_optionsframework_settings = get_option( 'wpr_optionsframework' );

		// Registers the settings fields and callback
		register_setting( 'wpr_optionsframework', $wpr_optionsframework_settings['id'],  array ( $this, 'validate_options' ) );

		// Displays notice after options save
		add_action( 'wpr_optionsframework_after_validate', array( $this, 'save_options_notice' ) );

    }

	/*
	 * Define menu options
	 *
	 * Examples usage:
	 *
	 * add_filter( 'wpr_optionsframework_menu', function( $menu ) {
	 *     $menu['page_title'] = 'The Options';
	 *	   $menu['menu_title'] = 'The Options';
	 *     return $menu;
	 * });
	 *
	 * @since 1.7.0
	 *
	 */
	static function menu_settings() {

		$menu = array(

			// Modes: submenu, menu
            'mode' => 'menu',

            // Submenu default settings
            'page_title' => __( 'Theme Options', 'textdomain'),
			'menu_title' => __('Theme Options', 'textdomain'),
			'capability' => 'edit_theme_options',
			'menu_slug' => 'options-framework',
            'parent_slug' => 'themes.php',

            // Menu default settings
            'icon_url' => 'dashicons-menu',
            'position' => '61'

		);

		return apply_filters( 'wpr_optionsframework_menu', $menu );
	}

	/**
     * Add a subpage called "Theme Options" to the appearance menu.
     *
     * @since 1.7.0
     */
	function add_custom_options_page() {

	$menu = $this->menu_settings();

        switch( $menu['mode'] ) {

            case 'menu':
            	// http://codex.wordpress.org/Function_Reference/add_menu_page
                $this->options_screen = add_menu_page(
                	$menu['page_title'],
                	$menu['menu_title'],
                	$menu['capability'],
                	$menu['menu_slug'],
                	array( $this, 'options_page' ),
                	$menu['icon_url'],
                	$menu['position']
                );
                break;

            default:
            	// http://codex.wordpress.org/Function_Reference/add_submenu_page
                $this->options_screen = add_submenu_page(
                	$menu['parent_slug'],
                	$menu['page_title'],
                	$menu['menu_title'],
                	$menu['capability'],
                	$menu['menu_slug'],
                	array( $this, 'options_page' ) );
                break;
       	}
        
}

	/**
     * Loads the required stylesheets
     *
     * @since 1.7.0
     */

	function enqueue_admin_styles( $hook ) {

		if ( $this->options_screen != $hook )
	        return;

		wp_enqueue_style( 'wpr_optionsframework', WPR_OPTIONS_FRAMEWORK_DIRECTORY . 'css/wpr_optionsframework.css', array(),  Wpr_Options_Framework::VERSION );
		wp_enqueue_style( 'wpr_icons', WPR_OPTIONS_FRAMEWORK_DIRECTORY . 'icons/style.css', array(),  Wpr_Options_Framework::VERSION );

		wp_enqueue_style( 'wpr_iconpicker', WPR_OPTIONS_FRAMEWORK_DIRECTORY . 'css/jquery.fonticonpicker.min.css', array(),  Wpr_Options_Framework::VERSION );

		wp_enqueue_style( 'select2-style', WPR_OPTIONS_FRAMEWORK_DIRECTORY . 'css/select2.min.css', Wpr_Options_Framework::VERSION );

		// Enqueue custom option panel JS
		wp_enqueue_script( 'Select2-js', WPR_OPTIONS_FRAMEWORK_DIRECTORY . 'js/select2.full.js', array( 'jquery'), Wpr_Options_Framework::VERSION );

		
		wp_enqueue_style( 'wpr_bootflat', WPR_OPTIONS_FRAMEWORK_DIRECTORY . 'css/site.min.css', array(),  Wpr_Options_Framework::VERSION );
		wp_enqueue_style( 'wp-color-picker' );
	}

	/**
     * Loads the required javascript
     *
     * @since 1.7.0
     */
	function enqueue_admin_scripts( $hook ) {

		if ( $this->options_screen != $hook )
	        return;
		
		wp_enqueue_script( 'wpr-bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js', array( 'jquery'), Wpr_Options_Framework::VERSION );

		//Ace js
	  wp_enqueue_script( 'wpr-ace', WPR_OPTIONS_FRAMEWORK_DIRECTORY . 'js/ace-min-noconflict/ace.js', array( 'jquery' ), Wpr_Options_Framework::VERSION );

	  wp_enqueue_script( 'wpr-ace-theme-chrome', WPR_OPTIONS_FRAMEWORK_DIRECTORY . 'js/ace-min-noconflict/theme-chrome.js', array( 'jquery' ), Wpr_Options_Framework::VERSION );

	  wp_enqueue_script( 'wpr-ace-mode-css', WPR_OPTIONS_FRAMEWORK_DIRECTORY . 'js/ace-min-noconflict/mode-css.js', array( 'jquery' ), Wpr_Options_Framework::VERSION );

	  // Enqueue icon-picker js
		wp_enqueue_script( 'icon-picker', WPR_OPTIONS_FRAMEWORK_DIRECTORY . 'js/jquery.fonticonpicker.min.js', array( 'jquery' ), Wpr_Options_Framework::VERSION );

		//Exit Intent
		wp_enqueue_script( 'wpr-exit-intent', WPR_OPTIONS_FRAMEWORK_DIRECTORY . 'js/wpr-exit-intent.js', array( 'jquery' ), Wpr_Options_Framework::VERSION );

		// Enqueue SweetAlert2 Style
		wp_enqueue_style( 'Sweetalert2-css', WPR_OPTIONS_FRAMEWORK_DIRECTORY . 'css/sweetalert2.min.css', Wpr_Options_Framework::VERSION );

		// Enqueue SweetAlert2 JS
		wp_enqueue_script( 'Sweetalert2-js', WPR_OPTIONS_FRAMEWORK_DIRECTORY . 'js/sweetalert2.all.min.js', array( 'jquery'), Wpr_Options_Framework::VERSION );

		// Enqueue custom option panel JS
		wp_enqueue_script( 'options-custom', WPR_OPTIONS_FRAMEWORK_DIRECTORY . 'js/options-custom.js', array( 'jquery','wp-color-picker', 'Select2-js', 'wpr-ace', 'wpr-exit-intent', 'Sweetalert2-js' ), Wpr_Options_Framework::VERSION );

		$option_var = array( 
			'options_path' 			=> WPR_OPTIONS_FRAMEWORK_DIRECTORY,
			'ajax_url'  	 		=>  admin_url( 'admin-ajax.php' ),
			'view_demo'				=> __('View Demo', 'wprmenu'),
			'preview_done' 			=> __('Preview Done', 'wprmenu'),
			'loading_preview' 		=> __('Loading Preview', 'wprmenu'),
			'import_demo'			=> __('Import Demo', 'wprmenu'),
			'import_error'			=> __('Something went wrong', 'wprmenu'),
			'please_wait'			=> __('Please Wait !', 'wprmenu'),
			'please_reload'			=> __('Please reload the page by doing click the button below', 'wprmenu'),
			'reload'				=> __('Reload', 'wprmenu'),
			'import_error_title'	=> __('Oops...', 'wprmenu'),
			'import_error'			=> __('Something went wrong', 'wprmenu'),
			'import_done'			=> __('Import Done', 'wprmenu'),
			'update_license_key'	=> __('Please Update Your License Key To Import Demo', 'wprmenu'),
			'pro_message'			=> __('Import requires PRO version', 'wprmenu'),
			'site_url'				=> get_site_url(),
			'please_reload'			=> __('Please reload the page by doing click the button below', 'wprmenu'),
			'reload'			=> __('Reload', 'wprmenu'),
			'navigating_away' => __('Seems like navigating away', 'wprmenu'),
			'confirm_message' => __('Are you sure to navigate away? Please save all the changes otherwise the recent changes will be reverted back', 'wprmenu'),
			'pro_version_text' => __('Pro Version', 'wprmenu'),
			'pro_version_upgrade_error' => __('This demo requires pro version to be activated', 'wprmenu'),
		);
		wp_localize_script( 'options-custom', 'wprOption' , $option_var );

		// Inline scripts from options-interface.php
		add_action( 'admin_head', array( $this, 'wpr_of_admin_head' ) );
	}

	function wpr_of_admin_head() {
		// Hook to add custom scripts
		do_action( 'wpr_optionsframework_custom_scripts' );
	}

	/**
     * Builds out the options panel.
     *
	 * If we were using the Settings API as it was intended we would use
	 * do_settings_sections here.  But as we don't want the settings wrapped in a table,
	 * we'll call our own custom wpr_optionsframework_fields.  See options-interface.php
	 * for specifics on how each individual field is generated.
	 *
	 * Nonces are provided using the settings_fields()
	 *
     * @since 1.7.0
     */
	 function options_page() { ?>

		<div id="wpr_optionsframework-wrap" class="wrap">

		<?php $menu = $this->menu_settings(); ?>
		<h2><?php echo esc_html( $menu['page_title'] ); ?></h2>

	    <h2 class="nav-tab-wrapper">
	        <?php echo Wpr_Options_Framework_Interface::wpr_optionsframework_tabs(); ?>
	    </h2>

	    <?php settings_errors( 'options-framework' ); ?>

	    <div id="wpr_optionsframework-metabox" class="metabox-holder">
		    <div id="wpr_optionsframework" class="postbox">
				<form action="options.php" method="post">
				<?php settings_fields( 'wpr_optionsframework' ); ?>
				<?php Wpr_Options_Framework_Interface::wpr_optionsframework_fields(); /* Settings */ ?>
				<div id="wpr_optionsframework-submit">
					<input type="submit" class="button-primary" name="update" value="<?php esc_attr_e( 'Save Options', 'textdomain' ); ?>" />
					<input type="submit" class="reset-button button-secondary" name="reset" value="<?php esc_attr_e( 'Restore Defaults', 'textdomain' ); ?>" onclick="return confirm( '<?php print esc_js( __( 'Click OK to reset. Any theme settings will be lost!', 'textdomain' ) ); ?>' );" />
					<div class="clear"></div>
				</div>
				</form>
			</div> <!-- / #container -->
		</div>
		<?php do_action( 'wpr_optionsframework_after' ); ?>
		</div> <!-- / .wrap -->

	<?php
	}

	/**
	 * Validate Options.
	 *
	 * This runs after the submit/reset button has been clicked and
	 * validates the inputs.
	 *
	 * @uses $_POST['reset'] to restore default options
	 */
	function validate_options( $input ) {

		/*
		 * Restore Defaults.
		 *
		 * In the event that the user clicked the "Restore Defaults"
		 * button, the options defined in the theme's options.php
		 * file will be added to the option for the active theme.
		 */

		if ( isset( $_POST['reset'] ) ) {
			add_settings_error( 'options-framework', 'restore_defaults', __( 'Default options restored.', 'textdomain' ), 'updated fade' );
			return $this->get_default_values();
		}

		/*
		 * Update Settings
		 *
		 * This used to check for $_POST['update'], but has been updated
		 * to be compatible with the theme customizer introduced in WordPress 3.4
		 */

		$clean = array();
		$options = & Wpr_Options_Framework::_wpr_optionsframework_options();
		foreach ( $options as $option ) {

			if ( ! isset( $option['id'] ) ) {
				continue;
			}

			if ( ! isset( $option['type'] ) ) {
				continue;
			}

			$id = preg_replace( '/[^a-zA-Z0-9._\-]/', '', strtolower( $option['id'] ) );

			// Set checkbox to false if it wasn't sent in the $_POST
			if ( 'checkbox' == $option['type'] && ! isset( $input[$id] ) ) {
				$input[$id] = false;
			}

			// Set each item in the multicheck to false if it wasn't sent in the $_POST
			if ( 'multicheck' == $option['type'] && ! isset( $input[$id] ) ) {
				foreach ( $option['options'] as $key => $value ) {
					$input[$id][$key] = false;
				}
			}

			// For a value to be submitted to database it must pass through a sanitization filter
			if ( has_filter( 'wpr_of_sanitize_' . $option['type'] ) ) {
				$clean[$id] = apply_filters( 'wpr_of_sanitize_' . $option['type'], $input[$id], $option );
			}
		}

		// Hook to run after validation
		do_action( 'wpr_optionsframework_after_validate', $clean );
		
		if (isset($_COOKIE['wprmenu_live_preview']) && $_COOKIE['wprmenu_live_preview'] == 'yes' ) {
    	unset($_COOKIE['wprmenu_live_preview']);
    	setcookie('wprmenu_live_preview', null, -1, '/');
		} 


		return $clean;
	}

	/**
	 * Display message when options have been saved
	 */

	function save_options_notice() {
		add_settings_error( 'options-framework', 'save_options', __( 'WP Responsive Menu Options Saved.', 'textdomain' ), 'updated fade in' );
	}

	/**
	 * Get the default values for all the theme options
	 *
	 * Get an array of all default values as set in
	 * options.php. The 'id','std' and 'type' keys need
	 * to be defined in the configuration array. In the
	 * event that these keys are not present the option
	 * will not be included in this function's output.
	 *
	 * @return array Re-keyed options configuration array.
	 *
	 */

	function get_default_values() {
		$output = array();
		$config = & Wpr_Options_Framework::_wpr_optionsframework_options();
		foreach ( (array) $config as $option ) {
			if ( ! isset( $option['id'] ) ) {
				continue;
			}
			if ( ! isset( $option['std'] ) ) {
				continue;
			}
			if ( ! isset( $option['type'] ) ) {
				continue;
			}
			if ( has_filter( 'wpr_of_sanitize_' . $option['type'] ) ) {
				$output[$option['id']] = apply_filters( 'wpr_of_sanitize_' . $option['type'], $option['std'], $option );
			}
		}
		return $output;
	}

	/**
	 * Add options menu item to admin bar
	 */

	function wpr_optionsframework_admin_bar() {

		$menu = $this->menu_settings();

		global $wp_admin_bar;

		if ( 'menu' == $menu['mode'] ) {
			$href = admin_url( 'admin.php?page=' . $menu['menu_slug'] );
		} else {
			$href = admin_url( 'themes.php?page=' . $menu['menu_slug'] );
		}

		$args = array(
			'parent' => 'appearance',
			'id' => 'wpr_of_theme_options',
			'title' => $menu['menu_title'],
			'href' => $href
		);

		$wp_admin_bar->add_menu( apply_filters( 'wpr_optionsframework_admin_bar', $args ) );
	}



	

}
