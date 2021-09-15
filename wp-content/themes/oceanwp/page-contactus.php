<?php get_header();  ?> 

<div class="contact-img">
	<div class="container">
		<img style="width: 100%; height: 350px;" src="<?php echo get_template_directory_uri() . '/images/QUICK-CONTACT.jpg' ?>" alt="..." />
	
	</div>
</div>

<div class="contact">
	<div class="container">
		<div class="col-xs-12 col-sm-12 col-lg-6">
			<div class="contact-info">
				<div class="contact-email">
					<i class="fas fa-envelope"></i>  <a class="" href="mailto:info@atswaq.com"> info@atswaq.com</a> 
				</div>
				<div class="contact-email">
					<i class="fas fa-headphones"></i> +201275527489
				</div>
				<div class="contact-email">
					<i class="fas fa-map-marker-alt"></i> Hadyek Elkobba, Cairo, Egypt
				</div>
			</div>
		</div>
		<div class="col-xs-12 col-sm-12 col-lg-6">
			<div class="contact-form">
				<h2 class="text-center">Send Your Message</h2>

				<?php echo do_shortcode('[contact-form-7 id="467" title="Contact form 1"]') ?>

				<!--
				<form>
					<div class="form-group form-group-lg">
					    <input class="form-control" type="text" placeholder="Your Name"/>
						<i class="fas fa-user icon-contact icon-contact-xs"></i>
                    </div>
				
				   <div class="form-group form-group-lg">
					  <input class="form-control" type="email" placeholder="Your Email"/>
					    <i class="fas fa-envelope icon-contact"></i>
                  </div>
				
				  <div class="form-group form-group-lg">
					  <input class="form-control" type="text" placeholder="Your Phone"/>
					   <i class="fas fa-phone icon-contact"></i>
                  </div>
					
				  <div class="form-group form-group-lg">
					  <textarea class="form-control area" placeholder="Your Message"/></textarea>
                  </div>
				
				<div class="form-group form-group-lg">
					  <input type="submit" class="btn btn-contact btn-lg" value="Send Message">
                 </div>		
			</form>
	        -->	
			</div>	
		</div>
	</div> 
</div>







<!-- start footer --> 
<?php get_footer()?>

<!-- end footer --> 