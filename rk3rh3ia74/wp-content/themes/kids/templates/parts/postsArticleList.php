<?php
$query = $args;
?>
<ul class="parts-articleList">
  <?php
  if ($query->have_posts()) :
    while ($query->have_posts()) :
      $query->the_post();
      $post_id = get_the_ID();
      $thumbnail_url = get_image_as_thumbnail($post_id, 'medium');
      $post_categories = get_the_category();
  ?>
      <li class="parts-articleItem js-inView">
        <a href="<?php echo get_permalink(); ?>" class="parts-articleLink js-articleLink">
          <figure class="parts-articleThumbnail">
            <?php if ($thumbnail_url) : ?>
              <span class="part-articleThumbnail_span" style="<?php echo 'background-image: url(' . $thumbnail_url . ')'; ?>"></span>
            <?php endif; ?>
          </figure>
          <div class="parts-articleTxtWrap">
            <div class="parts-articleHeadTxtBox">
              <h2 class="parts-articleTtl"><?php the_title(); ?></h2>
              <div class="parts-articleInfoBox">
                <time class="parts-articleTime" datetime="<?php echo get_the_date('Y-m-d'); ?>"><?php echo get_the_date('Y.m.d'); ?></time>
                <?php
                foreach ($post_categories as $post_category) :
                  $post_category_slug = $post_category->category_nicename;
                  if ($post_category_slug != 'news' && $post_category_slug != 'case') :
                ?>
                    <span class="parts-articleCategory js-articleCategory"><?php echo $post_category->cat_name; ?></span>
                <?php endif;
                endforeach; ?>
              </div>
            </div>
            <p class="parts-articleDetailTxt"><?php echo get_the_excerpt(); ?></p>
          </div>
        </a>
      </li>
  <?php endwhile;
  endif;
  wp_reset_postdata(); ?>
</ul>