<?php
/* Model */
class setupThemeModel {
    function __construct($init = array()){
        //private
        //add_filter('pre_get_posts',array($this,'pre_get_posts'));
        //固定ページ管理画面カスタマイズ
        add_action('add_meta_boxes', array($this,'add_meta_boxes'),10,2);
        add_action('save_post',      array($this,'save_post'));

        //検索ページ
        add_action('after_setup_theme',      array($this,'search_ridirect'));
        add_filter('template_include',       array($this,'template_include'),1);
        add_filter('posts_search',           array($this,'posts_search'),10,2);
        add_filter('pre_get_posts',          array($this,'pre_get_posts'));
        //add_action('generate_rewrite_rules', array($this,'generate_rewrite_rules'));
        // ハンドル名 hatebu の wp_enqueue_script に async 属性を追加する。
        //add_filter('script_loader_tag',array($this,'script_loader_tag'),10,2);
        //テーマオプションの設定
        add_action('admin_menu',array($this,'admin_menu'));
        //リビジョン自動保存停止
        add_action('wp_print_scripts',array($this,'disable_autosave'));
        //固定ページビジュアルエディター無効
        add_action('load-post.php',     array($this,'disable_visual_editor_in_page'));
        add_action('load-post-new.php', array($this,'disable_visual_editor_in_page'));
        //All-in-One WP Migration
        add_action('admin_enqueue_scripts',array($this,'admin_enqueue_scripts_All_in_One_WP_Migration'));
    }
    //All-in-One WP Migration
    function admin_enqueue_scripts_All_in_One_WP_Migration(){
        wp_enqueue_style('All-in-One WP Migration',get_stylesheet_directory_uri().'/functions/setup_theme/assets/css/All_in_One_WP_Migration.css');
    }
    //固定ページビジュアルエディター無効
    function disable_visual_editor_in_page() {
        global $typenow;
        if( $typenow == 'page' ){
            add_filter('user_can_richedit',array('setupThemeModel','disable_visual_editor_filter'));
        }
    }
    static function disable_visual_editor_filter(){
        return false;
    }
    //リビジョン自動保存停止
    function disable_autosave() {
        wp_deregister_script('autosave');
    }
    //テーマオプションの設定
    function admin_menu(){
        $hook = add_menu_page('phpinfo','PHPINFO','edit_others_posts','phpinfo',array($this,'phpinfo'),'dashicons-info',4);
    }
    function phpinfo(){
        ob_start();
        phpinfo();
        $buffer = ob_get_contents();
        ob_end_clean();
        $phpQueryObj = phpQuery::newDocument($buffer);
        echo '<div id="phpinfodata">'.$phpQueryObj["body"]->html().'</div>';
        ?><style type="text/css">
            #phpinfodata {font-size: 1rem;margin: 5em 0;color: #222; font-family: sans-serif;}
            #phpinfodata pre {margin: 0; font-family: monospace;}
            #phpinfodata a:link {color: #009; text-decoration: none; background-color: #fff;}
            #phpinfodata a:hover {text-decoration: underline;}
            #phpinfodata table {border-collapse: collapse; border: 0; width: 934px; box-shadow: 1px 2px 3px #ccc;}
            #phpinfodata .center {text-align: center;}
            #phpinfodata .center table {margin: 1em auto; text-align: left;border: 1px solid #666;}
            #phpinfodata .center th {text-align: center !important;}
            #phpinfodata td, #phpinfodata th {word-break: break-all;border: 1px solid #666; font-size: 75%; vertical-align: baseline; padding: 4px 5px;}
            #phpinfodata h1 {font-size: 150%;}
            #phpinfodata h2 {font-size: 125%;}
            #phpinfodata h1 a, #phpinfodata h2 a {color: #222;}
            #phpinfodata .p {text-align: left;}
            #phpinfodata .e {background-color: #ccf; width: 300px; font-weight: bold;}
            #phpinfodata .h {background-color: #99c; font-weight: bold;}
            #phpinfodata .v {background-color: #ddd; max-width: 300px; overflow-x: auto;}
            #phpinfodata .v i {color: #999;}
            #phpinfodata img {float: right; border: 0;}
            #phpinfodata hr {width: 934px; background-color: #ccc; border: 0; height: 1px;}
        </style><?php
    }
    //private
    function pre_get_posts($wp_query){
        if(!is_admin() && is_user_logged_in()){
            //$wp_query->set('post_status',array('publish','private',));
        }
        if($wp_query->is_main_query() && isset($wp_query->query['s']) && !is_admin()){
            $wp_query->set('post_type',array('post','news'));
            $wp_query->is_search = true;
            $wp_query->is_404    = false;
        }
        return $wp_query;
    }
    //検索ページ
    function search_ridirect(){
        global $wp_rewrite;
        if(is_admin())return;
        if(isset($_GET['s']) && get_search_link($_GET['s']) != ((empty($_SERVER["HTTPS"]) ? "http://" : "https://").$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"])){
            wp_redirect(get_search_link($_GET['s']),301);
            exit;
        }
        add_action('wp_enqueue_scripts',array($this,'search_wp_enqueue_scripts'));
    }
    function search_wp_enqueue_scripts(){
        global $wp_rewrite;
        wp_enqueue_script('search-action',get_stylesheet_directory_uri().'/functions/setup_theme/assets/js/search.js',array('jquery'),'',true);
        wp_localize_script('search-action','$searchform',array(
            'home_url'         => home_url(),
            'site_url'         => site_url(),
            'search_link'      => (mb_substr(get_search_link(),-1) != '/' && !empty($wp_rewrite->front)?get_search_link().'/':get_search_link()),
            'wp_rewrite_front' => (!empty($wp_rewrite->front)?'/':''),
            'wp_ajax'          => admin_url('admin-ajax.php'),
        ));
    }
    function template_include($template){
        global $wp_query;
        //test($wp_query);
        if(((isset($wp_query->query['name']) && $wp_query->query['name'] == 'search') || (isset($wp_query->query['pagename']) && $wp_query->query['pagename'] == 'search'))){
            if(file_exists(get_stylesheet_directory().'/search.php')){
                $template = get_stylesheet_directory().'/search.php';
            }elseif(file_exists(get_stylesheet_directory().'/templates/search.php')){
                $template = get_stylesheet_directory().'/templates/search.php';
            }
        }
        return $template;
    }
    function posts_search($search,$wp_query){
        //query['s']があったら検索ページ表示
        if($wp_query->is_main_query() && isset($wp_query->query['s']) && !is_admin()){
            $wp_query->set('post_type',array('post','news'));
            $wp_query->is_search = true;
            $wp_query->is_404    = false;
        }
        return $search;
    }
    /*function generate_rewrite_rules($wp_rewrite){
        remove_action('generate_rewrite_rules', array($this,'generate_rewrite_rules'));
        test($wp_rewrite->rewrite_rules());
        $non_wp_rules = array(
            'assets/(.*)'  => 'wp-content/themes/twentyfourteen/assets/$1',
        );
        $wp_rewrite->non_wp_rules = $non_wp_rules + $wp_rewrite->non_wp_rules;
        return $search_rewrite;
    }*/
    //固定ページ管理画面カスタマイズ
    function add_meta_boxes($post_type,$post){
        //固定ページ（フロントページ）
        if($post_type == 'page' && get_option('show_on_front') == 'page' && get_option('page_on_front') == $post->ID){
            //remove_post_type_support('page','editor'); // 本文欄
            add_action('edit_form_after_title',function(){
                echo '<div class="notice notice-warning inline"><p>フロントページを編集中です。</p></div>';
            });
        }elseif($post_type == 'page' && get_option('show_on_front') == 'page' && get_option('page_for_posts') == $post->ID){
            add_meta_box('setupThemeModel-posts_per_page','1ページに表示する最大投稿数',array($this,'add_meta_box_posts_per_page'),'page','normal','high');
        }
    }
    function add_meta_box_posts_per_page(){
        // 認証に nonce を使う
        wp_nonce_field(wp_create_nonce(md5('setupThemeModel-posts_per_page')),md5('setupThemeModel-posts_per_page'));
        // データ入力用の実際のフォーム
        ?><p><input type="number" name="post_type_posts_per_page" value="<?php echo esc_attr(get_option("posts_per_page",10)); ?>"></p><?php
    }
    function save_post($post_id){
        //2度処理をしないように
        remove_action('save_post',array('setupThemeModel','save_post'));

        global $post;
        $my_nonce = filter_input(INPUT_POST,md5('setupThemeModel-posts_per_page'))?filter_input(INPUT_POST,md5('setupThemeModel-posts_per_page')):null;
        if(!wp_verify_nonce($my_nonce,wp_create_nonce(md5('setupThemeModel-posts_per_page')))){ return $post_id; }
        if(wp_is_post_revision($post_id)){ return $post_id; }
        if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE){ return $post_id; }
        if(!current_user_can('edit_post', $post->ID)){ return $post_id; }

        //1ページに表示する最大投稿数
        if(intval($_POST['post_type_posts_per_page']) && get_option('posts_per_page') != intval($_POST['post_type_posts_per_page']) && intval($_POST['post_type_posts_per_page']) > 0){
            update_option('posts_per_page',intval($_POST['post_type_posts_per_page']));
        }
        //return $post_id;
    }
    // ハンドル名 hatebu の wp_enqueue_script に async 属性を追加する。
    function script_loader_tag($tag,$handle){
        if(is_admin()){ return $tag; }
        if('jquery' == $handle ){ return $tag; }
        return str_replace( ' src', ' async="async" src', $tag );
    }
}
//model
$GLOBAL['setupThemeModel'] = new setupThemeModel();

