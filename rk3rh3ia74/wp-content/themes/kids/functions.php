<?php
// remove_action('template_redirect', 'redirect_canonical');
//各種読み込み
// setlocale(LC_ALL, 'ja_JP.UTF-8');
// mb_internal_encoding('UTF-8');

// require_once(get_stylesheet_directory() . '/functions/init.php');

// class themeSetupClass
// {
// 	function __construct()
// 	{
// 		/* 初期設定 */
// 		$this->create_nonce = md5(__FILE__);
// 		$this->my_nonce     = md5(__FILE__);
// 		$GLOBALS['setupThemeMVC'] = new setupThemeController(array(
// 			'view_option' => array(
// 				//URLの「category」を削除
// 				'no_category_base'   => false,
// 				//読み込む「script」
// 				'wp_enqueue_scripts' => array(
// 					'svg.min.js'     => 'https://cdnjs.cloudflare.com/ajax/libs/svg.js/2.6.5/svg.min.js',
// 					'flickity'       => 'https://unpkg.com/flickity@2/dist/flickity.pkgd.min.js',
// 					'flickity-fade'       => 'https://unpkg.com/flickity-fade@1/flickity-fade.js',
// 					'iscroll'        => (get_stylesheet_directory_uri() . '/assets/js/iscroll/iscroll.min.js'),
// 					'drawer'         => (get_stylesheet_directory_uri() . '/assets/js/drawer-master/dist/js/drawer.min.js'),
// 					'ResizeSensor'   => (get_stylesheet_directory_uri() . '/assets/js/sticky-sidebar-master/dist/ResizeSensor.js'),
// 					'sticky-sidebar' => (get_stylesheet_directory_uri() . '/assets/js/sticky-sidebar-master/dist/sticky-sidebar.js'),
// 					'theme-common'   => (get_stylesheet_directory_uri() . '/assets/js/common.js'),
// 				),
// 				//読み込む「style」
// 				'wp_enqueue_styles' => array(
// 					'dashicons'     => '',
// 					'editor-css'    => includes_url('/css/editor.min.css'),
// 					'tinymce-skins' => includes_url('/js/tinymce/skins/wordpress/wp-content.css'),
// 					'font-lato'    => 'https://fonts.googleapis.com/css?family=Lato',
// 					'flickity'      => 'https://unpkg.com/flickity@2.0.10/dist/flickity.min.css',
// 					'flickity-fade'      => 'https://unpkg.com/flickity-fade@1/flickity-fade.css',
// 					'html5reset'    => (get_stylesheet_directory_uri() . '/assets/css/html5reset-1.6.1.css'),
// 					'themecommon'   => (get_stylesheet_directory_uri() . '/assets/css/common.css'),
// 					'drawer'        => (get_stylesheet_directory_uri() . '/assets/js/drawer-master/dist/css/drawer.min.css'),
// 					'themecss'      => (get_stylesheet_directory_uri() . '/style.css'),
// 				),
// 			),
// 			'from_404_to_TOP' => true,
// 		));

// 		//テーマオプション初期値
// 		$GLOBALS['theme_options'] = new theme_options(array(
// 			'default_options' => array(
// 				'logo' => array(
// 					'label'   => 'ロゴ',
// 					'type'    => 'image',
// 				),
// 				'キービジュアル' => array(
// 					'label'   => 'キービジュアル',
// 					'type'    => 'images',
// 				),
// 				'blogname' => array(
// 					'label' => 'サイト名',
// 					'type'  => 'text',
// 				),
// 				'blogdescription' => array(
// 					'label' => 'キャッチフレーズ',
// 					'type'  => 'text',
// 				),
// 				'admin_email' => array(
// 					'label'   => 'サイトメールアドレス',
// 					'type'    => 'text',
// 					'default' => '',
// 				),
// 				'post_single_kv' => array(
// 					'label'   => '記事詳細キービジュアル',
// 					'type'    => 'image',
// 				),
// 				'copyright' => array(
// 					'label'   => 'コピーライト',
// 					'type'    => 'text',
// 					'default' => 'Copyright &copy; [bloginfo name] All Rights Reserved.',
// 				),
// 				//estdoc
// 			),
// 		));

