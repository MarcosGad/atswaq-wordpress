<?php
/**
 * The Header for our theme.
 *
 * @package OceanWP WordPress theme
 */ ?>

<!DOCTYPE html>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2.0">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

	<?php do_action( 'ocean_before_outer_wrap' ); ?>

	<div id="outer-wrap" class="site clr">

		<?php do_action( 'ocean_before_wrap' ); ?>

		<div id="wrap" class="clr">

			
			<?php do_action( 'ocean_before_main' ); ?>



 <?php
 	$currLang = get_bloginfo('language');
     if($currLang == "en-US") {
 ?>

 <style>

 		ul.products li.product .tinvwl_add_to_wishlist_button{
 			right: 0; 
 		}

 		.list .yith-wcbm-badge-custom{
 			right: 68%; 
 		}

 		.xoo-el-login-tgr{
          border-right: 1px solid #fff;
          width: 50%;
        }

       
        
 		@media (max-width: 767.98px) {

	 		.list .yith-wcbm-badge-custom{
	 			right: 0; 
	 		}

	 		.xoo-el-username-menu img.avatar{
	 			margin-top: -4px; 
	 		}
 		}



 </style>

<div class="oneheader">
<div class="container">
    <div class="row">

    	<?php global $user_ID, $user_identity; if (!$user_ID) { ?>

        <div class="brandone hidden-xs col-sm-6 col-md-6 col-lg-8">
           <p> <a href="<?php echo get_home_url(); ?>"> Welcome To Atswaq </a> </p>
        </div>

        
        <!--
		<div class="col-xs-5 hidden-sm hidden-md hidden-lg">
			<div style="float: right">

				<i class="fas fa-user-lock"></i> <?php echo do_shortcode('[xoo_el_action active="login"]');  ?> 

			   <i class="fas fa-sign-in-alt"></i> <?php echo do_shortcode('[xoo_el_action type="register" change_to="myaccount"]');  ?> 
		    </div>

		</div>
		-->
		<div class="hidden-xs col-sm-6 col-md-6 col-lg-4">
        	<?php do_action( 'ocean_top_bar' ); ?>
        </div>
		
			<?php 

		     } else {   ?>


		    <div class="brandone-login hidden-xs col-sm-6 col-md-6 col-lg-8">
                 <p> <a href="<?php echo get_home_url(); ?>"> Welcome To Atswaq </a> </p>
            </div>
            <!--
	        <div class="col-xs-7 hidden-sm hidden-md hidden-lg">
			<div class="sidebox">

				<div class="container">
				<div class="usericon">
					<?php global $userdata; echo get_avatar($userdata->ID, 25); ?>
				</div>

				<div class="user">
				<p>Welcome, <?php echo $user_identity; ?></p>
				<span class="userinfo"> <a href="<?php echo wp_logout_url('index.php'); ?>">Log out</a> </span>	
			    </div>

		      </div>
			</div>
        	</div>	
        	-->
        	<div class="hidden-xs col-sm-6 col-md-6 col-lg-4">
        			<?php do_action( 'ocean_top_bar' ); ?>
           </div>			

	<?php } ?>
		
	
		</form>

    </div>
</div>
</div>

		
       <div class="container hidden-xs">
       		<div class="row">
       			<div class="col-sm-1 col-md-1 col-lg-1" style="padding:0;">
                     <a  href="<?php echo get_home_url(); ?>">
       				   <img class="img-responsive mylogo" src="<?php echo get_template_directory_uri() . '/images/mylogo.png' ?>" alt="..." />
                     </a>     
       			</div>

       			<div class="col-sm-6 col-md-7 col-lg-8">

       				<?php wp_nav_menu( array( 'theme_location' => 'max_mega_menu_1' ) ); ?>

       			</div>

       			<div class="col-sm-5 col-md-4 col-lg-3">
       				<div class="call-us">
       					<i class="fas fa-headphones"></i>
       					<p class="call-us-p" style="display: inline-block;"> 
       					<span><span class="one">Call Us Now: </span><span class="tow">+201275527489</span></span><br>
       					<span><span class="three">Email:</span> <a href="mailto:info@atswaq.com" class="four">info@atswaq.com</a></span>
       				    </p>
       				</div>
       			</div>

       		</div>	

		</div>

	
<div class="container-fulid" data-spy="affix" data-offset-top="198">
	<div class="threeheader">

	
	<div class="container">
		<div class="row">
		<div class="hidden-xs col-sm-5 col-md-4 col-lg-3">
	<div class="dropdown">
  <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" data-hover="dropdown">
   <i class="fa fa-bars" aria-hidden="true"></i> ALL DEPARTMENTS <span class="caret"></span>
  </button>
		
  <ul class="dropdown-menu">
  
		<?php get_sidebar('cat') ?>     
  </ul>
</div>
		
</div>
    <div class="col-sm-7 col-md-8 col-lg-9">   

           <?php echo do_shortcode("[aws_search_form]"); ?>
	
	</div>
</div>

</div>

</div>

</div>
			<main id="main" class="site-main clr"<?php oceanwp_schema_markup( 'main' ); ?>>
			<?php 

			if ( is_front_page() ) {

		    }else{
		    	do_action( 'ocean_page_header' );
		    }	

		    ?>

<?php if ( ! is_front_page() ) { ?>
<div class="container">
	<div class="breadcrumb">
		<?php echo do_shortcode("[wpseo_breadcrumb]") ?>
	</div>
</div>
<?php } ?>


<?php } ?> 


