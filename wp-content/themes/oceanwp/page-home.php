<?php

get_header(); ?>

<style>

	img{
	  max-width: none !important; 
	}

	.add_to_cart_button.loading{

		position: relative !important; 
		text-align: center !important; 
	    left:0 !important; 
		background: url(<?php echo get_template_directory_uri() . '/images/Rolling-1s-18px.gif' ?>) no-repeat center center #fff !important; 
	    top:0 !important; 
	    bottom: 0 !important; 
	    right: 0 !important; 
	    display: block !important; 
		margin-bottom: 2px !important; 
	    width: 80% !important; 
	    height: auto !important; 
	   background-color: #fff !important; 
    }

</style>

<div class="timer">
	<?php get_sidebar('timer') ?>	
</div>

<!-- start  main slider --> 
<div class="container">	
<div class="row block">
	
	<div  class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
			<div class="skitter skitter-large with-dots">
				
					  <ul>
						  
				    <?php 
				
					$values = array('post_type'=>'mslider','order'=>'ASC'); 
					
					$query = new wp_query($values); 
				
					if($query->have_posts()){
						
						while($query->have_posts()){
							
							$query->the_post(); 	
				
			           ?>

						<li>
						  <a href="<?php the_field("link_main") ?>">
							 	<img src="<?php the_field("img_main") ?>" class="cut">
						  </a>
						</li>

						 <?php } } ?> 
					  </ul>
			</div>
	</div>
	<div style="padding:0px" class="hidden-xs hidden-sm col-md-4 col-lg-4">
		
		
		<?php 
				
		  $values = array('post_type'=>'sidebarphoto','order'=>'DCE','posts_per_page' => 2); 
					
		  $query = new wp_query($values); 
				
		  if($query->have_posts()){
						
		  while($query->have_posts()){
							
		  $query->the_post(); 	
				
	   ?>

		<a href="<?php the_field("link_main") ?>">
	     <img class="img-slide wow zoomIn" data-wow-duration="5s" data-wow-offset="200" data-wow-iteration="1" src="<?php the_field("img_main") ?> " alt="..." />
		</a>
	<?php } } ?> 	
		
	</div>
	
</div>
	
</div> 

<!-- end main slider --> 


<!-- start important item --> 

<div class="container">

<div class="slider">
	
<section class="regular slider">
<?php
	
            $args = array( 'post_type' => 'product', 'posts_per_page' => 10, 'orderby' => 'rand', 
				'meta_query'     => array(
				'relation' => 'OR',
				array( // Simple products type
					'key'           => '_sale_price',
					'value'         => 0,
					'compare'       => '>',
					'type'          => 'numeric'
				),
				array( // Variable products type
					'key'           => '_min_variation_sale_price',
					'value'         => 0,
					'compare'       => '>',
					'type'          => 'numeric'
				)
			)

	);
        $loop = new WP_Query( $args );
        while ( $loop->have_posts() ) : $loop->the_post(); global $product; ?>
				
				<div class="postion-slick">
					
					<a class="price-item" href="<?php echo get_permalink( $loop->post->ID ) ?>" title="<?php echo esc_attr($loop->post->post_title ? $loop->post->post_title : $loop->post->ID); ?>">
                       
						
                       <?php if ( $product->is_on_sale() ) : ?>
                              <?php echo apply_filters( 'woocommerce_sale_flash', '<span class="circle-two">' . __( 'Sale!', 'woocommerce' ) . '</span>', $post, $product ); ?>
                         <?php endif; ?>
						
					
                         <div class="content-o">
                         <div class="content-overlay"></div>
                        <?php if (has_post_thumbnail( $loop->post->ID )) echo get_the_post_thumbnail($loop->post->ID, 'shop_catalog'); else echo '<img src="'.woocommerce_placeholder_img_src().'" alt="Placeholder"  class="owl-carousel-imgg" />'; ?>
                        </div>

                        <h3><?php the_title(); ?></h3>

                        <span class="price"><?php echo $product->get_price_html(); ?></span>                    

                    </a>
                    <?php echo do_shortcode("[ti_wishlists_addtowishlist loop=no]") ?>
                    <?php  woocommerce_template_loop_add_to_cart( $loop->post, $product ); ?>				
				</div>
				
			
		 <?php endwhile; ?>
    <?php wp_reset_query(); ?>	
		
   </section>
</div>
</div>

<!-- end important item -->