/* View */
class setupThemeView {
    function __construct($init = array()){
        foreach($init as $k => $v)$this->$k = $v;
        require_once(dirname(__FILE__).'/ua.class.php');
        $GLOBALS['UserAgent'] = new UserAgent;
        //javascript
        add_action('admin_enqueue_scripts',array($this,'admin_enqueue_scripts'),PHP_INT_MAX);
        //javascript
        add_action('wp_enqueue_scripts',array($this,'wp_enqueue_scripts'),PHP_INT_MAX);
        //style
        if(!empty($this->wp_enqueue_styles)){
            add_action('wp_enqueue_scripts',array($this,'wp_enqueue_styles'),PHP_INT_MAX);
        }
        //body_class
        add_filter('body_class',array($this,'body_class'),10,2);
        //post_class
        add_filter('post_class',array($this,'post_class'),10,3);
        //テーマセットアップ
        add_action('after_setup_theme',array($this,'after_setup_theme'));
        //全ての「?ver=~」を削除する
        //add_filter('style_loader_src',array($this,'vc_remove_wp_ver_css_js'),PHP_INT_MAX);
        //add_filter('script_loader_src',array($this,'vc_remove_wp_ver_css_js'),PHP_INT_MAX);
        //excerpt
        if(!empty($this->excerpt_length)){
            add_filter('excerpt_length',array('WpHead','excerpt_length'),PHP_INT_MAX);
        }
        if(!empty($this->excerpt_more)){
            add_filter('excerpt_more',array($this,'excerpt_more'));
        }
        //パーマリンクカテゴリ削除
        if(!empty($this->no_category_base)){
            add_action('created_category',       array($this,'no_category_base_refresh_rules'));
            add_action('delete_category',        array($this,'no_category_base_refresh_rules'));
            add_action('edited_category',        array($this,'no_category_base_refresh_rules'));
            add_action('init',                   array($this,'no_category_base_permastruct'));
            add_filter('category_rewrite_rules', array($this,'no_category_base_rewrite_rules'));
            add_filter('query_vars',             array($this,'no_category_base_query_vars'));
            add_filter('request',                array($this,'no_category_base_request'));
        }
        //リンク集復活
        if(!empty($this->post_link)){
            add_filter('pre_option_link_manager_enabled','__return_true');
        }
        //管理画面
        add_filter('admin_menu',array($this,'admin_menu'),PHP_INT_MAX);
        //他人の画像閲覧不可
        if(!empty($this->others_attachments)){
            add_action('ajax_query_attachments_args',array($this,'ajax_query_attachments_args'));
            add_action('pre_get_posts',array($this,'pre_get_posts'));
        }
        //タイトル
        add_filter('document_title_separator',array($this,'document_title_separator'));
    }
    //タイトル
    function document_title_separator( $sep ) {
        $sep = '｜';
        return $sep;
    }
    //他人の画像閲覧不可
    function ajax_query_attachments_args($query){
        if(!current_user_can('edit_theme_options')){
            if ( $user = wp_get_current_user() ) {
                $query['author'] = $user->ID;
            }
        }
        return $query;
    }
    function pre_get_posts( $wp_query ) {
        if( is_admin() && ( $wp_query->is_main_query() || ( defined( 'DOING_QUERY_ATTACHMENT' ) && DOING_QUERY_ATTACHMENT ) ) && $wp_query->get( 'post_type' ) == 'attachment' && !current_user_can('edit_theme_options')) {
            $user = wp_get_current_user();
            $wp_query->set( 'author', $user->ID );
        }
    }
    //管理画面
    function admin_menu(){
        //外観　カスタマイズ　非表示
        $customize_url_arr = array();
        // 3.x
        $customize_url_arr[] = 'customize.php';
        $customize_url = add_query_arg( 'return', urlencode( wp_unslash( $_SERVER['REQUEST_URI'] ) ), 'customize.php' );
        // 4.0 & 4.1
        $customize_url_arr[] = $customize_url;
        if ( current_theme_supports( 'custom-header' ) && current_user_can( 'customize') ) {
            // 4.1
            $customize_url_arr[] = add_query_arg( 'autofocus[control]', 'header_image', $customize_url );
            // 4.0
            $customize_url_arr[] = 'custom-header';
        }
        if ( current_theme_supports( 'custom-background' ) && current_user_can( 'customize') ) {
            // 4.1
            $customize_url_arr[] = add_query_arg( 'autofocus[control]', 'background_image', $customize_url );
            // 4.0
            $customize_url_arr[] = 'custom-background';
        }
        foreach ( $customize_url_arr as $customize_url ) {
            remove_submenu_page( 'themes.php', $customize_url );
        }
    }
    //パーマリンクカテゴリ削除
    function no_category_base_refresh_rules() {
        global $wp_rewrite;
        $wp_rewrite->flush_rules();
    }
    function no_category_base_deactivate() {
        remove_filter('category_rewrite_rules','no_category_base_rewrite_rules');
        no_category_base_refresh_rules();
    }
    function no_category_base_permastruct() {
        global $wp_rewrite;
        global $wp_version;
        if ( $wp_version >= 3.4 ) {
            $wp_rewrite->extra_permastructs['category']['struct'] = '%category%';
        } else {
            $wp_rewrite->extra_permastructs['category'][0] = '%category%';
        }
    }
    function no_category_base_rewrite_rules($category_rewrite) {
        global $wp_rewrite;
        $category_rewrite=array();
        /* WPML is present: temporary disable terms_clauses filter to get all categories for rewrite */
        if ( class_exists( 'Sitepress' ) ) {
            global $sitepress;
            remove_filter( 'terms_clauses', array( $sitepress, 'terms_clauses' ) );
            $categories = get_categories( array( 'hide_empty' => false ) );
            add_filter( 'terms_clauses', array( $sitepress, 'terms_clauses' ) );
        } else {
            $categories = get_categories( array( 'hide_empty' => false ) );
        }
        foreach( $categories as $category ) {
            $category_nicename = $category->slug;
            if ( $category->parent == $category->cat_ID ) {
                $category->parent = 0;
            } elseif ( $category->parent != 0 ) {
                $category_nicename = get_category_parents( $category->parent, false, '/', true ) . $category_nicename;
            }
            $category_rewrite['('.$category_nicename.')/(?:feed/)?(feed|rdf|rss|rss2|atom)/?$'] = 'index.php?category_name=$matches[1]&feed=$matches[2]';
            $category_rewrite["({$category_nicename})/{$wp_rewrite->pagination_base}/?([0-9]{1,})/?$"] = 'index.php?category_name=$matches[1]&paged=$matches[2]';
            $category_rewrite['('.$category_nicename.')/?$'] = 'index.php?category_name=$matches[1]';
        }
        // Redirect support from Old Category Base
        $old_category_base = get_option( 'category_base' ) ? get_option( 'category_base' ) : 'category';
        $old_category_base = trim( $old_category_base, '/' );
        $category_rewrite[$old_category_base.'/(.*)$'] = 'index.php?category_redirect=$matches[1]';
        return $category_rewrite;
    }
    function no_category_base_query_vars($public_query_vars) {
        $public_query_vars[] = 'category_redirect';
        return $public_query_vars;
    }
    function no_category_base_request($query_vars) {
        if( isset( $query_vars['category_redirect'] ) ) {
            $catlink = trailingslashit( get_option( 'home' ) ) . user_trailingslashit( $query_vars['category_redirect'], 'category' );
            status_header( 301 );
            header( "Location: $catlink" );
            exit();
        }
        return $query_vars;
    }