<!-- ar --> 

<?php
    $currLang = get_bloginfo('language');
     if($currLang == "ar") {
?>

<style>

	body{
		font-family: 'Markazi Text', serif;
		font-size: 16px; 
	}

	#mega-menu-wrap-max_mega_menu_1 #mega-menu-max_mega_menu_1{
		text-align: right;
	}

	#mega-menu-wrap-max_mega_menu_1 #mega-menu-max_mega_menu_1 > li.mega-menu-megamenu > ul.mega-sub-menu > li.mega-menu-item > a.mega-menu-link, #mega-menu-wrap-max_mega_menu_1 #mega-menu-max_mega_menu_1 > li.mega-menu-megamenu > ul.mega-sub-menu li.mega-menu-column > ul.mega-sub-menu > li.mega-menu-item > a.mega-menu-link{text-align: right;}

	.dropdown .widget-content ul li a{
       padding-right: 10px;
       text-align: right;
    }

    #top-bar-content #mega-menu-wrap-topbar_menu #mega-menu-topbar_menu >
     li.mega-menu-item > a.mega-menu-link{
     	font-size: 16px; 
     }

    .dropdown-menu{
    	left:-8px;
    }
	.threeheader .dropdown .btn,
	.threeheader .dropdown .btn:focus{
	 	text-align: right;
	 }

	 #mega-menu-wrap-topbar_menu #mega-menu-topbar_menu >
	 li.mega-menu-flyout ul.mega-sub-menu li.mega-menu-item a.mega-menu-link{
	 	text-align: right;
	 }

	 #top-bar-content #mega-menu-wrap-topbar_menu
	 #mega-menu-topbar_menu > li.mega-menu-item:last-child a > span{
	 	margin-right: 34px !important; 
	 }

	#mega-menu-wrap-topbar_menu #mega-menu-topbar_menu[data-effect="fade_up"] 
	li.mega-menu-item.mega-menu-megamenu > ul.mega-sub-menu,
	#mega-menu-wrap-topbar_menu #mega-menu-topbar_menu[data-effect="fade_up"] 
	li.mega-menu-item.mega-menu-flyout ul.mega-sub-menu li.lang-item-first{
	    top: 16px;
	    right: 0;
	    width: 52%;
	}

	#mega-menu-wrap-topbar_menu #mega-menu-topbar_menu[data-effect="fade_up"] 
	li.mega-menu-item.mega-menu-megamenu > ul.mega-sub-menu,
	#mega-menu-wrap-topbar_menu #mega-menu-topbar_menu[data-effect="fade_up"] 
	li.mega-menu-item.mega-menu-flyout ul.mega-sub-menu li.lang-item-first a.mega-menu-link span{
	 margin-top: -8px !important;
	 display:block !important;
	 margin-right: 1.6em !important; 
	}

	#mega-menu-wrap-topbar_menu #mega-menu-topbar_menu[data-effect="fade_up"] 
	li.mega-menu-item.mega-menu-megamenu > ul.mega-sub-menu,
	#mega-menu-wrap-topbar_menu #mega-menu-topbar_menu[data-effect="fade_up"] 
	li.mega-menu-item.mega-menu-flyout ul.mega-sub-menu li.lang-item-ar{
        top: 41px;
        right: 0px;
        width: 52%;
	 }

	 #top-bar-content #mega-menu-wrap-topbar_menu #mega-menu-topbar_menu > 
	 li.mega-menu-item:last-child a img{
	 	left: 0;
	 }

	#mega-menu-wrap-topbar_menu #mega-menu-topbar_menu[data-effect="fade_up"] 
	li.mega-menu-item.mega-menu-megamenu > ul.mega-sub-menu,
	#mega-menu-wrap-topbar_menu #mega-menu-topbar_menu[data-effect="fade_up"] 
	li.mega-menu-item.mega-menu-flyout ul.mega-sub-menu li.lang-item-first a.mega-menu-link img{
	    position: relative !important;
	    right: : 22px;
	    top: 9px !important;
	}

	#top-bar-content #mega-menu-wrap-topbar_menu
	#mega-menu-topbar_menu > li.mega-menu-item:last-child{
		margin-right: 0; 
	}

	.avatar{
	   margin-right: -29px !important;
	}

	.xoo-el-login-tgr{
		border:none;
	}

	.threeheader .dropdown .caret {
       margin-right: 138px !important;
    }

    .aws-search-result ul{
    	text-align: right;
    }

    .woocommerce ul.products li.product .button{
    	float: right !important; 
    }

    ul.products li.product .tinvwl_add_to_wishlist_button{
 	   left: 0; 
 	   bottom: 9px;
 	}
 	
 	 ul.products li.product .tinvwl_add_to_wishlist_button{
         height:34px;
    }
	
	ul.grid li.product .tinvwl_add_to_wishlist_button{
 	   bottom: 0;
 	}

 	.contact-form span:not([type='submit']) + .icon-contact{
 		 float: right;
         right: 8px;
         left: -8px; 
 	}

 	.wpcf7-form{
 		direction: rtl;
 	}

 	span.wpcf7-not-valid-tip{
 	   left: 0; 
 	   width: 100px;
 	}

	.woocommerce div.product .woocommerce-product-gallery
	.woocommerce-product-gallery__trigger {
	      right: 10px !important;
	 } 

	.list .yith-wcbm-badge-custom {
      top: 0;
      left: 68%;
    }

	.xoo-aff-group label{
		margin-right: 0; 
	}

	a.xoo-el-lostpw-tgr {
      position: absolute;
      left: 0;
    }

    .woocommerce-Tabs-panel--seller span.text {
       position: absolute;
       right: 14px;
    }

    .woocommerce-Tabs-panel--seller .seller-rating{
    	    float: right;
            padding-right: 67px;
    }

    .xoo-wsc-ctxt {
      display: block;
     text-align: left;
    }

    .call-us-p .tow, .call-us-p .three,
    .call-us-p .tow, .call-us-p .four{
    	font-size: 16px; 
    }

    .xoo-el-login-tgr{
        border-left: 1px solid #fff;
        width: 50%;
    }

    .brandone a, .brandone-login a{
    	float: right;
    }

    .timer .widget-title{
    	font-size: 22px; 
    }

    .box-tite h3{
    	font-size: 23px; 
    }

    .xoo-aff-input-group .xoo-aff-input-icon{
    	transform: rotateY(180deg);
    }

 	@media (min-width: 992px) and (max-width: 1200px) { 

 	  ul.products li.product .tinvwl_add_to_wishlist_button{
		  bottom: 0;
 	  }

 	  .list .yith-wcbm-badge-custom {
      top: 7.2%;
      }
 	}

 	@media (min-width: 768px) and (max-width: 992px)  { 

 	  ul.products li.product .tinvwl_add_to_wishlist_button{
 	      bottom: 4px;
 	  }
		
	  ul.grid li.product .tinvwl_add_to_wishlist_button{
 	    bottom: 0;
 	  }

 	 }
 	  @media (max-width: 767.98px) {

 	  	ul.products li.product .tinvwl_add_to_wishlist_button{
 	      bottom: 0;
 	   }
		  
	    ul.grid li.product .tinvwl_add_to_wishlist_button{
 	      bottom: 0;
 	    }

 	   .list .yith-wcbm-badge-custom{
 	   	left: 0; 
 	   }

 	   .woocommerce .oceanwp-off-canvas-filter, 
 	   .woocommerce .oceanwp-grid-list{
 	   	float: none;
 	   	display: block;
 	   	margin: 0; 
 	   }

 	   .woocommerce .woocommerce-ordering{
 	   	float: none;
 	   }

 	   .avatar {
           margin-right: 0px !important;
           margin-left: 8px;
        }

        #mg-wprm-wrap li.current-menu-item > a, 
        #mg-wprm-wrap ul#wprmenu_menu_ul li.menu-item a:hover{
        	border-right: 4px solid #f28500 !important;
        	border-left: none !important; 
        }

        .icon_default.wprmenu_icon_par:before{
        	 content: "\6f";
        }

        div#mg-wprm-wrap 
        ul#wprmenu_menu_ul>li>span.wprmenu_icon:before {
        	right: 200px; 
        }

        .xoo-aff-fields, #xoo-el-lostpw-email, 
        .xoo-el-notice-error,.xoo-el-notice-success{   
        	float: left;
        }


 	 }