<!-- start cat --> 
<div class="catogray">
<div id="cat-one" class="container block">
	<div class="row">
		<div class="col-sm-3 col-md-2 col-lg-2">
			
			<div  class="box-tite">
				<a href="http://www.atswaq.com/product-category/stationery/"> <h3>Stationary</h3> </a>
			</div>
			<a href="http://www.atswaq.com/product-category/stationery/">
			<img class="hidden-xs img-responsive img-thumbnail cat-img hvr-pulse-grow" style="max-width: 100% !important;" alt="Stationery" src="<?php echo get_template_directory_uri() . '/images/Stationary.jpg' ?>" alt="..." /></a>
				
			
		
		
		</div>
		<div class="col-sm-9 col-md-10 col-lg-10">
			
			<div class="header-slider xs-header-slider">
				 <p>Latest Products</p>
			</div>
			
	<section class="regular slider">
       <?php
	
        $args = array( 'post_type' => 'product', 'product_cat' => 'stationery',  'stock' => 1, 'posts_per_page' => 10, 'orderby' =>'date','order' => 'DESC'); 				  
        $loop = new WP_Query( $args );
        while ( $loop->have_posts() ) : $loop->the_post(); global $product; ?>
				
				<div class="postion-slick">
					
					<a class="price-item" href="<?php echo get_permalink( $loop->post->ID ) ?>" title="<?php echo esc_attr($loop->post->post_title ? $loop->post->post_title : $loop->post->ID); ?>">
                       
						
                       <?php if ( $product->is_on_sale() ) : ?>
                              <?php echo apply_filters( 'woocommerce_sale_flash', '<span class="circle-two">' . __( 'Sale!', 'woocommerce' ) . '</span>', $post, $product ); ?>
                         <?php endif; ?>
						
					
                        <div class="content-o">
                        <div class="content-overlay"></div>
                        <?php if (has_post_thumbnail( $loop->post->ID )) echo get_the_post_thumbnail($loop->post->ID, 'shop_catalog'); else echo '<img src="'.woocommerce_placeholder_img_src().'" alt="Placeholder"  class="owl-carousel-imgg" />'; ?>
                        </div>

                        <h3><?php the_title(); ?></h3>

                        <span class="price"><?php echo $product->get_price_html(); ?></span>                    

                    </a>
                    <?php echo do_shortcode("[ti_wishlists_addtowishlist loop=no]") ?>
                    <?php  woocommerce_template_loop_add_to_cart( $loop->post, $product ); ?>	
					
				</div>
				
			
		 <?php endwhile; ?>
    <?php wp_reset_query(); ?>	
		
</section>
		
</div>
	
</div>

</div>


<div class="container">
	
	<div class="row">
		<div class="col-sm-3 col-md-2"></div>
		<div class="col-sm-9 col-md-10">
			<div class="header-slider header-slider-xs">
				 <p>Discount</p>
			</div>
				<section class="regular slider">
             <?php
	
            $args = array( 'post_type' => 'product','product_cat' => 'stationery', 'posts_per_page' => 10, 'orderby' => 'rand', 
				'meta_query'     => array(
				'relation' => 'OR',
				array( // Simple products type
					'key'           => '_sale_price',
					'value'         => 0,
					'compare'       => '>',
					'type'          => 'numeric'
				),
				array( // Variable products type
					'key'           => '_min_variation_sale_price',
					'value'         => 0,
					'compare'       => '>',
					'type'          => 'numeric'
				)
			)

	);
        $loop = new WP_Query( $args );
        while ( $loop->have_posts() ) : $loop->the_post(); global $product; ?>
				
				<div class="postion-slick">
					
					<a class="price-item" href="<?php echo get_permalink( $loop->post->ID ) ?>" title="<?php echo esc_attr($loop->post->post_title ? $loop->post->post_title : $loop->post->ID); ?>">
                       
						
                       <?php if ( $product->is_on_sale() ) : ?>
                              <?php echo apply_filters( 'woocommerce_sale_flash', '<span class="circle-two">' . __( 'Sale!', 'woocommerce' ) . '</span>', $post, $product ); ?>
                         <?php endif; ?>
						
					
                        <div class="content-o">
                        <div class="content-overlay"></div>
                        <?php if (has_post_thumbnail( $loop->post->ID )) echo get_the_post_thumbnail($loop->post->ID, 'shop_catalog'); else echo '<img src="'.woocommerce_placeholder_img_src().'" alt="Placeholder"  class="owl-carousel-imgg" />'; ?>
                        </div>

                        <h3><?php the_title(); ?></h3>

                        <span class="price"><?php echo $product->get_price_html(); ?></span>                    

                    </a>
                     <?php echo do_shortcode("[ti_wishlists_addtowishlist loop=no]") ?>
                    <?php  woocommerce_template_loop_add_to_cart( $loop->post, $product ); ?>
										
				</div>
				
			
		 <?php endwhile; ?>
    <?php wp_reset_query(); ?>	
		
