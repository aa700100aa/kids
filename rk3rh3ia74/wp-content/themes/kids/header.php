<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta id="js-viewport" content="width=device-width,initial-scale=1" name="viewport">
  <meta content="IE=edge" http-equiv="X-UA-Compatible">
  <meta name="format-detection" content="telephone=no">
  <?php set_meta_tag(); ?>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="<?php echo output_file('/assets/css/style.css'); ?>" rel="stylesheet">
  <?php if (is_front_page() || is_page('service')) : ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.1/gsap.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.1/ScrollTrigger.min.js" defer></script>
  <?php endif; ?>
  <script src="<?php echo output_file('/assets/js/app.js'); ?>" defer></script>
  <?php wp_head(); ?>
</head>

<body id="<?php echo is_single() ? 'post' : set_body_id(); ?>">
  <div id="js-loader" class="loader_container">
    <div class="loader_position">
      <div class="loader"></div>
    </div>
  </div>
  <div class="page-wrapper" id="js-scrollElm">
    <?php get_template_part('templates/parts/header'); ?>