// 		//投稿画面から不要な機能を削除します。
// 		add_action('init', array($this, 'remove_post_supports'));

// 		//管理画面メニュー編集
// 		add_action('admin_menu', array($this, 'remove_menus'), PHP_INT_MAX);

// 		//管理画面一覧 最終更新日追加
// 		add_filter('manage_edit-page_columns',          array($this, 'last_modified_admin_column'));
// 		add_filter('manage_edit-page_sortable_columns', array($this, 'sortable_last_modified_column'));
// 		add_action('manage_page_posts_custom_column',   array($this, 'last_modified_admin_column_content'), PHP_INT_MAX, 2);
// 		add_filter('manage_edit-post_columns',          array($this, 'last_modified_admin_column'));
// 		add_filter('manage_edit-post_sortable_columns', array($this, 'sortable_last_modified_column'));
// 		add_action('manage_post_posts_custom_column',   array($this, 'last_modified_admin_column_content'), PHP_INT_MAX, 2);

// 		//カスタム投稿
// 		add_action('after_setup_theme', array($this, 'after_setup_theme'));
// 	}
// 	//カスタム投稿
// 	function after_setup_theme()
// 	{
// 		//アイキャッチ
// 		add_theme_support('post-thumbnails', array('page', 'post'));
// 		add_image_size('theme_thumbnail', 620, 500, true);
// 		add_image_size('theme_large', 1920, 9999, false);

// 		//投稿
// 		$this->post = new themePostController(array(
// 			'post_type'         => 'post', //投稿のslug
// 			'post_type_label'   => 'CASE & News', //投稿のラベル
// 		));
// 	}

// 	//管理画面 一覧最終更新日追加
// 	function last_modified_admin_column($columns)
// 	{
// 		$columns['modified-last'] = __('最終更新日', 'aco');
// 		return $columns;
// 	}
// 	function sortable_last_modified_column($columns)
// 	{
// 		$columns['modified-last'] = 'modified';
// 		return $columns;
// 	}
// 	function last_modified_admin_column_content($column_name, $post_id)
// 	{
// 		if ('modified-last' == $column_name) {
// 			$modified_date = get_the_modified_time('Y年m月d日 H時i分');
// 			echo $modified_date;
// 		}
// 	}

// 	//投稿画面から不要な機能を削除します。
// 	function remove_post_supports()
// 	{
// 		remove_post_type_support('page', 'comments');
// 		remove_post_type_support('post', 'comments');
// 	}

// 	//前後の空白削除
// 	function trim_emspace($str)
// 	{
// 		if (is_array($str)) {
// 			foreach ($str as $k => $v) {
// 				if (is_array($key)) {
// 					$str[$k] = $this->trim_emspace($v);
// 				} else {
// 					// 先頭の半角、全角スペースを、空文字に置き換える
// 					$str[$k] = preg_replace('/^[ 　]+/u', '', $v);
// 					// 最後の半角、全角スペースを、空文字に置き換える
// 					$str[$k] = preg_replace('/[ 　]+$/u', '', $v);
// 				}
// 			}
// 		} else {
// 			// 先頭の半角、全角スペースを、空文字に置き換える
// 			$str = preg_replace('/^[ 　]+/u', '', $str);
// 			// 最後の半角、全角スペースを、空文字に置き換える
// 			$str = preg_replace('/[ 　]+$/u', '', $str);
// 		}
// 		return $str;
// 	}

