<?php get_header(); ?>

<main>




	<div class="contact-outer">
    <?php
      $args = ['ttl' => 'お問い合わせ', 'img' => 'kids/contact/icon.png', 'background' => 'kids/news/titile_background.jpg'];
      get_template_part('templates/parts/mainTtl', null, $args);
    ?>

		<div class="contact-complete_wrap">
			<?php echo do_shortcode('[mwform_formkey key="6"]'); ?>
			<?php get_template_part('templates/parts/goToTop') ?>
		</div>
	</div>



</main>

<?php get_footer(); ?>