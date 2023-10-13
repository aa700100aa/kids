<?php
if(!class_exists('NavMenus')){
    class NavMenus {
        function __construct($init = array()) {
            foreach($init as $k => $v)$this->$k = $v;
            //テーマセットアップ
            add_action('init',array($this,'after_setup_theme'),PHP_INT_MAX);
            //wp_nav_menu hrer="#"　←の場合にaタグではなくspanに変換
            add_filter('wp_nav_menu_items', array($this,'wp_nav_menu_items'));
            add_filter('wp_nav_menu_args',  array($this,'wp_nav_menu_args'));
            add_filter('nav_menu_css_class',array($this,'nav_menu_css_class'),10,2);
            //add_filter('wp_nav_menu_objects',array($this,'wp_nav_menu_items'),10,2);
            //カスタムメニュー
            add_filter('wp_nav_menu_objects',array($this,'wp_nav_menu_objects'),10,2);
        }
        //テーマセットアップ
        function after_setup_theme(){
            //いらないメニューを削除
            unregister_nav_menu('primary-menu');
            unregister_nav_menu('secondary-menu');
            unregister_nav_menu('sidepage-menu');
            unregister_nav_menu('smartphone-menu');
            unregister_nav_menu('smartphone-footermenu');

            // カスタムメニューの使用（add_theme_supportは使わなくても問題ありません）
            register_nav_menus(array(
                'gnav'    => 'グローバルナビ',
                'snsnav'  => 'SNS',
                'fnav'    => 'フッターナビ',
            ));
        }
        //wp_nav_menu hrer="#"　←の場合にaタグではなくspanに変換
        function wp_nav_menu_items($items){
            $items     = str_replace('http://[home_url]/', home_url('/'), $items);
            $match_num = preg_match_all('/<a[^>]href\s?=\s?[\"\']#[\"\'][^>]*>(.*?)<\/a>/i',$items,$matches);
            if($match_num){
                foreach($matches[0] as $key => $value){
                    $items = mb_ereg_replace($value,"<span>{$matches[1][$key]}</span>",$items);
                }
            }
            $match_num = preg_match_all('/<a[^>]href\s?=\s?[\"\'](https?:\/\/\[home_url\]\/?.*?)[\"\'][^>]*>.*?<\/a>/i',$items,$matches);
            return $items;
        }
        function wp_nav_menu_args($args){
            $args['link_before'] = '<span>';
            $args['link_after'] = '</span>';
            return $args;
        }
        function nav_menu_css_class($classes,$item){
            global $wp_query;
            $post_type_query = $wp_query->query_vars['post_type']?$wp_query->query_vars['post_type']:get_post_type();
            $page_for_posts  = ($post_type_query == 'post')?get_option('page_for_posts'):get_option("{$post_type_query}-page_for_posts");
            if($item->type == 'post_type' && ($item->object == $post_type_query || $item->object_id == $page_for_posts)){
                $classes[] = 'current-menu-parent';
                if(is_archive()){
                    $classes[] = 'current-menu-archive';
                }
                if(is_single()){
                    $classes[] = 'current-menu-single';
                }
            }
            if(is_array($classes) && count($classes)){
                $classes = array_unique( $classes );
            }
            return $classes;
        }
        //カスタムメニュー
        function wp_nav_menu_objects($sorted_menu_items, $args){
            global $wp_query;
            $post_type_query = $wp_query->query_vars['post_type']?$wp_query->query_vars['post_type']:get_post_type();
            $page_for_posts  = ($post_type_query == 'post')?get_option('page_for_posts'):get_option("{$post_type_query}-page_for_posts");
            foreach($sorted_menu_items as $k => $item){
                if($item->type == 'post_type' && ($item->object == $post_type_query || $item->object_id == $page_for_posts)){
                    if(empty($sorted_menu_items[$k]->classes) || !is_array($sorted_menu_items[$k]->classes)){
                        $sorted_menu_items[$k]->classes = array();
                    }
                    if(is_archive() || is_single()){
                        $sorted_menu_items[$k]->classes[] = 'current-menu-parent';
                        if(is_archive()){
                            $sorted_menu_items[$k]->classes[] = 'current-menu-archive';
                        }
                        if(is_single()){
                            $sorted_menu_items[$k]->classes[] = 'current-menu-single';
                        }
                        if(is_array($sorted_menu_items[$k]->classes) && count($sorted_menu_items[$k]->classes)){
                            $sorted_menu_items[$k]->classes = array_unique( $sorted_menu_items[$k]->classes );
                        }
                        $sorted_menu_items = $this->item_parent_add_class($item,$sorted_menu_items);
                    }
                    //test($item);
                }
            }
            return $sorted_menu_items;
        }
        function item_parent_add_class($item,$items){
            if(!empty($item->menu_item_parent)){
                $index = false;
                foreach($items as $k => $_item){
                    if($_item->ID == $item->menu_item_parent){
                        $index = $k;
                        break;
                    }
                }
                if($index !== false){
                    $items[$index]->classes[] = 'current-menu-ancestor';
                    if(is_array($items[$index]->classes) && count($items[$index]->classes)){
                        $items[$index]->classes = array_unique($items[$index]->classes);
                    }
                    if(!empty($items[$index]->menu_item_parent)){
                        $items = $this->item_parent_add_class($items[$index],$items);
                    }
                }
                //test($item);
            }
            return $items;
        }
    }
}
$NavMenus = new NavMenus();
