<?php get_header();  ?>
<style>
	.contact-form input:not([type='submit']) + .icon-contact{
		right: 8px;
	}
	.contact-form input:not([type='submit']){
		padding-right: 35px !important;
	}
	

</style>
<div class="contact-img">
	<div class="container">
		<img style="width: 100%; height: 350px;" src="<?php echo get_template_directory_uri() . '/images/QUICK-CONTACT.jpg' ?>" alt="..." />
	
	</div>
</div>

<div class="contact">
	<div class="container">
		
		<div class="col-xs-12 col-sm-12 col-lg-6">
			<div class="contact-form">
				<h2 class="text-center">أرسل تعليقك</h2>

				<?php echo do_shortcode('[contact-form-7 id="468" title="Contact Ar"]') ?>
				<!--
				<form>
					<div class="form-group form-group-lg">
					  <input class="form-control" type="text" placeholder="الأسم"/>
						<i class="fas fa-user icon-contact icon-contact-xs"></i>
                    </div>
				
				   <div class="form-group form-group-lg">
					  <input class="form-control" type="email" placeholder="البريد الألكترونى"/>
					    <i class="fas fa-envelope icon-contact"></i>
                  </div>
				
				  <div class="form-group form-group-lg">
					  <input class="form-control" type="text" placeholder="رقم التليفون"/>
					   <i class="fas fa-phone icon-contact"></i>
                  </div>
					
				  <div class="form-group form-group-lg">
					  <textarea class="form-control area" placeholder="رسالنك"/></textarea>
                  </div>
				
				<div class="form-group form-group-lg">
					  <input type="submit" class="btn btn-contact btn-lg" value="أرسال">
                 </div>
			</form>
            -->
			</div>	
		</div>
		
		<div class="col-xs-12 col-sm-12 col-lg-6">
			<div class="contact-info">
				<div class="contact-email">
					<i class="fas fa-envelope"></i>  <a class="" href="mailto:atswaq@gmail.com"> atswaq@gmail.com</a> 
				</div>
				<div class="contact-email">
					<i class="fas fa-headphones"></i> 201275527489+
				</div>
				<div class="contact-email">
					<i class="fas fa-map-marker-alt"></i> حدائق القبة ,مصر ,القاهرة
				</div>
			</div>
		</div>
		
		
	
	</div> 
</div>




<!-- start footer --> 
<?php get_footer()?>

<!-- end footer --> 