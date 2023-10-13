<?php
if(!class_exists('Breadcrumb')){
    class Breadcrumb {
        function __construct($init = array()){
            foreach($init as $k => $v)$this->$k = $v;
        }
        //レスポンシブなページネーションを作成する
        static function the_breadcrumb(){
            echo Breadcrumb::get_the_breadcrumb();
        }
        static function get_the_breadcrumb(){
            global $post,$wp_query;
            $str ='';
            if(!is_front_page() && !is_admin()){
                $str.= '<div id="breadcrumb-area" class="view-area"><div id="breadcrumb" class="cf colbox"><div class="col" itemscope itemtype="http://data-vocabulary.org/Breadcrumb">';
                $str.= '<a href="'. home_url('/') .'" itemprop="url"><span itemprop="title">TOP</span></a></div>';
                if(get_query_var('pickup-word')){
                    $str .= '<div class="col"><span><span>TAG LIST</span></span></div>';
                }elseif(is_home()){
                    $obj  = get_post_type_object(get_post_type($post->ID));
                    $str .= '<div class="col"><span><span>'. $obj->labels->singular_name . '</span></span></div>';
                } elseif(isset($wp_query->query['s']) || (isset($wp_query->query['name']) && $wp_query->query['name'] == 'search') || (isset($wp_query->query['pagename']) && $wp_query->query['pagename'] == 'search')){
                    if(!get_queried_object()){
                        $page = get_page_by_path('search');
                        //test($wp_query);
                        if($page){
                            global $wp_query;
                            $wp_query->queried_object    = $page;
                            $wp_query->queried_object_id = $page->ID;
                        }
                    }
                    //$str .= '<div class="col"><span><span>“'.get_query_var('s').'”の検索結果 ( '.number_format($wp_query->found_posts).' )</span></span></div>';
                    $str .= '<div class="col"><span><span>'. get_the_title(get_queried_object_id()) . '</span></span></div>';
                } elseif(is_404()){
                    $str .= '<div class="col"><span><span>404 not found</span></span></div>';
                } elseif(is_single()){
                    $obj  = get_post_type_object(get_post_type($post->ID));
                    $str .= '<div class="col" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'. get_post_type_archive_link(get_post_type($post->ID)). '" itemprop="url"><span itemprop="title">'. $obj->labels->singular_name . '</span></a></div>';
                    if($post->post_parent){
                        $str .= Breadcrumb::post_parent_item(get_post($post->post_parent));
                    }
                    if(get_post_type($post->ID) == 'post'){
                        $terms = get_the_terms($post->ID,'category');
                        if(is_array($terms) && count($terms)){
                            $str .= '<div class="col" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'. get_term_link($terms[0]->term_id). '" itemprop="url"><span itemprop="title">'. $terms[0]->name . '</span></a></div>';
                        }
                    }elseif(get_post_type($post->ID) == 'news'){
                        $terms = get_the_terms($post->ID,'news_category');
                        if(is_array($terms) && count($terms)){
                            $str .= '<div class="col" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'. get_term_link($terms[0]->term_id). '" itemprop="url"><span itemprop="title">'. $terms[0]->name . '</span></a></div>';
                        }
                    }
                    $str .= '<div class="col"><span><span>'. get_the_title($post->ID) . '</span></span></div>';
                } elseif(is_page()){
                    if($post->post_parent){
                        $str .= Breadcrumb::post_parent_item(get_post($post->post_parent));
                    }
                    $str .= '<div class="col"><span><span>'. get_the_title($post->ID) .'</span></span></div>';
                } elseif(is_tax() || is_tag() || is_category()){
                    if(get_post_type() == 'post'){
                        $obj  = get_post_type_object('post');
                        $str .= '<div class="col" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'. get_post_type_archive_link(get_post_type($post->ID)). '" itemprop="url"><span itemprop="title">'. $obj->labels->singular_name . '</span></a></div>';
                    }
                    $term     = get_queried_object();
                    $taxonomy = $term->taxonomy;
                    $term_id  = $term->term_id;
                    if($term->parent){
                        $str .= term_parent_item(get_term($term->parent));
                    }
                    $str.='<div class="col"><span><span>'.$term->name.'</span></span></div>';
                } elseif(is_archive()){
                    $obj = get_post_type_object(get_query_var('post_type'));
                    //$str.='<div class="col" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'. get_post_type_archive_link(get_post_type($post->ID)). '" itemprop="url"><span itemprop="title">'. $obj->labels->singular_name . '</span></a></div>';
                    $str.='<div class="col"><span><span>'. $obj->labels->singular_name . '</span></span></div>';
                } else{
                    $str.='<div class="col"><span><span>'. get_the_title($post->ID) .'</span></span></div>';
                }
                $str.='</div></div>';
            }
            return $str;
        }
        static function post_parent_item($post){
            $items = '';
            if(!$post->post_parent){
                $obj    = get_post_type_object(get_post_type($post->ID));
                if($obj->has_archive){
                    $items .='<div class="col" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'. get_post_type_archive_link(get_post_type($post->ID)). '" itemprop="url"><span itemprop="title">'. $obj->labels->singular_name . '</span></a></div>';
                }
                $items .= '<div class="col" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'. get_the_permalink($post->ID).'" itemprop="url"><span itemprop="title">'. get_the_title($post->ID) .'</span></a></div>';
            }else{
                $items .= Breadcrumb::post_parent_item(get_post($post->post_parent));
            }
            return $items;
        }
        static function term_parent_item($term){
            $items = '';
            if(!$term->parent){
                $items .='<div class="col" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'. get_term_link($term->term_id). '" itemprop="url"><span itemprop="title">'.$term->name.'</span></a></div>';
            }else{
                $items .= Breadcrumb::post_parent_item(get_term($term->parent));
            }
            return $items;
        }
    }
}