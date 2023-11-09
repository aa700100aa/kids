<?php 
$prevUrl = $_SERVER['HTTP_REFERER'];
$domain = $_SERVER['SERVER_NAME'];
$headerClass = "";

if (strpos($prevUrl, $domain) == true || !(is_home() || is_front_page()) ) {
  $headerClass = "add-transition add-inView";
}
?>
<?php get_header(); ?>
<main>
  <div class="top-outer">
    <div class="top-vertical">
      <div class="top-kv js-inView <?php echo $headerClass; ?>">
        <picture>
          <source media="(min-width: 768px)" srcset="<?php echo output_file("/assets/images/kids/top/kv/pc/kv.jpg"); ?>" />
          <img src="<?php echo output_file("/assets/images/kids/top/kv/kv.jpg"); ?>" alt="" />
        </picture>
        <div class="top-kv_textWrap">
          <p class="top-kv_text">
            <span class="mod-pink mod-scale">た</span>
            <span class="mod-rotateRight">の</span>
            <span class="mod-blue mod-scale2">し</span>
            <span class="mod-rotateLeft">く</span>
            <br>
            <span class="mod-scale2">げ</span>
            <span class="mod-yellow mod-rotateRight">ん</span>
            <span class="mod-rotateLeft">き</span>
            <span class="mod-green mod-rotateRight">に</span>
            <br>
            <span class="mod-orenge mod-scale2">す</span>
            <span class="mod-rotateLeft">こ</span>
            <span class="mod-pink mod-space mod-rotateRight">や</span>
            <span class="mod-scale mod-rotateLeft">か</span>
            <span class="mod-blue mod-scale">に</span>
          </p>
        </div>
      </div>
      <section class="top-about" id="js-about">
        <h2 class="top-about_title js-inView">
          <img class="top-about_titleLogo" src="<?php echo output_file("/assets/images/kids/common/about.png"); ?>" alt="">
          <span class="top-about_titleText">当園について</span>
        </h2>
        <div class="top-about_imgWrap">
          <figure>
            <img class="top-about_img mod-first js-inView" src="<?php echo output_file("/assets/images/kids/top/about/photo_01.jpg"); ?>" alt="">
          </figure>
          <figure>
            <img class="top-about_img mod-last js-inView" src="<?php echo output_file("/assets/images/kids/top/about/photo_02.jpg"); ?>" alt="">
          </figure>
        </div>
        <div class="top-about_inner js-inView">
          <p class="top-about_mainText">「たのしく げんきに すこやかに」<br>を保育目標とし、<br>お子様を第一優先に考えて保育に取り組みます。</p>
          <p class="top-about_subText">私たちは保育目標を達成するために<br class="util-sp">丁寧な保育を心がけています。<br>思いやりのあるたくましい子を育てるためには、<br>自分を大切に思う心が育つ環境づくりが重要です。</p>
          <p class="top-about_subText">日々の保育を大切に、<br>子どもたちが成長していけるような保育園を<br class="util-sp">職員みんなでつくっています。</p>
        </div>
        <figure class="top-about_sun mod-scale2">
          <img class="js-inView" src="<?php echo output_file("/assets/images/kids/top/about/sun.png"); ?>" alt="">
        </figure>
        <figure class="top-about_cloud mod-scale">
          <img class="js-inView" src="<?php echo output_file("/assets/images/kids/top/about/cloud.png"); ?>" alt="">
        </figure>
      </section>
      <section class="top-flow">
        <h2 class="top-flow_title js-inView">
          <img class="top-flow_titleLogo" src="<?php echo output_file("/assets/images/kids/common/flow.png"); ?>" alt="">
          <span class="top-flow_titleText">一日の流れ</span>
        </h2>
        <div class="top-flow_wrap">
          <figure class="top-flow_imgWrap js-inView">
            <img class="top-flow_img" src="<?php echo output_file("/assets/images/kids/top/flow/photo_01.jpg"); ?>" alt="">
          </figure>
          <div class="top-flow_inner js-inView">
            <p class="top-flow_mainText">子どもたち一人ひとりが<br class="util-pc">安心してすごせる毎日</p>
            <p class="top-flow_subText">当園では子どもたちが安心してすごせるように遊びや食事の時間を考え、生活リズムを整えられるような保育を提供しております。</p>
            <?php
              $args = ['text' => '詳しくはこちら', 'link' => home_url().'/flow/','modify' => ''];
              get_template_part('templates/parts/button', null, $args);
            ?>
          </div>
        </div>
      </section>
      <section class="top-notice">
          <h2 class="top-notice_title js-inView">
            <img class="top-notice_titleLogo" src="<?php echo output_file("/assets/images/kids/common/news.png"); ?>" alt="">
            <span class="top-notice_titleText">お知らせ</span>
          </h2>
        <div class="top-noticeInner js-inView">
          <ul class="top-notice_list">
            <?php
            $category_name = 'news';
            $category = get_category_by_slug($category_name);
            $category_id = $category->term_id;
            $args = array(
              'category__in' => array($category_id),
              'posts_per_page' => 3,
            );
            $posts_query = new WP_Query($args);
            if ($posts_query->have_posts()) :
              while ($posts_query->have_posts()) :
                $posts_query->the_post();
                $featured_image_url = get_image_as_thumbnail(get_the_ID(), 'large');
            ?>
                <li class="top-notice_item">
                  <a href="<?php the_permalink(); ?>" class="top-notice_item_link part-topicsArticleLink">
                    <figure class="top-notice_fig">
                      <div class="part-topicsArticleImgWrap" style="background-image: url('<?php echo esc_url($featured_image_url); ?>');"></div>
                    </figure>
                    <div class="top-notice_itemBox">
                      <time datetime="<?php echo get_the_date('Y-m-d'); ?>" class="top-notice_itemDateTxt"><?php echo get_the_date('Y.m.d'); ?></time>
                      <h3 class="top-notice_itemTtl js-santen"><?php the_title(); ?></h3>
                      <p class="top-notice_itemTxt js-santen"><?php echo get_the_excerpt(); ?></p>
                    </div>
                  </a>
                </li>
            <?php
              endwhile;
              wp_reset_postdata();
            endif;
            ?>

          </ul>
          <?php
            $args = ['text' => 'すべての記事を見る', 'link' => home_url().'/category/news/', 'modify' => 'mod-space'];
            get_template_part('templates/parts/button', null, $args);
          ?>
        </div>
      </section>
    </div>
  </div>
</main>

<?php get_footer();