    //excerpt
    function excerpt_more($more) {
        return $this->excerpt_more;
    }
    function excerpt_length($length) {
        return $this->excerpt_length;
    }

    //admin_enqueue_scripts
    function admin_enqueue_scripts(){
        //body_class
        if(!empty($this->admin_enqueue_scripts['css']) && is_array($this->admin_enqueue_scripts['css'])){
            foreach($this->admin_enqueue_scripts['css'] as $k => $v){
                wp_enqueue_style('themestyle-'.md5($v),$v);
            }
        }
        //javascript
        if(!empty($this->admin_enqueue_scripts['js']) && is_array($this->admin_enqueue_scripts['js'])){
            foreach($this->admin_enqueue_scripts['js'] as $k => $v){
                wp_enqueue_script('themestyle-'.md5($v),$v,array('jquery'),'',true);
            }
        }
    }

    //javascript
    function wp_enqueue_scripts(){
        if(!is_admin()){
            //jQuery
            wp_deregister_script('jquery');
            $user_agent = $_SERVER['HTTP_USER_AGENT'];
            if(strstr($user_agent, 'Trident') || strstr($user_agent, 'MSIE')) {
                wp_enqueue_script('jquery','https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js', array(),NULL);
            }else{
                wp_enqueue_script('jquery','https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js', array(),NULL);
            }
            if($this->wp_enqueue_scripts){foreach($this->wp_enqueue_scripts as $key => $url){
                if(empty($url)){
                    wp_deregister_script($key);
                    wp_enqueue_script($key);
                }else{
                    $ver = NULL;
                    if($url && strpos($url,get_stylesheet_directory_uri()) !== false){
                        $path = str_replace(get_stylesheet_directory_uri(),get_stylesheet_directory(),$url);
                        //test($path);
                        $ver = filemtime($path);
                    }
                    wp_enqueue_script($key,$url,array('jquery'),$ver,true);
                }
            }}
        }
    }

