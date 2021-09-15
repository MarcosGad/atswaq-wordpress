<?php
/**
 * TI WooCommerce Wishlist integration with:
 *
 * @name YITH WooCommerce Quick View
 *
 * @version 1.3.13
 *
 * @slug yith-woocommerce-quick-view
 *
 * @url https://wordpress.org/plugins/yith-woocommerce-quick-view/
 *
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

if ( defined( 'YITH_WCQV' ) ) {

	if ( ! function_exists( 'tinv_wishlist_meta_support_yith_wcqv' ) ) {

		/**
		 * Clear custom meta
		 *
		 * @param array $meta Meta array.
		 *
		 * @return array
		 */
		function tinv_wishlist_meta_support_yith_wcqv( $meta ) {

			foreach ( $meta as $k => $v ) {
				$prefix = 'yith_';
				if ( 0 === strpos( $k, $prefix ) ) {
					unset( $meta[ $k ] );
				}
			}

			return $meta;
		}

		add_filter( 'tinvwl_wishlist_item_meta_post', 'tinv_wishlist_meta_support_yith_wcqv' );
	} // End if().
}
