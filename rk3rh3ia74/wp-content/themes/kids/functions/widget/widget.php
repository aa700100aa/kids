<?php

$myFunctionDirSearch = new myFunctionDirSearch(dirname(__FILE__));

class thmeWidget {
    function __construct() {
        add_action('widgets_init',array($this,'init'),PHP_INT_MAX);
    }
    function init(){
        unregister_widget('WP_Widget_Pages');                  // 固定ページ
        unregister_widget('WP_Widget_Calendar');               // カレンダー
        unregister_widget('WP_Widget_Archives');               // アーカイブ
        unregister_widget('WP_Widget_Meta');                   // メタ情報
        unregister_widget('WP_Widget_Search');                 // 検索
        unregister_widget('WP_Widget_Categories');             // カテゴリー
        unregister_widget('WP_Widget_Recent_Posts');           // 最近の投稿
        unregister_widget('WP_Widget_Recent_Comments');        // 最近のコメント
        unregister_widget('WP_Widget_RSS');                    // RSS
        unregister_widget('WP_Widget_Tag_Cloud');              // タグクラウド
        unregister_widget('Twenty_Fourteen_Ephemera_Widget');  // Twenty Fourteen 短冊
        register_sidebar(array(
            'name' => 'サイドバー',
            'id' => 'sidebar01',
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h2 class="title">',
            'after_title' => '</h2>',
        ));
    }
}
//$GLOBALS['thmeWidget'] = new thmeWidget();

if(!function_exists('is_sidebar')){
    function is_sidebar($name = ''){
        ob_start();
        dynamic_sidebar($name);
        $buffer = ob_get_contents();
        ob_end_clean();
        return !empty($buffer)?true:false;
    }
}