</section>

		</div>		
	</div>		
  </div>
</div>
<!-- end cat --> 

<!-- start cat --> 
<div class="catogray">
<div id="cat-one" class="container block">
	<div class="row">
		<div class="col-sm-3 col-md-2 col-lg-2">
			
			<div  class="box-tite">
				<a href="http://www.atswaq.com/product-category/electronices/"> <h3>Electronics</h3> </a>
			</div>
			<a href="http://www.atswaq.com/product-category/electronices/">
			<img class="hidden-xs img-responsive img-thumbnail cat-img hvr-pulse-grow" style="max-width: 100% !important;" alt="Electronics" src="<?php echo get_template_directory_uri() . '/images/Electronics.jpg' ?>" alt="..." /></a>
				
			
		
		
		</div>
		<div class="col-sm-9 col-md-10 col-lg-10">
			
			<div class="header-slider xs-header-slider">
				 <p>Latest Products</p>
			</div>
			
	<section class="regular slider">
       <?php
	
        $args = array( 'post_type' => 'product', 'product_cat' => 'electronices',  'stock' => 1, 'posts_per_page' => 10, 'orderby' =>'date','order' => 'DESC'); 				  
        $loop = new WP_Query( $args );
        while ( $loop->have_posts() ) : $loop->the_post(); global $product; ?>
				
				<div class="postion-slick">
					
					<a class="price-item" href="<?php echo get_permalink( $loop->post->ID ) ?>" title="<?php echo esc_attr($loop->post->post_title ? $loop->post->post_title : $loop->post->ID); ?>">
                       
						
                       <?php if ( $product->is_on_sale() ) : ?>
                              <?php echo apply_filters( 'woocommerce_sale_flash', '<span class="circle-two">' . __( 'Sale!', 'woocommerce' ) . '</span>', $post, $product ); ?>
                         <?php endif; ?>
						
					
                        <div class="content-o">
                        <div class="content-overlay"></div>
                        <?php if (has_post_thumbnail( $loop->post->ID )) echo get_the_post_thumbnail($loop->post->ID, 'shop_catalog'); else echo '<img src="'.woocommerce_placeholder_img_src().'" alt="Placeholder"  class="owl-carousel-imgg" />'; ?>
                        </div>

                        <h3><?php the_title(); ?></h3>

                        <span class="price"><?php echo $product->get_price_html(); ?></span>                    

                    </a>
                    <?php echo do_shortcode("[ti_wishlists_addtowishlist loop=no]") ?>
                    <?php  woocommerce_template_loop_add_to_cart( $loop->post, $product ); ?>	
					
				</div>
				
			
		 <?php endwhile; ?>
    <?php wp_reset_query(); ?>	
		
</section>
		
</div>
	
</div>

</div>


