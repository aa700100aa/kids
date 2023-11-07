<?php get_header(); ?>
<main>
  <div class="contact-outer">
    <?php
      $args = ['ttl' => 'お問い合わせ', 'img' => 'kids/contact/icon.png', 'background' => 'kids/contact/kv.jpg'];
      get_template_part('templates/parts/mainTtl', null, $args);
    ?>
    <?php echo do_shortcode('[mwform_formkey key="6"]'); ?>
  </div>
</main>
<?php get_footer(); ?>