    //style
    function wp_enqueue_styles(){
        // 管理画面以外で読み込ませる
        if(!is_admin()){
            if($this->wp_enqueue_styles){foreach($this->wp_enqueue_styles as $key => $url){
                $ver = NULL;
                if($url && strpos($url,get_stylesheet_directory_uri()) !== false){
                    $path = str_replace(get_stylesheet_directory_uri(),get_stylesheet_directory(),$url);
                    //test($path);
                    $ver = filemtime($path);
                }
                //test($ver);
                wp_enqueue_style($key,$url,array(),$ver);
            }}
        }
    }

    //body_class
    function body_class($classes,$class){
        global $is_IE;
        if($is_IE){
            $classes[] = 'ie';
        }
        switch ($GLOBALS['UserAgent']->set()){
            case 'tablet':
                $classes[] = 'ua-tb';
            case 'mobile':
                $classes[] = 'ua-sp';
                break;
            default:
                $classes[] = 'ua-pc';
        }
        if(is_front_page()){
            $classes[] = 'front-page';
        }else{
            $classes[] = 'bottompage';
        }
        if(is_page()){
            $classes[] = 'page-slug-'.get_page_uri(get_the_ID());
        }
        if(get_option('front_screen',false)){
            $classes[] = 'loading';
        }
        return $classes;
    }

