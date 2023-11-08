<?php 
//get_the_categoryをカテゴリの親子関係でソートする
//ソート順は 親 > 子 > 孫
function sortCategoryByAncestors() {
  $breaklist = get_the_category();
  foreach($breaklist as $key => $val){
      $breaklist[count(get_ancestors($val->term_id, 'category'))] = [
          'name' => $val->cat_name,
          'id' => $val->cat_ID
      ];
  }
  return $breaklist;
}
//カテゴリーページと記事ページのときの親カテゴリを取得
if(is_category() || is_single()) {
  $ancestorCat = mb_strtolower(sortCategoryByAncestors()[0]['name']);
}
?>
<header class='header' id='js-header'>
  <div class='header-outer'>
    <div class='header-inner'>
      <div class="header-border"></div>
    <?php if (is_front_page()) : ?>
      <div class='header-logoWrap'>
        <h1 class='header-logo'>
          <span class="header-logo_link">
            <img class="header-logo_icon" src="<?php echo output_file("/assets/images/kids/common/icon.svg"); ?>" alt="">
            <img class="header-logo_title" src="<?php echo output_file("/assets/images/kids/common/title.svg"); ?>" alt="あらかわ保育園">
          </span>
        </h1>
    <?php else : ?>
      <div class='header-logoWrap'>
        <div class='header-logo'>
          <a class="header-logo_link" href="<?php echo home_url(); ?>/" >
            <img class="header-logo_icon" src="<?php echo output_file("/assets/images/kids/common/icon.svg"); ?>" alt="">
            <img class="header-logo_title" src="<?php echo output_file("/assets/images/kids/common/title.svg"); ?>" alt="あらかわ保育園">
          </a>
        </div>
    <?php endif; ?>
        <button class='header-burgerIcon' id='js-hmbg'>
          <span class='header-burgerIcon_inner'>
            <span class='header-burgerIcon_line mod-top'></span>
            <span class='header-burgerIcon_line mod-middle'></span>
            <span class='header-burgerIcon_line mod-bottom'></span>
          </span>
        </button>
      </div>
      <div class='header-content' id="js-headerNav">
        <nav class='header-nav'>
          <ul class='header-nav_list'>
            <li class='header-nav_item'>
              <?php if (is_front_page()) : ?>
                <a class="js-smoothScroll header-nav_link <?php if( is_page('about') ) echo 'add-active'; ?>" href="#about" >
                  <img class="header-nav_img util-pc" src="<?php echo output_file("/assets/images/kids/common/about.png"); ?>" alt="">
                  <span class='header-nav_text'>当園について</span>
                </a>
              <?php else : ?>
                <a class="header-nav_link <?php if( is_page('about') ) echo 'add-active'; ?>" href="<?php echo home_url(); ?>/#about" >
                  <img class="header-nav_img util-pc" src="<?php echo output_file("/assets/images/kids/common/about.png"); ?>" alt="">
                  <span class='header-nav_text'>当園について</span>
                </a>
              <?php endif; ?>
            </li>
            <li class='header-nav_item'>
              <a class="header-nav_link <?php if( is_page('flow') ) echo 'add-active'; ?>" href="<?php echo home_url(); ?>/flow/" >
                <img class="header-nav_img util-pc" src="<?php echo output_file("/assets/images/kids/common/flow.png"); ?>" alt="">
                <span class='header-nav_text'>一日の流れ</span>
              </a>
            </li>
            <li class='header-nav_item'>
              <a class="header-nav_link <?php if($ancestorCat == 'news') echo 'add-active';?>" href="<?php echo home_url(); ?>/category/news/" >
                <img class="header-nav_img util-pc" src="<?php echo output_file("/assets/images/kids/common/news.png"); ?>" alt="">
                <span class='header-nav_text'>お知らせ</span>
              </a>
            </li>
            <li class='header-nav_item'>
              <a class="header-nav_link <?php if( is_page('contact') ) echo 'add-active'; ?>" href="<?php echo home_url(); ?>/contact/" >
                <img class="header-nav_img util-pc" src="<?php echo output_file("/assets/images/kids/common/contact.png"); ?>" alt="">
                <span class='header-nav_text'>お問い合わせ</span>
              </a>
            </li>
          </ul>
        </nav>
      </div>
    </div>
  </div>
</header>