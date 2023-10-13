<?php
$category = get_queried_object();
if ($category) {
  // カテゴリーに属する記事を取得するクエリを作成
  $args = array(
    'post_status' => 'publish', // 公開中の記事のみ
    'post_type' => 'post', // 投稿タイプ（デフォルトは「post」）
    'posts_per_page' => $post_per_page, // 取得する記事の数
    'paged' => get_query_var('paged'), // 現在のページ番号を取得
    'cat' => $category->cat_ID, // カテゴリーIDを指定
  );

  // カスタムクエリを実行
  $query = new WP_Query($args);
}
?>
<?php get_header(); ?>
<main class="posts-main">
  <div class="posts-ttlWrap">
    <?php get_template_part('templates/parts/mainTtl', '', $category); ?>
  </div>
  <div class="posts-contentWrap">
    <div class="posts-contentWrapInner">
      <?php get_template_part('templates/parts/postsArticleList', '', $query) ?>
      <?php get_template_part('templates/parts/postsPagination', '', $query); ?>
    </div>
    <div class="posts-sideMenuOuter">
      <?php get_template_part('templates/parts/postsSideMenu', '', $category); ?>
    </div>
  </div>
</main>
<?php get_footer(); ?>
<?php wp_reset_postdata(); ?>