//管理画面メニュー編集
function remove_menus()
{
  global $menu, $submenu;
  //test($submenu);
  //remove_menu_page( 'index.php' );                  // ダッシュボード
  //remove_menu_page( 'edit.php' );                   // 投稿
  //remove_menu_page( 'upload.php' );                 // メディア
  //remove_menu_page( 'edit.php?post_type=page' );    // 固定ページ
  remove_menu_page('edit-comments.php');          // コメント
  //remove_menu_page( 'themes.php' );                 // 外観
  //remove_menu_page( 'plugins.php' );                // プラグイン
  //remove_menu_page( 'users.php' );                  // ユーザー
  //remove_menu_page( 'tools.php' );                  // ツール
  //remove_menu_page( 'options-general.php' );        // 設定
  remove_submenu_page('edit.php', 'edit-tags.php?taxonomy=post_tag'); // 投稿 タグ
  remove_submenu_page('edit.php?post_type=page', 'post-new.php?post_type=page'); // 固定ページ 新規追加
  remove_submenu_page('edit.php?post_type=service-handling', 'post-new.php?post_type=service-handling'); // 取り扱いメーカー 新規追加
  // //カスタムメニュー
  // remove_menu_page('edit.php?post_type=smart-custom-fields');
  // //debag
  // remove_submenu_page('options-general.php', 'debug-bar-extender');
}
add_action('admin_menu', 'remove_menus');
// }

// $GLOBALS['themeSetupClass'] = new themeSetupClass();



/* ---------------------------------------
ここからQの記述
--------------------------------------- */

/*-------- 
デフォルトで入るhead内の要素
---------*/

//ページ上部の更新バーを削除
add_filter('show_admin_bar', '__return_false');

// グーテンベルグのデフォルトのCSSを削除
function remove_block_library_css()
{
  wp_dequeue_style('wp-block-library');
}
// グローバルスタイルを削除
function remove_my_global_styles()
{
  wp_dequeue_style('global-styles');
}
// クラシックテーマのスタイルを削除
function remove_classic_theme_style()
{
  wp_dequeue_style('classic-theme-styles');
}

// Robots API 削除
remove_filter('wp_robots', 'wp_robots_max_image_preview_large');

// restAPI無効化
function remove_unwanted_links()
{
  // 不要なリンク要素の無効化
  // 他のリンク要素の無効化も必要な場合は、同様に追加
  remove_action('wp_head', 'rest_output_link_wp_head');                // REST APIへのリンクを無効化
  remove_action('wp_head', 'wp_oembed_add_discovery_links');           // oEmbedディスカバリーリンクを無効化
  remove_action('wp_head', 'wp_resource_hints', 2);                    // リソースのヒントを無効化
  remove_action('wp_head', 'rsd_link');                                 // Really Simple Discovery (RSD) リンクを無効化
  remove_action('wp_head', 'wlwmanifest_link');                         // Windows Live Writer リンクを無効化
  remove_action('wp_head', 'wp_generator');                             // WordPressのバージョン情報を無効化
  remove_action('wp_head', 'wp_shortlink_wp_head');                     // 短縮リンクの表示を無効化
  remove_action('wp_head', 'print_emoji_detection_script', 7);          // 絵文字スクリプトの無効化
  remove_action('wp_print_styles', 'print_emoji_styles', 10);           // 絵文字スタイルの無効化
}

// まとめて実行
function custom_cleanup_functions()
{
  remove_block_library_css();
  remove_my_global_styles();
  remove_classic_theme_style();
  remove_unwanted_links();
}
add_action('wp_enqueue_scripts', 'custom_cleanup_functions');
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'feed_links_extra', 3);

/*-------- 
不要な固定ページを消す
---------*/
function hide_specific_pages($query)
{
  global $pagenow;
  if (is_admin() && $pagenow == 'edit.php' && $_GET['post_type'] == 'page') {
    $query->query_vars['post__not_in'] = array(108, 2836, 2834); // 非表示にしたい固定ページのIDを配列で指定
  }
}
add_filter('parse_query', 'hide_specific_pages');



/*-------- 
META出力
---------*/

