<?php

// these are helpers for the shortcode and embed render endpoints
abstract class WPCOM_JSON_API_Render_Endpoint extends WPCOM_JSON_API_Endpoint {

	/*
	 * Figure out what scripts and styles to load.
	 * props to o2's o2_Read_API::poll() function for inspiration.
	 *
	 * In short we figure out what scripts load for a "normal" page load by executing wp_head and wp_footer
	 * then we render the embed/shortcode (to both get our result, and to have the shortcode files enqueue their resources)
	 * then we load wp_head and wp_footer again to see what new resources were added
	 * finally we find out the url to the source file and any extra info (like media or init js)
	 */
	function process_render( $callback, $callback_arg ) {
		global $wp_scripts, $wp_styles;

		// initial scripts & styles (to subtract)
		ob_start();
		wp_head();
		wp_footer();
		ob_end_clean();
		$initial_scripts = $wp_scripts->done;
		$initial_styles = $wp_styles->done;

		// actually render the shortcode, get the result, and do the resource loading again so we can subtract..
		ob_start();
		wp_head();
		ob_end_clean();
		$result = call_user_func( $callback, $callback_arg );
		ob_start();
		wp_footer();
		ob_end_clean();

		// find the difference (the new resource files)
		$loaded_scripts = array_diff( $wp_scripts->done, $initial_scripts );
		$loaded_styles = array_diff( $wp_styles->done, $initial_styles );
		return array(
			'result' => $result,
			'loaded_scripts' => $loaded_scripts,
			'loaded_styles' => $loaded_styles,
		);
	}

	/**
	 * Takes the list of styles and scripts and adds them to the JSON response
	 */
	function add_assets( $return, $loaded_scripts, $loaded_styles ) {
		global $wp_scripts, $wp_styles;
		// scripts first, just cuz
		if ( count( $loaded_scripts ) > 0 ) {
			$scripts = array();
			foreach ( $loaded_scripts as $handle ) {
				if ( !isset( $wp_scripts->registered[ $handle ] ) )
					continue;

				$src = $wp_scripts->registered[ $handle ]->src;

				// attach version and an extra query parameters
				$ver = $this->get_version( $wp_scripts->registered[ $handle ]->ver, $wp_scripts->default_version );
				if ( isset( $wp_scripts->args[ $handle ] ) ) {
					$ver = $ver ? $ver . '&amp;' . $wp_scripts->args[$handle] : $wp_scripts->args[$handle];
				}
				$src = add_query_arg( 'ver', $ver, $src );

				// add to an aray so we can return all this info
				$scripts[ $handle ] = array(
					'src' => $src,
				);
				$extra = $wp_scripts->print_extra_script( $handle, false );
				if ( !empty( $extra ) ) {
					$scripts[$handle]['extra'] = $extra;
				}
			}
			$return['scripts'] = $scripts;
		}
		// now styles
		if ( count( $loaded_styles ) > 0 ) {
			$styles = array();
			foreach ( $loaded_styles as $handle ) {
				if ( !isset( $wp_styles->registered[ $handle ] ) )
					continue;

				$src = $wp_styles->registered[ $handle ]->src;

				// attach version and an extra query parameters
				$ver = $this->get_version( $wp_styles->registered[ $handle ]->ver, $wp_styles->default_version );
				if ( isset( $wp_styles->args[ $handle ] ) ) {
					$ver = $ver ? $ver . '&amp;' . $wp_styles->args[$handle] : $wp_styles->args[$handle];
				}
				$src = add_query_arg( 'ver', $ver, $src );

				// is there a special media (print, screen, etc) for this? if not, default to 'all'
				$media = 'all';
				if ( isset( $wp_styles->registered[ $handle ]->args ) ) {
					$media = esc_attr( $wp_styles->registered[ $handle ]->args );
				}

				// add to an array so we can return all this info
				$styles[ $handle ] = array (
					'src' => $src,
					'media' => $media,
				);
			}

			$return['styles'] = $styles;
		}

		return $return;
	}

	/**
	 * Returns the 'version' string set by the shortcode so different versions of scripts/styles can be loaded
	 */
	function get_version( $this_scripts_version, $default_version ) {
		if ( null === $this_scripts_version ) {
			$ver = '';
		} else {
			$ver = $this_scripts_version ? $this_scripts_version : $default_version;
		}
		return $ver;
	}

	/**
	 * given a shortcode, process and return the result
	 */
	function do_shortcode( $shortcode ) {
		return do_shortcode( $shortcode );
	}

	/**
	 * given a one-line embed URL, process and return the result
	 */
	function do_embed( $embed_url ) {
		// in order for oEmbed to fire in the `$wp_embed->shortcode` method, we need to set a post as the current post
		$_posts = get_posts( array( 'posts_per_page' => 1, 'suppress_filters' => false ) );
		if ( ! empty( $_posts ) ) {
			global $post;
			$post = array_shift( $_posts );
		}

		global $wp_embed;
		return $wp_embed->shortcode( array(), $embed_url );
	}
}