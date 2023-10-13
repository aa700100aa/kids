<?php
//デバッグファイル
require_once(dirname(__FILE__).'/debag.php');

//関数群
if(!function_exists('get_taxonomies_by_other_term_id')){
    function get_taxonomies_by_other_term_id($get_taxonomy,$other_term,$search=''){
        global $wpdb;
        if(is_array($other_term) && count($other_term) > 1){
            $other_term = array_merge($other_term);
            $taxonomy   = get_taxonomy($other_term[0]->taxonomy);
            $post_types = "('".implode("','",$taxonomy->object_type)."')";
            $term_ids   = array();
            foreach($other_term as $obj){
                $term_ids[] = $obj->term_id;
            }
        }else{
            if(is_array($other_term)){
                $other_term = array_merge($other_term);
                $other_term = $other_term[0];
            }
            $taxonomy   = get_taxonomy($other_term->taxonomy);
            $post_types = "('".implode("','",$taxonomy->object_type)."')";
            $term_ids   = array($other_term->term_id);
        }
        $from = get_taxonomies_by_other_term_id_from_create($term_ids);
        if(!empty($search)){
            $search = $wpdb->prepare(" AND ((({$wpdb->posts}.post_title LIKE '%%%s%%') OR ({$wpdb->posts}.post_excerpt LIKE '%%%s%%') OR ({$wpdb->posts}.post_content LIKE '%%%s%%'))) ",$search,$search,$search);
        }else{
            $search = '';
        }
        $query  = "
            SELECT DISTINCT
                SQL_CALC_FOUND_ROWS 
                inner_join04.term_taxonomy_id AS term_taxonomy_id ,
                inner_join04.term_id          AS term_id ,
                inner_join04.taxonomy         AS taxonomy ,
                inner_join04.parent           AS parent ,
                inner_join04.name             AS name ,
                inner_join04.slug             AS slug
            FROM {$wpdb->posts}
            INNER JOIN (
                SELECT DISTINCT
                    inner_join03.object_id        AS object_id ,
                    inner_join03.term_taxonomy_id AS term_taxonomy_id ,
                    inner_join03.term_id          AS term_id ,
                    inner_join03.taxonomy         AS taxonomy ,
                    inner_join03.parent           AS parent ,
                    inner_join03.name             AS name ,
                    inner_join03.slug             AS slug
                {$from}
                INNER JOIN (
                    SELECT
                        inner_join.object_id        AS object_id ,
                        inner_join.term_taxonomy_id AS term_taxonomy_id ,
                        inner_join02.term_id        AS term_id ,
                        inner_join02.taxonomy       AS taxonomy ,
                        inner_join02.parent         AS parent ,
                        inner_join02.name           AS name ,
                        inner_join02.slug           AS slug
                    FROM {$wpdb->term_relationships} AS inner_join
                    INNER JOIN (
                        SELECT
                            {$wpdb->term_taxonomy}.term_id          AS term_id ,
                            {$wpdb->term_taxonomy}.term_taxonomy_id AS term_taxonomy_id ,
                            {$wpdb->term_taxonomy}.taxonomy         AS taxonomy ,
                            {$wpdb->term_taxonomy}.parent           AS parent ,
                            inner_join01.name                            AS name ,
                            inner_join01.slug                            AS slug
                        FROM {$wpdb->term_taxonomy}
                        INNER JOIN (
                            SELECT *
                            FROM {$wpdb->terms}
                        ) inner_join01 ON (inner_join01.term_id = {$wpdb->term_taxonomy}.term_id)
                        WHERE {$wpdb->term_taxonomy}.taxonomy = '{$get_taxonomy}'
                    ) inner_join02 ON (inner_join02.term_taxonomy_id = inner_join.term_taxonomy_id)
                ) inner_join03 ON (inner_join03.object_id = target.object_id)
            ) inner_join04 ON (inner_join04.object_id = {$wpdb->posts}.ID)
            WHERE 1=1
                AND {$wpdb->posts}.post_type     IN {$post_types}
                AND (({$wpdb->posts}.post_status = 'publish'))
                {$search}
            GROUP BY inner_join04.term_id
        ";
        //test($query);
        return array_merge($wpdb->get_results($query));
    }
}
if(!function_exists('get_taxonomies_by_other_term_id_from_create')){
    function get_taxonomies_by_other_term_id_from_create($term_ids,$index=0){
        global $wpdb;
        if(is_array($term_ids) && $index == 0){
            $term   = array_shift($term_ids);
            $return = "
                FROM (
                    SELECT target_from{$index}.object_id
                    FROM {$wpdb->term_relationships} AS target_from{$index}
                    ".(count($term_ids)?get_taxonomies_by_other_term_id_from_create($term_ids,($index + 1)):'')."
                    WHERE target_from{$index}.term_taxonomy_id = {$term}
                ) target
            ";
        }elseif(is_array($term_ids) && $index > 0){
            $term   = array_shift($term_ids);
            $return = "
                INNER JOIN (
                    SELECT target_from{$index}.object_id AS object_id , target_from{$index}.term_taxonomy_id AS term_taxonomy_id
                    FROM {$wpdb->term_relationships} AS target_from{$index}
                    ".(count($term_ids)?get_taxonomies_by_other_term_id_from_create($term_ids,($index + 1)):'')."
                    WHERE target_from{$index}.term_taxonomy_id = {$term}
                ) target_inner_join{$index} ON (target_inner_join{$index}.object_id = target_from".($index - 1).".object_id)
            ";
        }
        return $return;
    }
}