// title： 出力結果
function get_meta_title()
{
  // サイト名
  $top_id = get_page_by_path('front-page');
  $site_name = get_post($top_id)->post_title;
  $title = '';


  // // お知らせ | 実績紹介
  if (is_category()) {
    $current_category = get_queried_object();
    if ($current_category->parent == 0) {
      // 親カテゴリのページのとき
      if ($current_category->name == 'お知らせ') {
        $current_title = 'お知らせ';
      } elseif ($current_category->name == 'CASE') {
        $current_title = '実績紹介';
      }
      $title = $current_title . ' | ' . get_bloginfo('name');
    } else {
      // 子カテゴリのページのとき
      $current_title = $current_category->name;
      $parent_category = get_term($current_category->parent, 'category');
      if ($parent_category->name == 'お知らせ') {
        $parent_title = 'お知らせ';
      } elseif ($parent_category->name == 'CASE') {
        $parent_title = '実績紹介';
      }
      $title = $current_title . ' | ' . $parent_title . ' | ' . get_bloginfo('name');
    }
    return $title;
  }

  // その他
  if (is_home() || is_front_page()) {
    $site_name = get_bloginfo('name');
    $title = $site_name . ' | ' . '「たのしく げんきに すこやかに」を保育目標とし、お子様を第一優先に考える保育園';
    return $title;
  }
  if (is_page('contact') || is_page('confirm') || is_page('complete')) {
    $page_id = get_page_by_path('contactdesc');  $page = get_post($page_id);
    $title = $page->post_title . ' | ' . (is_front_page() ? get_bloginfo('description') : get_bloginfo('name'));
    return $title;
  }
  // 404
  if (is_404()) {
    $site_name = get_bloginfo('name'); // サイトのタイトルを取得
    $title = 'お探しのページが見つかりませんでした | ' . $site_name;
    return $title;
  }


  return $title;
}

// description： 出力結果
function get_meta_description()
{
  // // お知らせ | 実績紹介
  if (is_category()) {
    $current_category = get_queried_object();
    if ($current_category->parent == 0) {
      $page_path = ($current_category->name == 'お知らせ') ? 'newsdesc' : 'casedesc';
      $page_data = get_page_by_path($page_path);
      $page = get_post($page_data);
      $content = $page->post_content;

      if ($current_category->name == 'お知らせ') {
        $description = 'あらかわ保育園のお知らせに関する一覧ページです。';
      } elseif ($current_category->name == 'CASE') {
        $description = 'あらかわ保育園の実績紹介に関する一覧ページです。';
      }
    } else {
      $current_title = $current_category->name;
      $parent_category = get_term($current_category->parent, 'category');
      $page_path = ($parent_category->name == 'お知らせ') ? 'newsdesc' : 'casedesc';
      $page_data = get_page_by_path($page_path);
      $page = get_post($page_data);
      $content = $page->post_content;

      if ($parent_category->name == 'お知らせ') {
        $description = 'お知らせの' . $current_title . 'に関する一覧ページです。';
      } elseif ($parent_category->name == 'CASE') {
        $description = '実績紹介の' . $current_title . 'に関する一覧ページです。';
      }
    }

    return $description;
  } else {
    // トップページ
    if (is_front_page()) {
      $description_txt = '私たちは保育目標を達成するために日々丁寧な保育を心がけています。子どもたちが成長していけるような保育園を職員みんなでつくっています。';
    }
    // お問い合わせ
    if (is_page('contact') || is_page('confirm') || is_page('complete')){
      $page_id = get_page_by_path('contactdesc');
      $page = get_post($page_id);
      $description_txt = $page->post_content;
    }
  }

  // 表示に不都合な改行や空白などを除外
  $exclud_string = array("&nbsp;", "\r\n", "\r", "\n", "\t", "\"");
  $text = str_replace($exclud_string, '', $description_txt);

  // 120文字以上になったら"..."で表示
  $description = mb_substr(strip_tags($text), 0, 120, 'UTF-8');
  $desc_strlen = mb_strlen($description, 'UTF-8');
  if ($desc_strlen >= 120) {
    $description = $description . '...';
  }

  return $description;
}