    //post_class
    function post_class($classes,$class,$post_id){
        if(!empty($post_id)){
            $slug = get_page_uri($post_id);
            $classes[] = "post-slug-{$slug}";
        }
        return $classes;
    }

    function after_setup_theme(){
        // session開始
        session_start();

        // titleタグの出力
        add_theme_support('title-tag');

        // HTML5のサポート
        add_theme_support('html5',array('search-form','comment-form','comment-list','gallery','caption'));

        // 外観 > カスタマイズ　削除
        remove_theme_support('custom-header');
        remove_theme_support('custom-background');

        // アイキャッチのサポート
        //add_theme_support('post-thumbnails');

        // Feedのリンク生成をサポート
        // add_theme_support('automatic-feed-links');

        // カスタムエディターのCSS（パスは適宜変更してください）
        // add_editor_style(array('editor-style.css','css/font-awesome.css'));

        // WordPressのバージョン 削除
        remove_action('wp_head', 'wp_generator');

        // バージョン更新を非表示にする
        #add_filter('pre_site_transient_update_core','__return_zero');
        #remove_action('wp_version_check','wp_version_check');
        #remove_action('admin_init','_maybe_update_core');

        // APIによるバージョンチェックの通信をさせない
        remove_action('wp_version_check', 'wp_version_check');
        remove_action('admin_init', '_maybe_update_core');

        // レティナパラメーター 削除
        add_filter('wp_calculate_image_srcset_meta', '__return_null');

        // feed 削除
        remove_action('wp_head','feed_links_extra',3);

        // 絵文字関連 削除
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('wp_print_styles', 'print_emoji_styles');

        // ブログ投稿ツールのためのタグ 削除
        remove_action('wp_head', 'rsd_link');
        remove_action('wp_head', 'wlwmanifest_link');

        // rel=”prev”とrel=”next” 削除
        remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');

        // rel=”canonical” 削除
        remove_action('wp_head', 'rel_canonical');

        // 短縮URL 削除
        remove_action('wp_head', 'wp_shortlink_wp_head');

        // Embedタグ 削除
        remove_action('wp_head','rest_output_link_wp_head');
        remove_action('wp_head','wp_oembed_add_discovery_links');
        remove_action('wp_head','wp_oembed_add_host_js');
    }

    //全ての「?ver=~」を削除する
    function vc_remove_wp_ver_css_js( $src ) {
        if ( strpos( $src, 'ver=' ) )
            $src = remove_query_arg( 'ver', $src );
        return $src;
    }
}

/* Controller */
class setupThemeController {
    function __construct($init = array()){
        foreach($init as $k => $v)$this->$k = $v;
        //404 トップへリダイレクト
        if(!empty($this->from_404_to_TOP)){
            add_action('template_redirect',array($this,'template_redirect'));
        }else{
            add_filter('redirect_canonical',array($this,'redirect_canonical'));
        }
        //model
        global $setupThemeModel;
        $this->model = $setupThemeModel;
        //view
        $this->view = new setupThemeView($this->view_option);
    }

    //404 トップへリダイレクト
    function template_redirect() {
        if( is_404() ){
            wp_safe_redirect( home_url( '/' ) );
            exit();
        }
    }
    function redirect_canonical($redirect_url) {
        //return false;
        if(is_404()){
            return false;
        }
        return $redirect_url;
    }
}
