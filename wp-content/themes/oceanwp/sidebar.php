<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package OceanWP WordPress theme
 */

// Retunr if full width or full screen
if ( in_array( oceanwp_post_layout(), array( 'full-screen', 'full-width' ) ) ) {
	return;
} ?>

<?php do_action( 'ocean_before_sidebar' ); ?>

<aside id="right-sidebar" class="sidebar-container widget-area sidebar-primary"<?php oceanwp_schema_markup( 'sidebar' ); ?>>

	<?php do_action( 'ocean_before_sidebar_inner' ); ?>

	<div id="right-sidebar-inner" class="clr">

		<?php

		$ar = array(
			post_type => 'product',
		); 

		if ( $sidebar = oceanwp_get_sidebar() and !is_page('my-account') and !is_page('cart') and !is_page('checkout') and !is_page('wishlist') and !is_page('حسابى') and !is_page('عربة التسوق') and !is_page('الدفع') and !is_page('الأماني') and !is_single() ){
			dynamic_sidebar( $sidebar );
		} ?>

	</div><!-- #sidebar-inner -->

	<?php do_action( 'ocean_after_sidebar_inner' ); ?>

</aside><!-- #right-sidebar -->

<?php do_action( 'ocean_after_sidebar' ); ?>