// canonical： 出力結果
remove_action('wp_head', 'rel_canonical');
function get_meta_canonical()
{
  // 現在のURLを取得
  $url = (empty($_SERVER["HTTPS"]) ? "http://" : "https://") . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];

  // 2ページ目以降の場合
  if(is_paged()){
    //URLからpageというワード以降を削除
    $url = mb_strstr( $url, 'page', true);
  }

  // クエリパラメータがある場合、それを省く
  $parsed_url = parse_url($url);
  if (isset($parsed_url["query"])) {
    $url = str_replace('?' . $parsed_url["query"], '', $url);
  }
  return $url;
}

// og:type： 出力結果
function get_meta_og_type()
{
  return is_front_page() ? 'website' : 'article';
}

// og:image： 出力結果
remove_filter('wp_robots', 'wp_robots_max_image_preview_large');

function get_first_image_from_post_content($post_content)
{
  preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post_content, $matches);
  if (!empty($matches[1][0])) {
    return $matches[1][0];
  }
  return false;
}

function get_meta_og_img()
{
  return get_template_directory_uri() . '/assets/images/kids/ogp.png';
}




// og:published_time, modified_time： 出力結果
function get_meta_og_time()
{
  $dates = array(
    'published_time' => get_the_time('c'),
    'modified_time' => get_the_modified_date('c')
  );
  return $dates;
}


function set_meta_tag()
{
  $top_id = get_page_by_path('front-page');
  echo $top_id;

  // if (is_category()) {
  //   $current_category = get_queried_object();
  //   if ($current_category->parent == 0) {
  //     // 親カテゴリのページのとき
  //     if ($current_category->name == 'NEWS') {
  //       $current_title = 'お知らせ';
  //     } elseif ($current_category->name == 'CASE') {
  //       $current_title = '実績紹介';
  //     }
  //     $site_name = $current_title . ' | ' . get_bloginfo('description');
  //   } else {
  //     // 子カテゴリのページのとき
  //     $current_title = $current_category->name;
  //     $parent_category = get_term($current_category->parent, 'category');
  //     if ($parent_category->name == 'NEWS') {
  //       $parent_title = 'お知らせ';
  //     } elseif ($parent_category->name == 'CASE') {
  //       $parent_title = '実績紹介';
  //     }
  //     $site_name = $current_title . ' | ' . $parent_title . ' | ' . get_bloginfo('description');
  //   }
  // } else {
  //   $site_name = get_post($top_id)->post_title . ' | ' . get_bloginfo('description');
  // }

  $title = get_meta_title();
  $desc = get_meta_description();
  $url = get_meta_canonical();
  $og_type = get_meta_og_type();
  $og_img = get_meta_og_img();
  $og_published_time = get_meta_og_time()['published_time'];
  $og_modified_time = get_meta_og_time()['modified_time'];
  $front_page_id = get_option('page_on_front');

  if (is_404() || is_page('complete') || is_page('confirm')) {
    echo <<<END
    <title>$title</title>
      <meta property="og:image" content="$og_img">
    END;
  } else {
    echo <<<END
    <title>$title</title>
      <meta name="description" content="$desc">
      <link rel="canonical" href="$url">
      <meta property="og:locale" content="ja_JP">
      <meta property="og:site_name" content="あらかわ保育園">
      <meta property="og:title" content="$title">
      <meta property="og:type" content="$og_type">
      <meta property="og:description" content="$desc">
      <meta property="og:url" content="$url">
      <meta property="og:image" content="$og_img">\n
    END;
  }

  if (is_category()) {
    echo <<<END
      <meta property="article:published_time" content="$og_published_time">
      <meta property="article:modified_time" content="$og_modified_time">\n
    END;
  }
}



//クエリパラメータにタイムスタンプ付きのファイル出力
// TEMPLATE_DIRにテンプレートまでのディレクトリパスを代入
define('TEMPLATE_DIR', get_template_directory_uri());

