<?php
/**
 * The template for displaying the footer.
 *
 * @package OceanWP WordPress theme
 */ ?>

        </main><!-- #main -->

<!--start footer --> 
<?php
     $currLang = get_bloginfo('language');
     if($currLang == "en-US") {
 ?>

       <style>

             .head-email a, .head-email p{
                margin-left: 30px; 
             }

             .head-p-p{
                font-size: 18px;
                font-weight: bold;
             }

             @media (min-width: 992px) and (max-width: 1200px) { 

               .head-p-p{
                  font-size: 14px;
                }

               .head-email a, .head-email p{
                 font-size: 14px; 
               }
             }

             @media (min-width: 768px) and (max-width: 992px)  { 

                .head-p-p{
                  font-size: 11px;
                }

               .head-email a, .head-email p{
                 font-size: 10px; 
               }

               .head-footer .head-email {

                   margin-top: 25px;
               }
            }
         

       </style>

            <div class="head-footer hidden-xs">
                 <div class="container">
                     <div class="row">

                         <div class="col-sm-3">
                             <p class="head-p">
                                 <p class="head-p-p">We’re Always Here To Help</p>
                                 <span style="font-size: 10px">Continue through us:</span>
                             </p>
                         </div>

                         <div class="col-sm-3">
                            <div class="head-email">
                              <i class="fas fa-envelope"></i> 
                              <a href="mailto:info@atswaq.com"> info@atswaq.com</a> 
                            </div>
                         </div>

                        <div class="col-sm-3">
                             <div class="head-email">
                              <i class="fas fa-headphones"></i> 
                              <p> +201275527489 </p> 
                            </div>
                         </div>

                         <div class="col-sm-3">
                             <div class="head-email">
                              <i class="fab fa-whatsapp-square"></i>
                              <a href="https://api.whatsapp.com/send?phone=201275527489&text=Hello&source=&data="> WhatsApp Us </a>  
                            </div>
                         </div>
                     
                        
                     </div>
                 </div>
            </div>

        <section class="footer">
            <div class="container">
                <div class="row follo">
                     <div class="col-xs-6 col-sm-4 col-md-4 col-lg-4">
                     <h3>INFORMATION</h3>
                           <ul class="list-unstyled" style="margin: 0;">
                            <li><a href="<?php bloginfo('url')?>/shipping-policy/">Shipping Policy</a></li>
                            <li><a href="<?php bloginfo('url')?>/privacy-policy/">Privacy Policy</a></li>
                            <li><a href="<?php bloginfo('url')?>/refund-policy/">Refund Policy</a></li>
                            <li><a href="<?php bloginfo('url')?>/payment-policy/">Payment Policy</a></li>
                            <li><a href="<?php bloginfo('url')?>/return-polic/">Return Polic</a></li>
                            <li><a href="<?php bloginfo('url')?>/termsandconditions/">Terms and Conditions</a></li>
                            </ul>
                    </div>
                    <div class="col-xs-6 col-sm-4 col-md-4 col-lg-4 ">
                     <h3>MY ACCOUNT</h3>
                    <ul class="list-unstyled" style="margin: 0;">
                    <li><a href="<?php bloginfo('url')?>/cart/">Cart</a></li>
                    <li><a href="<?php bloginfo('url')?>/wishlist/">Wishlist</a></li>
                    <li><a href="<?php bloginfo('url')?>/checkout/">Checkout</a></li>
                    <li><a href="<?php bloginfo('url')?>/my-account/">My Account</a></li>
                    </ul>
                    </div>
                     <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                      <h3>CONTACT US</h3>
					 <div class="media"> 
                     <div class="media-body">
                         <h4 class="media-heading">
                             <i class="fas fa-map-marker-alt"></i> 
                            Hadyek Elkobba, Cairo, Egypt
                         </h4>
                          
                     </div>
                     </div>
						 
                    <div class="media"> 
                     <div class="media-body">
                         <h4 class="media-heading">
                             <i class="fas fa-envelope"></i> 
                            Email
                         </h4>
                         <a class="" href="mailto:info@atswaq.com"> info@atswaq.com</a> 
                     </div>
                     </div>
                    <div class="media"> 
                     <div class="media-body">
                         <h4 class="media-heading">
                             <i class="fas fa-headphones"></i> 
                            Call Us Now
                         </h4>
                         +201275527489
                     </div>
                     </div>
                    </div>
                </div>
                    <div class="follow">
                    <div class="row">
                        <div class="hidden-xs hidden-sm col-md-8">
                            <div class="row">
                                <div class="col-md-3">
                                <p class="sign-name">SIGN UP FOR NEWSLETTER</p>
                                </div>
                                <div class="col-md-9">
                                <?php echo do_shortcode("[nsu_form]"); ?>
                               </div>
                           </div>
                        </div>
                        <!-- small scren -->
                        <div class="col-xs-12 col-sm-8 hidden-md hidden-lg">
                            <p class="follow-name">SIGN UP FOR NEWSLETTER</p>
                            <div class="row">
                                <?php echo do_shortcode("[nsu_form]"); ?>
                           </div>
                        </div>
                        <!-- small scren -->
                        <div class="col-xs-12 col-sm-4">
                            <p class="follow-name follow-name-xs">FOLLOW US</p>
                            <ul>
                                <a href="https://www.facebook.com/atswaq/" class="hvr-pulse-grow"><li><i class="fab fa-facebook-f"></i></li></a>
                                <a href="https://www.instagram.com/atswaq/" class="hvr-pulse-grow"><li><i class="fab fa-instagram"></i></li></a>
                                <a href="" class="hvr-pulse-grow"><li><i class="fab fa-twitter"></i></li>
                                </a>
                            </ul>

                           <p class="app-store">
	                           <a href="#">
	                           <img class="img-responsive app-img" src="<?php echo get_template_directory_uri() . '/images/A.png' ?>" alt="..." />  
	                           </a>

	                           <a href="#">
	                           <img class="img-responsive app-img" src="<?php echo get_template_directory_uri() . '/images/G.png' ?>" alt="..." />  
	                           </a>
                           </p>

                        </div>
                    </div>  
                </div>
                
            </div>
            <div class="pay-method">
                    <img class="img-responsive img-thumbnail" src="<?php echo get_template_directory_uri() . '/images/payment-footer.png' ?>" alt="..." />    
            </div>
            <div class="copyright text-center">
                 Copyright &copy; <span class="copy"><?php echo date('Y')?> Atswaq.com</span> All Rights Reserved
            </div>
        </section>
      
<?php
}
?>
<!-- ar --> 
<?php
    $currLang = get_bloginfo('language');
     if($currLang == "ar") {
?>

<style>
    .sign-name{
        right: 10px; 
    }

    .nsu-form p:first-child{
       margin-right: -40px;
       margin-left: 0px;
    }

    .follow ul{
        padding-right: 15px; 
    }
	
	@media (min-width: 768px) and (max-width: 992px)  { 
		.nsu-form p:first-child{
			margin-right: 20px; 
		}
	}
	@media (max-width: 767.98px) {
		
		.nsu-form p:first-child{
			margin-right: 0; 
		}		
	}

</style>
        
           <div class="head-footer hidden-xs">
                 <div class="container">
                     <div class="row">
                         <div class="col-sm-3">
                             <div class="head-email">
                              <i class="fab fa-whatsapp-square"></i>
                              <a href="https://api.whatsapp.com/send?phone=201275527489&text=Hello&source=&data=">كلمنا على WhatsApp </a>  
                            </div>
                         </div>
                         <div class="col-sm-3">
                             <div class="head-email">
                              <i class="fas fa-headphones"></i> 
                              <p> 201275527489+ </p> 
                            </div>
                         </div>
                         <div class="col-sm-3">
                            <div class="head-email">
                              <i class="fas fa-envelope"></i> 
                              <a href="mailto:info@atswaq.com"> info@atswaq.com</a> 
                            </div>
                         </div>
                         <div class="col-sm-3">
                             <p class="head-p">
                                 احنا دايما موجودين في خدمتك
                                 <span>تواصل معانا عبر:</span>
                             </p>
                         </div>
                     </div>
                 </div>
            </div>

        <section class="footer">

            <div class="container">
                <div class="row follo">
                    
					
                     <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
					
                      <h3>اتصل بنا</h3>
					   <div class="media"> 
                     <div class="media-body">
                         <h4 class="media-heading">
                             <i class="fas fa-map-marker-alt"></i>
							 العنوان
                         </h4>
						 حدائق القبة ,مصر ,القاهرة
                     </div>
                     </div>
						 
                    <div class="media"> 
                     <div class="media-body">
                         <h4 class="media-heading">
                             <i class="fas fa-envelope"></i> 
                            البريد الإلكتروني
                         </h4>
                         <a class="" href="mailto:info@atswaq.com"> info@atswaq.com</a> 
                     </div>
                     </div>
                    <div class="media"> 
                     <div class="media-body">
                         <h4 class="media-heading">
                             <i class="fas fa-headphones"></i> 
                            اتصل بنا الآن
                         </h4>
                         +201275527489 
                     </div>
                     </div>
                    </div>
					
					
                    <div class="col-xs-6 col-sm-4 col-md-4 col-lg-4 ">
                     <h3>حسابي</h3>
                    <ul class="list-unstyled" style="margin: 0;">
                    <li><a href="<?php bloginfo('url')?>/عربة-التسوق/">عربة التسوق</a></li>
                    <li><a href="<?php bloginfo('url')?>/wishlist/">المفضلة</a></li>
                    <li><a href="<?php bloginfo('url')?>/الدفع/">الدفع</a></li>
                    <li><a href="<?php bloginfo('url')?>/حسابى/">حسابي</a></li>
                    </ul>
                    </div>
					
					 <div class="col-xs-6 col-sm-4 col-md-4 col-lg-4">
                     <h3>معلومات</h3>
                           <ul class="list-unstyled" style="margin: 0;">
                            <li><a href="<?php bloginfo('url')?>/سياسة-الشحن/">سياسة الشحن</a></li>
                            <li><a href="<?php bloginfo('url')?>/سياسة-الخصوصية/">سياسة الخصوصية</a></li>
                            <li><a href="<?php bloginfo('url')?>/سياسة-الاسترداد/">سياسة الاسترداد</a></li>
                            <li><a href="<?php bloginfo('url')?>/سياسة-الدفع/">سياسة الدفع</a></li>
                            <li><a href="<?php bloginfo('url')?>/سياسة-الاسترجاع/">سياسة الاسترجاع</a></li>
                            <li><a href="<?php bloginfo('url')?>/الشروط-والأحكام/">الشروط والأحكام</a></li>
                            </ul>
                    </div>
					
					
                </div>
							
                 <div class="follow">
                    <div class="row">
						
						  <!-- phone scren -->
                        <div class="col-xs-12 hidden-sm hidden-md hidden-lg">
                            
                            <p class="follow-name">الاشتراك في النشرة الإخبارية</p>

                            <div class="row">
                                <?php echo do_shortcode("[nsu_form]"); ?>
                           </div>
                           
                        </div>
                        <!-- phone scren -->
						
                        
                        <div class="col-xs-12 col-sm-4">
                            <p class="follow-name follow-name-xs">تابعنا</p>
                            <ul>
                                <a href="https://www.facebook.com/atswaq/" class="hvr-pulse-grow"><li><i class="fab fa-facebook-f"></i></li></a>
                                <a href="https://www.instagram.com/atswaq/" class="hvr-pulse-grow"><li><i class="fab fa-instagram"></i></li></a>
                                <a href="" class="hvr-pulse-grow"><li><i class="fab fa-twitter"></i></li>
                                </a>
                            </ul>  

                        <p class="app-store">
                           <a href="#">
                           <img class="img-responsive app-img-ar" src="<?php echo get_template_directory_uri() . '/images/AA.png' ?>" alt="..." />  
                           </a>
                        
                           <a href="#">
                           <img class="img-responsive app-img-ar" src="<?php echo get_template_directory_uri() . '/images/GA.png' ?>" alt="..." />  
                           </a>
                        </p>

                        </div>

                        <div class="hidden-xs hidden-sm col-md-8">
                            <div class="row">
                                <div class="col-md-9">
                                <?php echo do_shortcode("[nsu_form]"); ?>
                               </div>
                               <div class="col-md-3">
                                <p class="sign-name">الاشتراك في النشرة الإخبارية</p>
                               </div>
                           </div>
                        </div>
						
						  <!-- small scren -->
                        <div class="hidden-xs col-sm-8 hidden-md hidden-lg">
                            
                            <p class="follow-name">الاشتراك في النشرة الإخبارية</p>

                            <div class="row">
                                <?php echo do_shortcode("[nsu_form]"); ?>
                           </div>
                           
                        </div>
                        <!-- small scren -->
                      

                    </div>  
                </div>
                
            </div>
            <div class="pay-method">
                    <img class="img-responsive img-thumbnail" src="<?php echo get_template_directory_uri() . '/images/payment-footer.png' ?>" alt="..." />    
            </div>
            <div class="copyright text-center">
                 حقوق الطبع والنشر  &copy; <span class="copy"> Atswaq.com <?php echo date('Y')?> </span> جميع الحقوق محفوظة
            </div>
        </section>
      
<?php
}
?>






        <!-- end footer--> 
<?php wp_footer(); ?>
</body>
</html>