/* svg */
//add SVG to allowed file uploads
function add_file_types_to_uploads($file_types){
    $new_filetypes = array();
    $new_filetypes['svg'] = 'image/svg+xml';
    $file_types = array_merge($file_types, $new_filetypes );
    return $file_types;
}
add_action('upload_mimes', 'add_file_types_to_uploads');

//call our function when initiated from JavaScript
add_action('wp_ajax_svg_get_attachment_url', 'get_attachment_url_media_library');

//called via AJAX. returns the full URL of a media attachment (SVG) 
function get_attachment_url_media_library(){
    $url = '';
    $attachmentID = isset($_REQUEST['attachmentID']) ? $_REQUEST['attachmentID'] : '';
    if($attachmentID){
        $url = wp_get_attachment_url($attachmentID);
    }
    echo $url;
    die();
}
function wp_get_attachment_svg($attachment_id, $size = 'thumbnail', $icon = false, $attr = ''){
    $url = wp_get_attachment_url($attachment_id);
    $ext = substr($url, strrpos($url, '.') + 1);
    if($ext == 'svg'){
        return get_svg_code($url);
    }else{
        return wp_get_attachment_image($attachment_id,$size, $icon,$attr);
    }
}
function get_svg_code($filepath){
    $upload_dir = wp_upload_dir();
    $filepath   = str_replace($upload_dir['baseurl'],$upload_dir['basedir'],$filepath);
    $doc = new DOMDocument();
    $doc->load($filepath);
    //$doc->loadXML(file_get_contents($filepath));
    $svg = $doc->getElementsByTagName('svg');
    return $svg->item(0)->C14N();
}

//ファイル自動インクルード
if(!class_exists('myFunctionDirSearch')){
    class myFunctionDirSearch {
        private $dir = '';
        function __construct($dirname=null){
            //現在のディレクトリ取得
            $this->dir = $dirname?$dirname:dirname(__FILE__);
            //ディレクトリー内にあるプラグインを実行
            $this->_ini();
        }
        function _ini(){
            $function_files = array();
            $wp_plugins = array();
            if($handle01 = opendir($this->dir)){
                while(false !== ($dir = readdir($handle01))){
                    if($dir != "." && $dir != ".." && is_dir("{$this->dir}/{$dir}")){
                        $handle02 = opendir("{$this->dir}/{$dir}");
                        while(false !== ($file = readdir($handle02))){
                            if($file == "." || $file == "..")continue;
                            if(is_file("{$this->dir}/{$dir}/{$file}") && $file == "{$dir}.php"){
                                require_once("{$this->dir}/{$dir}/{$file}");
                            }
                        }
                        closedir($handle02);
                    }
                }
                closedir($handle01);
            }
        }
    }
}
$myFunctionDirSearch = new myFunctionDirSearch(dirname(__FILE__));

//テーマカスタマイザー
add_filter('customize_loaded_components', '__return_empty_array');
add_action('customize_register',          'customizer_remove_section',15);
function customizer_remove_section( $wp_customize ) {
    // 色の項目を削除
    $wp_customize->remove_section("colors");
    // サイトタイトルとキャッチフレーズ
    $wp_customize->remove_section("title_tagline");
    // ヘッダー画像
    $wp_customize->remove_section("header_image");
    // 背景画像
    $wp_customize->remove_section("background_image");
    // ナビゲーション（訳注：無いかも？）
    $wp_customize->remove_section("nav");
    // 固定フロントページ
    $wp_customize->remove_section("static_front_page");
    
    $wp_customize->remove_control( 'blog_title' );
    $wp_customize->remove_section( 'genesis_header' );
    $wp_customize->remove_section( 'genesis_comments' );
    $wp_customize->remove_section( 'genesis_layout' );
    $wp_customize->remove_section( 'genesis_adsense' );
    $wp_customize->remove_section( 'genesis_breadcrumbs' );
    $wp_customize->remove_section( 'genesis_archives' );
    $wp_customize->remove_section( 'genesis_scripts' );
    $wp_customize->remove_panel( 'genesis-seo' );
}