// クエリパラメータにタイムスタンプ付きのファイル出力を行う関数
function output_file($file_path)
{
  date_default_timezone_set('Asia/Tokyo');
  $full_path = TEMPLATE_DIR . $file_path;
  $timestamp = date('Ymdhi', filemtime(get_theme_file_path($file_path)));
  return $full_path . '?' . $timestamp;
}


//bodyタグのid付け替え
function set_body_id()
{
  $id = '';

  if (is_home() || is_front_page()) {
    $id = 'top';
  } elseif (is_category()) {
    $id = 'posts';
  } elseif (is_page('contact')) {
    $id = 'contact';
  } elseif (is_page('confirm')) {
    $id = 'confirm';
  } elseif (is_page('complete')) {
    $id = 'complete';
  } elseif (is_page('flow')) {
    $id = 'flow';
  } elseif (is_404()) {
    $id = 'notFound';
  } elseif (is_single('post')) {  //カスタム投稿タイプ名はまだ不明なので仮あて
    $id = 'post';
  }
  return $id;
}

/*-------- 
  contact
  ---------*/
// mw wp formの自動brタグ削除
function mvwpform_autop_filter()
{
  if (class_exists('MW_WP_Form_Admin')) {
    $mw_wp_form_admin = new MW_WP_Form_Admin();
    $forms = $mw_wp_form_admin->get_forms();
    foreach ($forms as $form) {
      add_filter('mwform_content_wpautop_mw-wp-form-107' . $form->ID, '__return_false');
    }
  }
}
mvwpform_autop_filter();

// カスタム投稿タイプのビジュアルエディタ無効
function disable_visual_editor_in_page()
{
  global $typenow;
  if ($typenow == 'mw-wp-form') {
    add_filter('user_can_richedit', 'disable_visual_editor_filter');
  }
}
function disable_visual_editor_filter()
{
  return false;
}
add_action('load-post.php', 'disable_visual_editor_in_page');
add_action('load-post-new.php', 'disable_visual_editor_in_page');



/*-------- 
記事詳細
---------*/
function check_browser_history()
{
  $pre_url = $_SERVER['HTTP_REFERER'];
  //ひとつ前のブラウザの履歴に本サイトのドメインが含まれる場合
  if (!empty($pre_url) && strstr($pre_url, home_url()) != false) {
    return true;
  } else {
    return false;
  }
}
// add_action( 'load-post.php', 'disable_visual_editor_in_page' );
// add_action( 'load-post-new.php', 'disable_visual_editor_in_page' );


add_action('load-post.php', 'disable_html_editor_in_post');
add_action('load-post-new.php', 'disable_html_editor_in_post');
function disable_html_editor_in_post()
{
  global $typenow;
  if ($typenow == 'post') {
    add_filter('wp_editor_settings', function ($settings) {
      if (user_can_richedit())
        $settings['quicktags'] = false;
      return $settings;
    });
  }
}

// 前後の投稿を取得
function twpp_get_adjacent_post_url($previous = true)
{
  $post = get_adjacent_post(false, '', $previous);
  $url  = '';
  if (!empty($post)) {
    $url = get_permalink($post->ID);
  }
  return $url;
}




// /*-------- 
// 管理画面のJS,CSSをいじる場合
// ---------*/
// // 管理画面のCSSを追加する関数を定義
// function mystyle_admin_enqueue($hook)
// {
//   // 読み込む管理ページフックの配列を変数として定義
//   $admin_pages = array("edit.php", "edit-tags.php", "term.php", "post.php", "post-new.php");

//   // 該当する管理ページ以外では読み込まない
//   if (!in_array($hook, $admin_pages)) return;

//   // CSSファイルを読み込む
//   wp_enqueue_style('my_admin_style', TEMPLATE_DIR . '/assets/css/admin.css');
// }
// add_action('admin_enqueue_scripts', 'mystyle_admin_enqueue');

// // 管理画面のJavaScriptを追加する関数を定義
// function myscript_admin_enqueue($hook)
// {
//   // 読み込む管理ページフックの配列を変数として定義
//   $admin_pages = array("edit.php", "edit-tags.php", "term.php", "post.php", "post-new.php");