<div class="container">
	
	<div class="row">
		<div class="col-sm-3 col-md-2"></div>
		<div class="col-sm-9 col-md-10">
			<div class="header-slider header-slider-xs">
				 <p>Discount</p>
			</div>
				<section class="regular slider">
             <?php
	
            $args = array( 'post_type' => 'product','product_cat' => 'electronices', 'posts_per_page' => 10, 'orderby' => 'rand', 
				'meta_query'     => array(
				'relation' => 'OR',
				array( // Simple products type
					'key'           => '_sale_price',
					'value'         => 0,
					'compare'       => '>',
					'type'          => 'numeric'
				),
				array( // Variable products type
					'key'           => '_min_variation_sale_price',
					'value'         => 0,
					'compare'       => '>',
					'type'          => 'numeric'
				)
			)

	);
        $loop = new WP_Query( $args );
        while ( $loop->have_posts() ) : $loop->the_post(); global $product; ?>
				
				<div class="postion-slick">
					
					<a class="price-item" href="<?php echo get_permalink( $loop->post->ID ) ?>" title="<?php echo esc_attr($loop->post->post_title ? $loop->post->post_title : $loop->post->ID); ?>">
                       
						
                       <?php if ( $product->is_on_sale() ) : ?>
                              <?php echo apply_filters( 'woocommerce_sale_flash', '<span class="circle-two">' . __( 'Sale!', 'woocommerce' ) . '</span>', $post, $product ); ?>
                         <?php endif; ?>
						
					
                        <div class="content-o">
                        <div class="content-overlay"></div>
                        <?php if (has_post_thumbnail( $loop->post->ID )) echo get_the_post_thumbnail($loop->post->ID, 'shop_catalog'); else echo '<img src="'.woocommerce_placeholder_img_src().'" alt="Placeholder"  class="owl-carousel-imgg" />'; ?>
                        </div>

                        <h3><?php the_title(); ?></h3>

                        <span class="price"><?php echo $product->get_price_html(); ?></span>                    

                    </a>
                     <?php echo do_shortcode("[ti_wishlists_addtowishlist loop=no]") ?>
                    <?php  woocommerce_template_loop_add_to_cart( $loop->post, $product ); ?>
										
				</div>
				
			
		 <?php endwhile; ?>
    <?php wp_reset_query(); ?>	
		
</section>

		</div>		
	</div>		
  </div>
</div>
<!-- end cat --> 

<!-- start new item --> 

<div class="container">

<div class="header-slider header-slider-xs-new">
	<p>New Product</p>
</div>

<div class="slider">
	
<section class="regular slider">
<?php
	
         $args = array( 'post_type' => 'product', 'stock' => 1, 'posts_per_page' => 10, 'orderby' =>'date','order' => 'DESC'); 
        $loop = new WP_Query( $args );
        while ( $loop->have_posts() ) : $loop->the_post(); global $product; ?>
				
				<div class="postion-slick">
					
					<a class="price-item" href="<?php echo get_permalink( $loop->post->ID ) ?>" title="<?php echo esc_attr($loop->post->post_title ? $loop->post->post_title : $loop->post->ID); ?>">

						<div class="content-o">
                        <div class="content-overlay"></div>
                        <?php if (has_post_thumbnail( $loop->post->ID )) echo get_the_post_thumbnail($loop->post->ID, 'shop_catalog'); else echo '<img src="'.woocommerce_placeholder_img_src().'" alt="Placeholder"  class="owl-carousel-imgg" />'; ?>
                        </div>

                        <h3><?php the_title(); ?></h3>

                        <span class="price"><?php echo $product->get_price_html(); ?></span>                    

                    </a>

                    <?php echo do_shortcode("[ti_wishlists_addtowishlist loop=no]") ?>
                    <?php  woocommerce_template_loop_add_to_cart( $loop->post, $product ); ?>					
				</div>
				
			
		 <?php endwhile; ?>
    <?php wp_reset_query(); ?>	
		
			  </section>
</div>
</div>
<!-- end new item -->

<!-- start slider end --> 
<div class="container">
	<div class="skitter skitter-large with-dots" style="margin-top: 30px;">
				
					  <ul>
						  
				    <?php 
				
					$values = array('post_type'=>'msliderfooter','order'=>'ASC'); 
					
					$query = new wp_query($values); 
				
					if($query->have_posts()){
						
						while($query->have_posts()){
							
							$query->the_post(); 	
				
			           ?>

						<li>
						  <a href="<?php the_field("link_main") ?>">
							 	<img src="<?php the_field("img_main") ?> " class="cut">
							  
						  </a>
						</li>

						 <?php } } ?> 
					  </ul>
			</div>
	
</div>

<!-- end slider end --> 

<!-- start scrollTop -->
 <div class="scrollTop hidden-xs">
     <i class="fas fa-arrow-up"></i>   
 </div>   
<!-- end scrollTop --> 

<?php
get_footer();

?>

 <script>
	 
jQuery(function ($) {
          				
$('.regular').slick({
 	    dots: false,
		autoplay: true,
		arrows : false,
        infinite: true,
        slidesToShow: 4,
        slidesToScroll: 1,
  responsive: [
    {
      breakpoint: 1024,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 3,
        infinite: true,
        dots: false
      }
    },
    {
      breakpoint: 600,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 2
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    }
  ]
});						
				
 }); 
				
</script>
				
				
			
<?php get_footer(); ?>