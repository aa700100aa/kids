<?php
if(is_category()) {
  $current_category = $args;
  if ($current_category->parent == 0) {
    $category = $current_category->name;
  } else {
    $parent_category = get_term($current_category->parent, 'category');
    $category = $parent_category->name;
  }
}
if($category == 'NEWS') {
  $img = 'common/icon-news1.svg';
  $ttl = 'お知らせ';
} elseif ($category  == 'CASE') {
  $img = 'common/icon-case1.svg';
  $ttl = '実績紹介';
}
?>
<h1 class="part-mainTtl">
  <img src="<?php echo output_file("/assets/images/" . $img ); ?>" alt="" class="part-mainTtl_img">
  <span class="part-mainTtl_span"><?php echo $ttl; ?></span>
</h1>