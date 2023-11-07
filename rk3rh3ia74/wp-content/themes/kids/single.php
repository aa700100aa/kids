<?php get_header(); ?>


<?php
// 現在の投稿のIDを取得
$post_id = get_the_ID();
// 投稿に関連するカテゴリを取得
$categories = get_the_category($post_id);


// 親カテゴリと子カテゴリの名前を決定する
foreach ($categories as $category) {
  if ($category->parent === 0) {
    $parent_category_name = strtolower($category->name);
  } else {
    $child_category_name = strtolower($category->name);
    $parent_category = get_term($category->parent, $category->taxonomy);
    $parent_category_name = strtolower($parent_category->name);
    $child_category_id = get_queried_object_id();
  }
}

// 親カテゴリの名前に基づいて変数を設定する
if ($parent_category_name === 'case') {
  $img = 'common/icon-case1.svg';
  $ttl = '実績紹介';
} elseif ($parent_category_name === 'news') {
  $img = 'common/icon-news1.svg';
  $ttl = 'お知らせ';
}

foreach ($categories as $category) {
  if ($category->parent === 0) {
    $parent_term_id = $category->term_id;
  } else {
    $child_term_id[] = $category->term_id;
  }
}

// セッションが設定されていたら
$back_url = home_url() . '/category/' . $parent_category_name . '/';
$parent_category_id = 0;
if (!empty($post_id)) {
  $parent_category_id = $post_id[0]->parent;
}
$args = array(
  'posts_per_page' => 1,
  'post_type' => 'post',
  'category__in' => array($parent_category_id),
  'orderby' => 'post_date',
  'post_status' => 'publish'
);
$prev_post = get_previous_post($args);
$next_post = get_next_post($args);
?>


<main class="post">
  <div class='post-outer'>
    <div class="post-ttlWrap">
      <?php
        $args = ['ttl' => 'お知らせ', 'img' => 'kids/news/icon.png', 'background' => 'kids/news/kv.jpg'];
        get_template_part('templates/parts/mainTtl', null, $args);
      ?>
    </div>
    <div class="post-wrap">
      <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
          <div class="post-orderWrap">
            <div class="post-topWrap">
              <time class="post-time" datetime="<?php echo str_replace('.', '-', get_post_time('Y.m.d')); ?>"><?php echo get_post_time('Y.m.d'); ?></time>
              <?php if ($child_category_name) : ?>
                <div class="post-category">
                  <span class="js-getCate"><?php echo strtoupper($child_category_name); ?></span>
                </div>
              <?php endif; ?>
            </div>
            <h1><?php the_title(); ?></h1>
          </div>
          <div class="post-inner">
            <?php the_content(); ?>
          </div>
        <?php endwhile; ?>
      <?php endif; ?>
      <div class="post-btnBox">
        <?php if (!empty($next_post)) : ?>
          <a href="<?php echo esc_url(get_permalink($next_post->ID)); ?>" class="post-backIndex mod-prev">
            <span>前の記事</span>
          </a>
        <?php else : ?>
          <div class="post-backIndex mod-prev mod-disable">
            <span>前の記事</span>
          </div>
        <?php endif; ?>
        <a class="post-backIndex js-backToIndex" href="<?php echo $back_url; ?>">
          <span>記事一覧へ戻る</span>
        </a>
        <?php if (!empty($prev_post)) : ?>
          <a href="<?php echo get_permalink($prev_post->ID); ?>" class="post-backIndex mod-next">
            <span>次の記事</span>
          </a>
        <?php else : ?>
          <div class="post-backIndex mod-next mod-disable">
            <span>次の記事</span>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</main>

<?php get_footer(); ?>