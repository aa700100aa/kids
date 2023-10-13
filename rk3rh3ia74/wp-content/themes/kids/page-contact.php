<?php get_header(); ?>
<main>
  <div class="contact-outer">
    <h1 class="contact-ttl"><img src="<?php echo output_file("/assets/images/contact/icon1.svg"); ?>" alt=""><span>お問い合わせ</span></h1>
    <?php echo do_shortcode('[mwform_formkey key="6"]'); ?>
    <div class="contact-logo">
      <?php get_template_part('templates/parts/privacyMark'); ?>
    </div>
  </div>
</main>
<?php get_footer(); ?>