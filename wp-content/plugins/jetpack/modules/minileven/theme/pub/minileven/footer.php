<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package Minileven
 */
?>

	</div><!-- #main -->
</div><!-- #page -->
<?php get_sidebar(); ?>

</div><!-- #wrapper -->

<?php
	/**
	* Fires before the Mobile Theme's <footer> tag.
	*
	* @module minileven
	*
	* @since 3.7.0
	*/
	do_action( 'jetpack_mobile_footer_before' );
?>

<footer id="colophon" role="contentinfo">
	<div id="site-generator">

		<?php
			/*
			 * Construct "$target_url", which adds "ak_action=reject_mobile"
			 * to the current URL.
			 */
			global $wp;
			$url_params = array(
				'ak_action' => 'reject_mobile',
			);
			if ( is_array( $_GET ) && ! empty( $_GET ) ) {
				$url_params[] = $_GET;
			}
			$target_url = home_url( add_query_arg( $url_params, $wp->request ) );
		?>

		<a href="<?php echo esc_url( $target_url ); ?>"><?php _e( 'View Full Site', 'jetpack' ); ?></a>
		<br />

		<?php
			/**
			 * Fires after the View Full Site link in the Mobile Theme's footer.
			 *
			 * By default, a promo to download the native apps is added to this action.
			 *
			 * @module minileven
			 *
			 * @since 1.8.0
			 */
			do_action( 'wp_mobile_theme_footer' );

			/**
			 * Fires before the credit links in the Mobile Theme's footer.
			 *
			 * @module minilven
			 *
			 * @since 1.8.0
			 */
			do_action( 'minileven_credits' );
		?>

		<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'jetpack' ) ); ?>" rel="noopener noreferrer" target="_blank" title="<?php esc_attr_e( 'Semantic Personal Publishing Platform', 'jetpack' ); ?>" rel="generator"><?php printf( __( 'Proudly powered by %s', 'jetpack' ), 'WordPress' ); ?></a>
	</div>
</footer><!-- #colophon -->

<?php wp_footer(); ?>

</body>
</html>