//   // 該当する管理ページ以外では読み込まない
//   if (!in_array($hook, $admin_pages)) return;

//   // JavaScriptファイルを読み込む
//   wp_enqueue_script('my_admin_script', TEMPLATE_DIR . '/assets/js/admin.js', array(), '1.0.0', true);
// }
// add_action('admin_enqueue_scripts', 'myscript_admin_enqueue');

/**
 * 管理画面の「会社情報」、「採用情報」のサブメニューを非表示
 */
function remove_submenu()
{
  //サブメニューを非表示する
  remove_submenu_page('edit.php?post_type=employment-info', 'post-new.php?post_type=employment-info');
  remove_submenu_page('edit.php?post_type=company-info', 'post-new.php?post_type=company-info');
}
//管理画面でremove_submenu()を実行
add_action('admin_menu', 'remove_submenu');


/*-------- 
管理画面のカテゴリの順番に階層を持たせる
---------*/
function solecolor_wp_terms_checklist_args($args, $post_id)
{
  if ($args['checked_ontop'] !== false) {
    $args['checked_ontop'] = false;
  }
  return $args;
}
add_filter('wp_terms_checklist_args', 'solecolor_wp_terms_checklist_args', 10, 2);


/**
 * 構造化データ追加
 */
function append_structure_data()
{
  global $jsonld, $basicCompanyInfo;
  $jsonld = array();
  $basicCompanyInfo = array(
    'homeUrl' => esc_url(home_url('/')),
    'currentUrl' => esc_url(get_the_permalink()),
    'companyName' => 'あらかわ保育園',
    'companyLogo' => esc_url(get_template_directory_uri() . '/assets/images/kids/common/image_object.png')
  );

  function create_type_of_organization()
  {
    global $jsonld, $basicCompanyInfo;
    $jsonld['organization'] = array(
      '@context' => 'http://schema.org',
      '@type' => 'Organization',
      'name' => $basicCompanyInfo['companyName'],
      'url' => $basicCompanyInfo['homeUrl'],
      'logo' => $basicCompanyInfo['companyLogo'],
      'address' => array(
        '@type' => 'PostalAddress',
        'streetAddress' => 'XX-XX-XX',
        'addressLocality' => 'XXX市XXX区',
        'addressRegion' => '神奈川県',
        'postalCode' => 'XXX-XXXX',
        'addressCountry' => 'JP'
      ),
      'contactPoint' => array(
        '@type' => 'ContactPoint',
        'telephone' => '+81-00-000-0000',
        'contactType' => 'customer support'
      )
    );
  };

  function create_type_of_article()
  {
    global $jsonld, $basicCompanyInfo;
    $jsonld['article'] = array(
      '@context' => 'http://schema.org',
      '@type' => 'Article',
      'headline' => esc_html(get_meta_title()),
      'description' => esc_html(get_meta_description()),
      'mainEntityOfPage' => array(
        '@type' => 'WebPage',
        '@id' => esc_html(get_meta_canonical()),
      ),
      'datePublished' => get_the_date('Y-m-d') . "T" . mb_substr(get_the_time(), 0, 5) . ":00+09:00",
      'dateModified' => get_the_modified_date('Y-m-d') . "T" . mb_substr(get_the_modified_time(), 0, 5) . ":00+09:00",
      'author' => array(
        '@type' => 'Organization',
        'name' => esc_html(get_bloginfo('name')),
        'url' => $basicCompanyInfo['homeUrl']
      ),
      'image' => array(
        '@type' => 'ImageObject',
        'url' => esc_url(get_template_directory_uri() . '/assets/images/kids/common/image_object.png')
      )
    );
  };

  function create_type_of_breadcrumbList()
  {
    global $jsonld, $basicCompanyInfo;
    $itemListElement = array();
    $wp_obj = get_queried_object();
    $page_id = $wp_obj->ID;
    $position = 1;
    $itemListElement[] = array(
      "@type"    => "ListItem",
      "position" => $position++,
      "name" => "TOP",
      "item"  => esc_url(home_url('/'))
    );
    if (is_page()) {
      $page_title = apply_filters('the_title', $wp_obj->post_title);
      $itemListElement[] = array(
        "@type"    => "ListItem",
        "position" => $position++,
        "name" => esc_html($page_title),
        "item"  => $basicCompanyInfo['currentUrl']
      );
    };
    if (is_category()) {
      $wp_obj = get_queried_object();
      if ($wp_obj->parent != 0) {
        $parent_ID = $wp_obj->parent;
        $itemListElement[] = array(
          "@type"    => "ListItem",
          "position" => $position++,
          "name" => esc_html(get_category($parent_ID)-> name),
          "item"  => esc_url(get_category_link($parent_ID))
        );
        $itemListElement[] = array(
          "@type"    => "ListItem",
          "position" => $position++,
          "name" => esc_html($wp_obj-> name),
          "item"  => esc_url(get_category_link($wp_obj->term_id))
        );
      } else {
        $itemListElement[] = array(
          "@type"    => "ListItem",
          "position" => $position++,
          "name" => esc_html($wp_obj-> name),
          "item"  => esc_url(get_category_link($wp_obj->term_id))
        );
      }
    }
    if (is_single()) {
      $page_title = apply_filters('the_title', $wp_obj->post_title);
      $breaklist = get_the_category();
      foreach ($breaklist as $key => $val) {
        $breaklist[count(get_ancestors($val->term_id, 'category'))] = [
          'name' => $val->cat_name,
          'id' => $val->cat_ID
        ];
      }
      //親カテゴリ
      $itemListElement[] = array(
        "@type"    => "ListItem",
        "position" => $position++,
        "name" => esc_html(get_category($breaklist[0]['id'])-> name),
        "item"  => esc_url(get_category_link($breaklist[0]['id']))
      );
      //子カテゴリ
      $itemListElement[] = array(
        "@type"    => "ListItem",
        "position" => $position++,
        "name" => esc_html($breaklist[1]['name']),
        "item"  => esc_url(get_category_link($breaklist[1]['id']))
      );
      //今見てるページの情報
      $itemListElement[] = array(
        "@type"    => "ListItem",
        "position" => $position++,
        "name" => esc_html($page_title),
        "item"  => esc_url(get_meta_canonical())
      );
    }

    //構造化データ作成
    $jsonld['breadcrumbList'] = array(
      '@context' => 'http://schema.org',
      '@type' => 'BreadcrumbList',
      'itemListElement' => $itemListElement
    );
  }

  if (!is_page('confirm') && !is_page('complete') && !is_404()) {
    create_type_of_organization();
    if (!is_front_page()) {
      create_type_of_breadcrumbList();
    }
  }
  if (is_single()) {
    create_type_of_article();
  }
  foreach ($jsonld as $data) {
    echo '<script type="application/ld+json">' . PHP_EOL;
    echo json_encode($data, JSON_UNESCAPED_UNICODE  | JSON_PRETTY_PRINT) . PHP_EOL;
    echo '</script>' . PHP_EOL;
  }
}
add_action('wp_head', 'append_structure_data');

/*-------- 
アイキャッチ設定有効
---------*/
add_theme_support('post-thumbnails');

/*-------- 
サムネイル取得処理処理
---------*/
function get_image_as_thumbnail($post_id, $size) {
  $eye_catch = get_the_post_thumbnail_url($post_id, $size);
  if($eye_catch) {
    // アイキャッチが設定されていたら
    return $eye_catch;
  } else {
    // アイキャッチが設定されていなかったら
    $post = get_post($post_id);
    // 本文（コンテンツ）から画像を抽出
    $content = $post->post_content;
    preg_match('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $content, $matches);

    if ($matches && isset($matches[1])) {
      // 画像があったら
      return $matches[1];
    } else {
      // 画像がなかったら
      return output_file("/assets/images/kids/posts/thumb_logo.png");
    }
  }
}