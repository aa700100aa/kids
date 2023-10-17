<?php get_header(); ?>
<!-- 
<?php
$domain = $_SERVER['HTTP_HOST'];
if ($domain == 'localhost:8888') {
  $key = '6LdC0tgmAAAAAExtfz_vf6Xy92ry2onoGi6O5xCZ';
}
//  elseif ($domain == 'xs121703.xsrv.jp') {
//   $key = '6Lf_r4MjAAAAANTK82dIVMel2pbMoPDAsqRoBxj-';
// }

$json_array = json_encode($key);

?> -->

<main>

  <div class="contact-outer">

    <?php
      $args = ['ttl' => 'お問い合わせ', 'img' => 'kids/contact/icon.png', 'background' => 'kids/news/titile_background.jpg'];
      get_template_part('templates/parts/mainTtl', null, $args);
    ?>

    <div class="contact-head_container">
      <p class="contact-head_txt">お問い合わせ内容をご確認のうえ、<br>よろしければ「送信する」ボタンを押して下さい。</p>
    </div>

    <?php echo do_shortcode('[mwform_formkey key="6"]'); ?>

  </div>
</main>

<?php get_footer(); ?>
<script>
  const js_array = JSON.parse('<?php echo $json_array; ?>');
</script> 