</style>


<div class="oneheader">
<div class="container">
    <div class="row">

    	<?php global $user_ID, $user_identity; if (!$user_ID) { ?>


		<div class="hidden-xs hidden-sm hidden-md hidden-lg">
			<div style="float: left;">

		    </div>

		</div>

		<div class="hidden-xs col-sm-6 col-md-6 col-lg-4">
        	<?php do_action( 'ocean_top_bar' ); ?>
        </div>


        <div class="brandone hidden-xs col-sm-6 col-md-6 col-lg-8">

            <p> <a href="<?php echo get_home_url(); ?>">مرحــــبا فى اتــــســـوق  </a></p>

        </div>
		
			<?php 

		     } else {   ?>


		    

	        <div class="col-xs-7 hidden-sm hidden-md hidden-lg">
		
        	</div>	

        	<div class="hidden-xs col-sm-6 col-md-6 col-lg-4">
        			<?php do_action( 'ocean_top_bar' ); ?>
           </div>

           <div class="brandone-login hidden-xs col-sm-6 col-md-6 col-lg-8">
                  <p> <a href="<?php echo get_home_url(); ?>">مرحــــبا فى اتــــســـوق  </a></p>
            </div>			

	<?php } ?>
		
	
		</form>

    </div>
</div>
</div>

		
       <div class="container hidden-xs">
       		<div class="row">

       			<div class="col-sm-5 col-md-4 col-lg-4">
       				<div class="call-us">
       					<i class="fas fa-headphones"></i>
       					<p class="call-us-p" style="display: inline-block;"> 
       					<span><span class="one">اتصل بنا الآن: </span> <span class="tow"> 201275527489+ </span></span><br>
       					<span><span class="three">البريد الإلكتروني: </span> <a href="mailto:info@atswaq.com" class="four">info@atswaq.com</a></span>
       				    </p>
       				</div>
       			</div>
       			

       			<div class="col-sm-6 col-md-7 col-lg-7">

       				<?php wp_nav_menu( array( 'theme_location' => 'max_mega_menu_1' ) ); ?>

       			</div>

       			
       			<div class="col-sm-1 col-md-1 col-lg-1" style="padding:0;">
                    <a  href="<?php echo get_home_url(); ?>">
       				<img class="img-responsive mylogo" src="<?php echo get_template_directory_uri() . '/images/mylogo.png' ?>" alt="..." />
                    </a>
       			</div>

       		</div>	

		</div>

	
<div class="container-fulid" data-spy="affix" data-offset-top="198">
	<div class="threeheader">

	
	<div class="container">

<div class="row">

<div class="col-sm-7 col-md-8 col-lg-9">   

    <?php echo do_shortcode("[aws_search_form]"); ?>

</div>

<div class="hidden-xs col-sm-5 col-md-4 col-lg-3">
	<div class="dropdown">
  <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" data-hover="dropdown">
   <i class="fa fa-bars" aria-hidden="true"></i> جميع الأقسام  <span class="caret"></span>
  </button>
		
  <ul class="dropdown-menu">
  
		<?php get_sidebar('cat') ?>     
  </ul>
</div>
		
</div>


</div>

</div>

</div>

</div>
			<main id="main" class="site-main clr"<?php oceanwp_schema_markup( 'main' ); ?>>
			<?php 

			if ( is_front_page() ) {

		    }else{
		    	do_action( 'ocean_page_header' );
		    }	

		    ?>

<?php if ( ! is_front_page() ) { ?>

<div class="container">
	<div class="breadcrumb">
		<?php echo do_shortcode("[wpseo_breadcrumb]") ?>
	</div>
</div>

<?php } ?>
 

<?php } ?> 

