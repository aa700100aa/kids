<?php
if(!class_exists('Pagination')){
    class Pagination {
        function __construct($init = array()){
            foreach($init as $k => $v)$this->$k = $v;
            //スクリプト・スタイル追加
            wp_enqueue_style('pagination',get_stylesheet_directory_uri().'/functions/class/Pagination/assets/css/pagination.css');
        }
        //レスポンシブなページネーションを作成する
        static function the_pagination($pages = '', $range = 3,$paged = '',$posts_per_page = '',$pagination_base = '',$url_query = array()){
            //スクリプト・スタイル追加
            wp_enqueue_style('pagination',get_stylesheet_directory_uri().'/functions/class/Pagination/assets/css/pagination.css');
            echo self::get_the_pagination($pages,$range,$paged,$posts_per_page,$pagination_base,$url_query);
        }
        static function get_the_pagination($pages = '', $range = 3,$paged = '',$posts_per_page = '',$pagination_base = '',$url_query = array()){
            global $wp_query;
            $html = '';
            $showitems = ($range * 2)+1;

            if($paged == ''){
                if(is_home()){
                    global $paged;
                    if(empty($paged)) $paged = 1;
                }else{
                    global $paged;
                    if(empty($paged)) $paged = 1;
                }
            }

            //ページ情報の取得
            if($pages == '') {
                $pages = $wp_query->max_num_pages;
                if(!$pages){
                    $pages = 1;
                }
            }

            //
            if(empty($posts_per_page)){
                $posts_per_page = get_query_var('posts_per_page',get_option('posts_per_page',20));
            }

            if(1 != $pages) {

                $html .= '<div class="wrap inner"><ul class="pagenation" role="menubar" aria-label="Pagination">';
                //先頭へ
                /*if($paged > 1){
                    $href  = add_query_arg($url_query,self::get_pagenum_link(1,false,$pagination_base));
                    $html .= '<li class="first"><a href="'.$href.'"><span>First</span></a></li>';
                }else{
                    $html .= '<li class="first"><span><span>First</span></span></li>';
                }*/
                //1つ戻る
                if($paged > 1){
                    $href  = add_query_arg($url_query,self::get_pagenum_link($paged - 1,false,$pagination_base));
                    $html .= '<li class="back"><a href="'.$href.'"><span>&nbsp;</span></a></li>';
                }else{
                    //$html .= '<li class="previous"><span><span><span class="dashicons dashicons-arrow-left-alt2"></span>前の'.$posts_per_page.'件</span></span></li>';
                }
                //番号つきページ送りボタン
                for ($i=1; $i <= $pages; $i++){
                    if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems )){
                        $href  = add_query_arg($url_query,self::get_pagenum_link($i,false,$pagination_base));
                        $html .= ($paged == $i)? '<li class="current"><a href="" class="current">'.$i.'</a></li>':'<li><a href="'.$href.'" class="inactive" ><span>'.$i.'</span></a></li>';
                    }
                }
                //1つ進む
                if($pages > $paged){
                    $href  = add_query_arg($url_query,self::get_pagenum_link($paged + 1,false,$pagination_base));
                    $html .= '<li class="next"><a href="'.$href.'"><span>&nbsp;</span></a></li>';
                }else{
                    //$html .= '<li class="next"><span><span>次の'.$posts_per_page.'件<span class="dashicons dashicons-arrow-right-alt2"></span></span></span></li>';
                }
                //最後尾へ
                /*if($pages > $paged){
                    $href  = add_query_arg($url_query,self::get_pagenum_link($pages,false,$pagination_base));
                    $html .= '<li class="last"><a href="'.$href.'"><span>Last</span></a></li>';
                }else{
                    $html .= '<li class="last"><span><span>Last</span></span></li>';
                }*/
                return "{$html}</ul><p class=\"ac text\">".(($paged - 1) * $posts_per_page + 1)."件 - ".(($paged * $posts_per_page < $wp_query->found_posts)?$paged * $posts_per_page:$wp_query->found_posts)."件 表示</p></div>";
            }
        }
        static function get_pagenum_link($pagenum = 1, $escape = false ,$pagination_base = '') {
            global $wp_rewrite;
            if(empty($pagination_base)){
                $pagination_base = $wp_rewrite->pagination_base;
            }
            //test($pagination_base);
            //test($wp_rewrite);
            $pagenum = (int) $pagenum;
            $request = remove_query_arg( 'paged' );
            $home_root = parse_url(home_url());
            $home_root = ( isset($home_root['path']) ) ? $home_root['path'] : '';
            $home_root = preg_quote( $home_root, '|' );
            $request = preg_replace('|^'. $home_root . '|i', '', $request);
            $request = preg_replace('|^/+|', '', $request);
            if ( !$wp_rewrite->using_permalinks() || is_admin() ) {
                $base = trailingslashit( get_bloginfo( 'url' ) );
                if ( $pagenum > 1 ) {
                    $result = add_query_arg( 'paged', $pagenum, $base . $request );
                } else {
                    $result = $base . $request;
                }
            } else {
                $qs_regex = '|\?.*?$|';
                preg_match( $qs_regex, $request, $qs_match );
                if ( !empty( $qs_match[0] ) ) {
                    $query_string = $qs_match[0];
                    $request = preg_replace( $qs_regex, '', $request );
                } else {
                    $query_string = '';
                }
                $request = preg_replace( "|$pagination_base/\d+/?$|", '', $request);
                $request = preg_replace( '|^' . preg_quote( $wp_rewrite->index, '|' ) . '|i', '', $request);
                $request = ltrim($request, '/');
                $base = trailingslashit( get_bloginfo( 'url' ) );
                if ( $wp_rewrite->using_index_permalinks() && ( $pagenum > 1 || '' != $request ) )
                    $base .= $wp_rewrite->index . '/';
                if ( $pagenum > 1 ) {
                    $request = ( ( !empty( $request ) ) ? trailingslashit( $request ) : $request ) . user_trailingslashit( $pagination_base . "/" . $pagenum, 'paged' );
                }
                $result = $base . $request . $query_string;
            }
            /**
             * Filters the page number link for the current request.
             *
             * @since 2.5.0
             *
             * @param string $result The page number link.
             */
            $result = apply_filters( 'get_pagenum_link', $result );
            if ( $escape )
                return esc_url( $result );
            else
                return esc_url_raw( $result );
        }
    }
}