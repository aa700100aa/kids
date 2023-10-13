<?php get_header(); ?>

<main>




	<div class="contact-outer">

		<h1 class="contact-ttl"><img src="<?php echo output_file("/assets/images/contact/icon1.svg"); ?>" alt="">お問い合わせ</h1>

		<div class="contact-complete_wrap">
			<?php echo do_shortcode('[mwform_formkey key="6"]'); ?>
			<?php get_template_part('templates/parts/goToTop') ?>
		</div>
	</div>



</main>

<?php get_footer(); ?>