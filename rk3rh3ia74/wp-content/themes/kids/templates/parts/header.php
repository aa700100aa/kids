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
<header class='header'>
  <div class='header-bg'></div>
  <div class='header-outer' id='js-headerOuter'>
    <div class='header-inner'>
    <?php if (is_front_page()) : ?>
      <div class='header-logoWrap'>
        <h1 class='header-logo'>
          <span class="header-logo_link">
            <img src="<?php echo output_file("/assets/images/kids/common/title.svg"); ?>" alt="あらかわ保育園">
          </span>
        </h1>
    <?php else : ?>
      <div class='header-logoWrap'>
        <div class='header-logo'>
          <a class="header-logo_link" href="<?php echo home_url(); ?>/" >
            <img src="<?php echo output_file("/assets/images/kids/common/title.svg"); ?>" alt="あらかわ保育園">
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
              <a class="header-nav_link <?php if( is_page('about') ) echo 'add-active'; ?>" href="<?php echo home_url(); ?>/about/" >当園について</a>
            </li>
            <li class='header-nav_item'>
              <a class="header-nav_link <?php if( is_page('flow') ) echo 'add-active'; ?>" href="<?php echo home_url(); ?>/flow/" >一日の流れ</a>
            </li>
            <li class='header-nav_item'>
              <a class="header-nav_link <?php if($ancestorCat == 'news') echo 'add-active';?>" href="<?php echo home_url(); ?>/category/news/" >お知らせ</a>
            </li>
            <li class='header-nav_item'>
              <a class="header-nav_link <?php if( is_page('contact') ) echo 'add-active'; ?>" href="<?php echo home_url(); ?>/contact/" >お問い合わせ</a>
            </li>
          </ul>
          <div class='header-sns'>
            <div class='header-sns_ttl'>Follow us</div>
            <div class='header-sns_item'>
              <a class="header-sns_link" href="https://www.instagram.com/gfd_pr/" target="_blank">
                <span class='header-sns_name util-pc'>Instagram</span>
                <img class='header-sns_img' src="<?php echo output_file("/assets/images/common/icon-instagram.svg"); ?>" alt="Instagram">
              </a>
            </div>
          </div>
        </nav>
      </div>
    </div>
  </div>
</header>