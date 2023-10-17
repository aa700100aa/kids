<?php get_header(); ?>
<main>
  <div class="contact-outer">
    <?php
      $args = ['ttl' => 'お問い合わせ', 'img' => 'kids/contact/icon.png', 'background' => 'kids/news/titile_background.jpg'];
      get_template_part('templates/parts/mainTtl', null, $args);
    ?>
    <?php echo do_shortcode('[mwform_formkey key="6"]'); ?>
    <div class="contact-logo">
      <?php get_template_part('templates/parts/privacyMark'); ?>
    </div>
  </div>
</main>
<?php get_footer(); ?>