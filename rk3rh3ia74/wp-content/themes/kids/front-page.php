<?php get_header(); ?>
<main>
  <div class="top-outer">
    <div class="top-vertical">
      <div class="top-kv">
        <picture>
          <source media="(min-width: 768px)" srcset="<?php echo output_file("/assets/images/kids/top/kv/pc/kv.jpg"); ?>" />
          <img src="<?php echo output_file("/assets/images/kids/top/kv/kv.jpg"); ?>" alt="" />
        </picture>
        <div class="top-kv_textWrap">
          <p class="top-kv_text">
            <span class="mod-pink">た</span>
            <span>の</span>
            <span class="mod-blue">し</span>
            <span>く</span>
            <br>
            <span>げ</span>
            <span class="mod-yellow">ん</span>
            <span>き</span>
            <span class="mod-green">に</span>
            <br>
            <span class="mod-orenge">す</span>
            <span>こ</span>
            <span class="mod-pink mod-space">や</span>
            <span>か</span>
            <span class="mod-blue">に</span>
          </p>
        </div>
      </div>
      <section class="top-ex" id='js-topEx'>
        <h2 class="top-ex_ttl js-inView">
          <span>事例紹介</span>
        </h2>
        <ul class="top-ex_list">

          <?php
          $category_name = 'case';
          $category = get_category_by_slug($category_name);
          $category_id = $category->term_id;
          $args = array(
            'category__in' => array($category_id),
            'posts_per_page' => 4,
          );
          $posts_query = new WP_Query($args);
          if ($posts_query->have_posts()) :
            while ($posts_query->have_posts()) :
              $posts_query->the_post();
              $featured_image_url = get_image_as_thumbnail(get_the_ID(), 'large');
          ?>
              <li class="top-ex_item js-inView">
                <a href="<?php the_permalink(); ?>" class="top-ex_item_link part-topicsArticleLink">
                  <figure class="top-ex_itemFig">
                    <div class="part-topicsArticleImgWrap" style="background-image: url('<?php echo esc_url($featured_image_url); ?>');"></div>
                  </figure>
                  <h3 class="top-ex_itemTxt js-santen"><?php the_title(); ?></h3>
                  <time datetime="<?php echo get_the_date('Y-m-d'); ?>" class="top-ex_itemDateTxt"><?php echo get_the_date('Y.m.d'); ?></time>
                </a>
              </li>
          <?php
            endwhile;
            wp_reset_postdata();
          endif;
          ?>

        </ul>




        <div class="top-ex_linkBox js-inView">
          <a href="<?php echo home_url(); ?>/category/case/" class="part-readMore_link">Read more</a>
        </div>
      </section>

      <section class="top-notice">
        <div class="top-noticeWrap">
          <h2 class="top-notice_ttl js-inView">
            <span>お知らせ</span>
          </h2>
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
                <li class="top-notice_item js-inView">
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
        </div>


        <div class="top-notice_linkBox js-inView">
          <a href="<?php echo home_url(); ?>/category/news/" class="part-readMore_link">Read more</a>
        </div>
      </section>


      <div class="top-bgWrap">
        <section class="top-recruit" id="js-checkSlideObserver">
          <div class="top-recruitWrap">
            <h2 class="top-recruit_ttl js-inView">採用情報</h2>
            <p class="top-recruit_ttlTxt js-inView">GFDの未来と<br class="util-sp">あなたの未来が重なる<br>縦でもなく横でもない<br class="util-sp">円の会社</p>
            <p class="top-recruit_txt js-inView"><span>GFDが描く未来構想は大きなものだからこそ<br>未来を形づくる中であなたの人生にも<br>プラスが生まれてほしい。</span><span>GFDは「誰と」仕事をするのか<br>ということを大事にしながら<br>自らを成長させる機会、<br>挑戦できる場所を見つけ、<br>自分らしい「働き方」<br>家族との時間を大切にすることができる<br>環境づくりに取り組んでいます。</span></p>
            <div class="top-recruit_linkBox js-inView">
              <a href="<?php echo home_url(); ?>/employment/" class="top-recruit_link ">
                <svg xmlns="http://www.w3.org/2000/svg" id="_レイヤー_2" viewBox="0 0 307.10608 88.77654">
                  <defs>
                    <style>
                      .cls-1 {
                        fill: #14509f;
                      }

                      .cls-2 {
                        fill: #65aee5;
                        stroke: #14509f;
                        stroke-miterlimit: 10;
                        stroke-width: 2px;
                      }
                    </style>
                  </defs>
                  <g id="_レイヤー_1-2">
                    <path class="cls-2" d="m292.46856,82.85248c-.47747,2.70823-3.11813,4.92406-5.86813,4.92406H5.20666c-2.75,0-4.60934-2.21583-4.13187-4.92406L14.63752,5.92406c.47747-2.70823,3.11813-4.92406,5.86813-4.92406h281.39377c2.75,0,4.60934,2.21583,4.13187,4.92406l-13.56273,76.92842Z" />
                    <polygon class="cls-1" points="262.98295 48.70163 254.97652 55.55104 254.97652 60.62113 268.90938 48.70163 254.97652 36.78229 254.97652 41.85231 262.98295 48.70163" />
                    <path class="cls-1" d="m51.69055,47.81567h-4.60547l-1.83301,10.64062h-6.125l5.32031-30.40137h10.14844c5.72266,0,10.23828,3.12891,10.2832,9.25391,0,4.91797-2.99512,8.31641-7.24316,9.74707l5.18652,11.40039h-6.4375l-4.69434-10.64062Zm1.38574-5.09668c3.4873,0,5.54395-1.92188,5.54395-5.1416,0-2.72754-1.96777-4.20215-4.69434-4.20215h-4.29199l-1.6543,9.34375h5.09668Z" />
                    <path class="cls-1" d="m77.97961,54.07446c2.2793,0,3.62109-.89355,4.64941-1.6543l3.44238,3.8457c-2.14551,1.6543-4.51562,2.59277-8.13672,2.59277-6.61719,0-10.64063-3.97949-10.64063-9.65723,0-6.25879,4.96289-10.19336,10.77441-10.19336,6.7959,0,10.19336,5.05273,9.34375,11.66895h-14.12695c.40234,2.10156,1.92188,3.39746,4.69434,3.39746Zm3.70996-6.75c.04492-2.19141-1.38574-3.62207-3.7998-3.62207-2.36914,0-3.97852,1.20703-4.51563,3.62207h8.31543Z" />
                    <path class="cls-1" d="m90.67688,49.73852c0-6.88574,4.20215-10.73047,9.03125-10.73047,2.59277,0,4.82812.9834,6.125,3.44238l.40234-3.04004h5.90137l-2.37012,13.50195c-.13379.80469.13477,1.11816.80469,1.11816h1.3418l-.75977,4.42578h-2.23535c-4.29199,0-4.87402-1.11816-5.18652-2.6377l-.04492-.22363c-1.47559,2.19043-2.90625,3.26367-5.54395,3.26367-4.11328,0-7.46582-3.53223-7.46582-9.12012Zm14.03809-1.16309c0-2.68262-1.29688-4.42578-3.66602-4.42578-2.59277,0-4.20215,2.10156-4.20215,5.32031,0,2.68262,1.34082,4.29199,3.66602,4.29199,2.50293,0,4.20215-2.14648,4.20215-5.18652Z" />
                    <path class="cls-1" d="m114.46203,49.73852c0-6.88574,4.29199-10.73047,9.25488-10.73047,2.72656,0,4.91699.9834,6.25879,3.21875l2.81641-16.0498h5.90137l-4.69434,26.73535c-.13379.80469.13477,1.11816.80469,1.11816h1.3418l-.76074,4.42578h-2.23535c-4.29199,0-4.96191-1.16211-5.18555-2.72754l-.04492-.3125c-1.25195,2.23535-3.08496,3.44238-5.76758,3.44238-4.24707,0-7.68945-3.53223-7.68945-9.12012Zm14.48535-1.16309c0-2.68262-1.43066-4.42578-3.88965-4.42578-2.72754,0-4.42578,2.10156-4.42578,5.32031,0,2.68262,1.47461,4.29199,3.88965,4.29199,2.6377,0,4.42578-2.14648,4.42578-5.18652Z" />
                    <path class="cls-1" d="m153.26867,27.74145h.44629l11.40137,16.54199,17.7041-16.54199h.53613l-5.40918,30.71484h-5.85742l2.0127-11.40039c.35742-2.05664,1.20703-5.54395,1.20703-5.54395,0,0-2.05664,2.81641-3.84473,4.51562l-7.10938,6.75098h-.49121l-4.73926-6.75098c-1.16211-1.60938-2.59277-4.20312-2.72754-4.4707-.04492.13379-.3125,3.48633-.6709,5.49902l-2.01172,11.40039h-5.85645l5.41016-30.71484Z" />
                    <path class="cls-1" d="m184.20617,49.20141c0-6.16992,4.69434-10.19336,10.59668-10.19336,5.63281,0,10.05859,3.97949,10.05859,9.70215,0,6.16895-4.69434,10.14844-10.5957,10.14844-5.63281,0-10.05957-4.06836-10.05957-9.65723Zm14.5752-.53613c0-2.77246-1.60938-4.56055-4.1582-4.56055-2.68164,0-4.33594,2.10156-4.33594,5.18652,0,2.77148,1.60938,4.56055,4.15723,4.56055,2.68262,0,4.33691-2.10156,4.33691-5.18652Z" />
                    <path class="cls-1" d="m210.40539,39.4104h5.90137l-.75977,3.66602c1.87793-3.26367,3.7998-4.06836,5.85645-4.06836,1.3418,0,2.14648.35742,2.86133.75977l-2.23535,5.41016c-.71484-.44727-1.51953-.62598-2.45898-.62598-2.59277,0-4.38086,1.07324-5.1416,5.4541l-1.47461,8.4502h-5.90137l3.35254-19.0459Z" />
                    <path class="cls-1" d="m235.62121,54.07446c2.2793,0,3.62109-.89355,4.64941-1.6543l3.44238,3.8457c-2.14551,1.6543-4.51562,2.59277-8.13672,2.59277-6.61719,0-10.64062-3.97949-10.64062-9.65723,0-6.25879,4.96289-10.19336,10.77441-10.19336,6.7959,0,10.19336,5.05273,9.34375,11.66895h-14.12695c.40234,2.10156,1.92188,3.39746,4.69434,3.39746Zm3.70996-6.75c.04492-2.19141-1.38574-3.62207-3.7998-3.62207-2.36914,0-3.97852,1.20703-4.51562,3.62207h8.31543Z" />
                  </g>
                </svg>
              </a>
            </div>
            <div class="top-recruit_illustWrap js-fadeIn_top">
              <img src="<?php echo output_file("/assets/images/top/recruit/item1.svg"); ?>" alt="" class="top-recruit_illustWrap_inline">
            </div>
            <div class="top-recruit_figWrap js-inView js-changeImg">
              <img src="<?php echo output_file("/assets/images/top/recruit/fig1.png"); ?>" alt="" class="top-serviceFig_inline js-img">
              <img src="<?php echo output_file("/assets/images/top/recruit/fig2.png"); ?>" alt="" class="top-serviceFig_inline js-img">
            </div>
          </div>


          <svg xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 751 401" class="top-svgNone">
            <clipPath id="clip01" clipPathUnits="objectBoundingBox">
              <path transform="scale(0.00133155792277,0.002493765586035)" fill="#282e38" d="M 255.83 68.56 C 419.90 90.66 584.67 105.28 749.81 116.40 A 0.54 0.54 2.3 0 1 750.31 116.94 L 750.44 388.11 A 0.39 0.39 2.0 0 1 750.02 388.50 Q 576.46 377.48 403.51 359.40 C 268.67 345.31 134.18 326.94 1.20 300.24 A 1.18 1.18 -84.4 0 1 0.25 299.08 L 0.25 25.40 A 0.62 0.62 -83.7 0 1 1.01 24.79 C 85.58 42.96 170.36 57.05 255.83 68.56 Z" />
            </clipPath>
          </svg>

          <svg xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 2685 655" class="top-svgNone">
            <clipPath id="clip02" clipPathUnits="objectBoundingBox">
              <path fill="#3e0228" transform="scale(0.000372439478585,0.001526717557252)" d="m.15.1865v461.33301c927.35144,190.80078,2512.33777,194.62891,2685.65808,194.62891V201.05466C2511.64695,201.05466,892.10374,195.99314.15.1865Z" />
            </clipPath>
          </svg>
          <div class="top-swiper js-inView">
            <div class="top-swiper_wrapper">
              <div class="top-swiper_slide" id="js-slideParent">
                <img src="<?php echo output_file("/assets/images/top/border/fig1.jpg"); ?>" alt="">
                <img src="<?php echo output_file("/assets/images/top/border/fig2.jpg"); ?>" alt="">
                <img src="<?php echo output_file("/assets/images/top/border/fig3.jpg"); ?>" alt="">
                <img src="<?php echo output_file("/assets/images/top/border/fig4.jpg"); ?>" alt="">
                <img src="<?php echo output_file("/assets/images/top/border/fig1.jpg"); ?>" alt="">
                <img src="<?php echo output_file("/assets/images/top/border/fig2.jpg"); ?>" alt="">
                <img src="<?php echo output_file("/assets/images/top/border/fig3.jpg"); ?>" alt="">
                <img src="<?php echo output_file("/assets/images/top/border/fig4.jpg"); ?>" alt="">
              </div>
            </div>
          </div>



        </section>


        <section class="top-soon">
          <div class="top-soonWrap">
            <img src="<?php echo output_file("/assets/images/top/soon/item1.svg"); ?>" alt="" class="top-soon_itemFig js-inView">
            <h2 class="top-soon_head">サイバーセキュリティシティ YOKOHAMA<br><span>横浜を「セキュリティエンジニア」が集まる街に</span></h2>

          </div>
          <div class="top-soonTxtPos">
            <p class="top-soon_txt">COMING SOON</p>
          </div>

        </section>




      </div>




    </div>
  </div>
</main>

<?php get_footer();
