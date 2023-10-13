<?php
// 全てのエラーを表示する
//error_reporting(E_ALL);
// 非表示(notice/warning)
//error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_STRICT);
// 非表示(notice)
error_reporting(E_ALL & ~E_NOTICE);

// テスト
if(!function_exists('test')){
    function test($text){
        if(is_array($text) || is_object($text)){
            echo'<pre>';print_r($text);echo'</pre><br />';
        }elseif($text === true){
            echo'<pre>TRUE</pre><br />';
        }elseif($text === false){
            echo'<pre>FALSE</pre><br />';
        }elseif($text === null){
            echo'<pre>NULL</pre><br />';
        }else{
            echo '<pre>'.$text.'</pre><br />';
        }
    }
}
if( ! function_exists('boolval')){
    /**
     * Get the boolean value of a variable
     *
     * @param mixed The scalar value being converted to a boolean.
     * @return boolean The boolean value of var.
     */
    function boolval($var){
        return !! $var;
    }
}
// アーカイブページで現在のカテゴリー・タグ・タームを取得する
if(!function_exists('get_current_term')){
    function get_current_term(){
        $id;
        $tax_slug;
        if(is_category()){
            $tax_slug = "category";
            $id = get_query_var('cat');	
        }else if(is_tag()){
            $tax_slug = "post_tag";
            $id = get_query_var('tag_id');	
        }else if(is_tax()){
            $tax_slug = get_query_var('taxonomy');	
            $term_slug = get_query_var('term');	
            $term = get_term_by("slug",$term_slug,$tax_slug);
            $id = $term->term_id;
        }
        if(!empty($id)){
            return get_term($id,$tax_slug);
        }
        return '';
    }
}