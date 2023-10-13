<?php
class ThemeShortCode {
    function __construct($init = array()){
        foreach($init as $k => $v)$this->$k = $v;
        // ショートコードが効かないプロパティーへの対処
        add_filter('wp_kses_allowed_html',array($this,'wp_kses_allowed_html'),10,2);
        add_filter('safe_style_css',array($this,'safe_style_css'));
        // テーマディレクトリを取得 ショートコード
        add_shortcode('dir',array($this,'diruri'));
        add_shortcode('assets',array($this,'assets'));
        // 固定ページのスラッグからページを取得 ショートコード
        add_shortcode('page_slug_url',array($this,'page_slug'));
        // home_url ショートコード
        add_shortcode('home_url',array($this,'home_url'));
        // staffリスト
        add_shortcode('staff_slider',array($this,'staff_slider'));
        // homeの画像取得
        add_shortcode('main_image',array($this,'main_image'));
        // posts
        add_shortcode('posts',array($this,'posts'));
        add_shortcode('the_time',array($this,'the_time'));
        add_shortcode('the_term_link',array($this,'the_term_link'));
        add_shortcode('the_permalink',array($this,'the_permalink'));
        add_shortcode('the_content',array($this,'the_content'));
        add_shortcode('the_title',array($this,'the_title'));
        add_shortcode('the_post_thumbnail',array($this,'the_post_thumbnail'));
        add_shortcode('the_excerpt',array($this,'the_excerpt'));
        add_shortcode('the_ID',array($this,'the_ID'));
        add_shortcode('the_terms',array($this,'the_terms'));
        add_shortcode('the_terms_full',array($this,'the_terms_full'));
        add_shortcode('the_post_meta',array($this,'the_post_meta'));
        add_shortcode('bloginfo',array($this,'bloginfo'));
        add_shortcode('contactBanner',array($this,'contactBanner'));
        add_shortcode('the_post_type_archive_link',array($this,'the_post_type_archive_link'));
        //add_shortcode('pagination',array($this,'pagination'));
        //ショートコードの前後のPタグ削除
        //add_filter('the_content',array($this,'shortcode_remove_p'));
        //add_filter('the_content',array($this,'run_shortcode_before_wpautop'),9);
        add_action('init', function() {
            remove_filter('the_title', 'wptexturize');
            remove_filter('the_content', 'wptexturize');
            remove_filter('the_excerpt', 'wptexturize');
            //remove_filter('the_title', 'wpautop');
            //remove_filter('the_content', 'wpautop');
            //remove_filter('the_excerpt', 'wpautop');
            remove_filter('the_title', 'do_shortcode');
            remove_filter('the_content', 'do_shortcode');
            remove_filter('the_excerpt', 'do_shortcode');
            remove_filter('the_editor_content', 'wp_richedit_pre');
        });
        //ビジュアルエディタ
        add_filter('tiny_mce_before_init',function($init) {
            //$init['wpautop'] = false;
            //$init['apply_source_formatting'] = true;
            $init['indent']                  = true;
            $init['valid_elements']          = '*[*]';
            $init['extended_valid_elements'] = '*[*]';
            $init['valid_children']          = '+body[style|span|br],+div[div|span|br],+span[span|br],+p[span|br]';
            $init['verify_html']             = false;
            $screen = get_current_screen();
            if('medical_menu' == $screen->post_type){
                //$init['wpautop'] = false;
            }
            return $init;
        });
        add_action('the_title',array($this,'wpautop'));
        add_action('the_content',array($this,'wpautop'));
        add_action('the_excerpt',array($this,'wpautop'));
        //ショートコードの実行
        add_filter('the_title',array($this,'do_shortcode'));
        add_filter('the_content',array($this,'do_shortcode'));
        add_filter('the_excerpt',array($this,'do_shortcode'));
        add_filter('widget_text',array($this,'do_shortcode'));
        //カスタムメニュー
        add_shortcode('wp_nav_menu',array($this,'shortcode_wp_nav_menu'));
    }
    //ショートコードの実行
    function do_shortcode( $content, $ignore_html = false ) {
        global $shortcode_tags;

        if ( false === strpos( $content, '[' ) ) {
            return $content;
        }

        if (empty($shortcode_tags) || !is_array($shortcode_tags))
            return $content;

        // Find all registered tag names in $content.
        preg_match_all( '@\[([^<>&/\[\]\x00-\x20=]++)@', $content, $matches );
        $tagnames = array_intersect( array_keys( $shortcode_tags ), $matches[1] );

        if ( empty( $tagnames ) ) {
            return $content;
        }

        //$content = do_shortcodes_in_html_tags( $content, $ignore_html, $tagnames );

        $pattern = get_shortcode_regex( $tagnames );
        $content = preg_replace_callback( "/$pattern/", 'do_shortcode_tag', $content );

        // Always restore square braces so we don't break things like <!--[if IE ]>
        $content = unescape_invalid_shortcodes( $content );

        return $content;
    }
    //ショートコードの前後のPタグ削除
    function wpautop( $pee, $br = true ) {
        $pre_tags = array();

        if ( trim($pee) === '' )
            return '';

        // Just to make things a little easier, pad the end.
        $pee = $pee . "\n";

        /*
         * Pre tags shouldn't be touched by autop.
         * Replace pre tags with placeholders and bring them back after autop.
         */
        if ( strpos($pee, '<pre') !== false ) {
            $pee_parts = explode( '</pre>', $pee );
            $last_pee = array_pop($pee_parts);
            $pee = '';
            $i = 0;

            foreach ( $pee_parts as $pee_part ) {
                $start = strpos($pee_part, '<pre');

                // Malformed html?
                if ( $start === false ) {
                    $pee .= $pee_part;
                    continue;
                }

                $name = "<pre wp-pre-tag-$i></pre>";
                $pre_tags[$name] = substr( $pee_part, $start ) . '</pre>';

                $pee .= substr( $pee_part, 0, $start ) . $name;
                $i++;
            }

            $pee .= $last_pee;
        }
        // Change multiple <br>s into two line breaks, which will turn into paragraphs.
        $pee = preg_replace('|<br\s*/?>\s*<br\s*/?>|', "\n\n", $pee);

        $allblocks = '(?:a|table|thead|tfoot|caption|col|colgroup|tbody|tr|td|th|div|dl|dd|dt|ul|ol|li|pre|form|map|area|blockquote|address|math|style|p|h[1-6]|hr|fieldset|legend|section|article|aside|hgroup|header|footer|nav|figure|figcaption|details|menu|summary)';

        // Add a single line break above block-level opening tags.
        $pee = preg_replace('!(<' . $allblocks . '[\s/>])!', "\n$1", $pee);

        // Add a double line break below block-level closing tags.
        $pee = preg_replace('!(</' . $allblocks . '>)!', "$1\n\n", $pee);

        // Standardize newline characters to "\n".
        $pee = str_replace(array("\r\n", "\r"), "\n", $pee);

        // Find newlines in all elements and add placeholders.
        $pee = wp_replace_in_html_tags( $pee, array( "\n" => " <!-- wpnl --> " ) );

        // Collapse line breaks before and after <option> elements so they don't get autop'd.
        if ( strpos( $pee, '<option' ) !== false ) {
            $pee = preg_replace( '|\s*<option|', '<option', $pee );
            $pee = preg_replace( '|</option>\s*|', '</option>', $pee );
        }

        /*
         * Collapse line breaks inside <object> elements, before <param> and <embed> elements
         * so they don't get autop'd.
         */
        if ( strpos( $pee, '</object>' ) !== false ) {
            $pee = preg_replace( '|(<object[^>]*>)\s*|', '$1', $pee );
            $pee = preg_replace( '|\s*</object>|', '</object>', $pee );
            $pee = preg_replace( '%\s*(</?(?:param|embed)[^>]*>)\s*%', '$1', $pee );
        }

        /*
         * Collapse line breaks inside <audio> and <video> elements,
         * before and after <source> and <track> elements.
         */
        if ( strpos( $pee, '<source' ) !== false || strpos( $pee, '<track' ) !== false ) {
            $pee = preg_replace( '%([<\[](?:audio|video)[^>\]]*[>\]])\s*%', '$1', $pee );
            $pee = preg_replace( '%\s*([<\[]/(?:audio|video)[>\]])%', '$1', $pee );
            $pee = preg_replace( '%\s*(<(?:source|track)[^>]*>)\s*%', '$1', $pee );
        }

        // Remove more than two contiguous line breaks.
        $pee = preg_replace("/\n\n+/", "\n\n", $pee);

        // Split up the contents into an array of strings, separated by double line breaks.
        $pees = preg_split('/\n\s*\n/', $pee, -1, PREG_SPLIT_NO_EMPTY);

        // Reset $pee prior to rebuilding.
        $pee = '';

        // Rebuild the content as a string, wrapping every bit with a <p>.
        foreach ( $pees as $tinkle ) {
            //$pee .= '<p>' . trim($tinkle, "\n") . "</p>\n";
            $pee .= trim($tinkle, "\n");
        }

        // Under certain strange conditions it could create a P of entirely whitespace.
        $pee = preg_replace('|<p>\s*</p>|', '', $pee);

        // Add a closing <p> inside <div>, <address>, or <form> tag if missing.
        $pee = preg_replace('!<p>([^<]+)</(div|address|form)>!', "<p>$1</p></$2>", $pee);

        // If an opening or closing block element tag is wrapped in a <p>, unwrap it.
        $pee = preg_replace('!<p>\s*(</?' . $allblocks . '[^>]*>)\s*</p>!', "$1", $pee);

        // In some cases <li> may get wrapped in <p>, fix them.
        $pee = preg_replace("|<p>(<li.+?)</p>|", "$1", $pee);

        // If a <blockquote> is wrapped with a <p>, move it inside the <blockquote>.
        $pee = preg_replace('|<p><blockquote([^>]*)>|i', "<blockquote$1><p>", $pee);
        $pee = str_replace('</blockquote></p>', '</p></blockquote>', $pee);

        // If an opening or closing block element tag is preceded by an opening <p> tag, remove it.
        $pee = preg_replace('!<p>\s*(</?' . $allblocks . '[^>]*>)!', "$1", $pee);

        // If an opening or closing block element tag is followed by a closing <p> tag, remove it.
        $pee = preg_replace('!(</?' . $allblocks . '[^>]*>)\s*</p>!', "$1", $pee);

        // Optionally insert line breaks.
        if ( $br ) {
            // Replace newlines that shouldn't be touched with a placeholder.
            $pee = preg_replace_callback('/<(script|style).*?<\/\\1>/s', '_autop_newline_preservation_helper', $pee);

            // Normalize <br>
            $pee = str_replace( array( '<br>', '<br/>' ), '<br />', $pee );

            // Replace any new line characters that aren't preceded by a <br /> with a <br />.
            $pee = preg_replace('|(?<!<br />)\s*\n|', "<br />\n", $pee);

            // Replace newline placeholders with newlines.
            $pee = str_replace('<WPPreserveNewline />', "\n", $pee);
        }

        // If a <br /> tag is after an opening or closing block tag, remove it.
        $pee = preg_replace('!(</?' . $allblocks . '[^>]*>)\s*<br />!', "$1", $pee);

        // If a <br /> tag is before a subset of opening or closing block tags, remove it.
        $pee = preg_replace('!<br />(\s*</?(?:a|article|section|p|li|div|dl|dd|dt|th|pre|td|ul|ol)[^>]*>)!', '$1', $pee);
        $pee = preg_replace( "|\n</p>$|", '</p>', $pee );

        // Replace placeholder <pre> tags with their original content.
        if ( !empty($pre_tags) )
            $pee = str_replace(array_keys($pre_tags), array_values($pre_tags), $pee);

        // Restore newlines in all elements.
        if ( false !== strpos( $pee, '<!-- wpnl -->' ) ) {
            $pee = str_replace( array( ' <!-- wpnl --> ', '<!-- wpnl -->' ), "\n", $pee );
        }

        return $pee;
    }
    function run_shortcode_before_wpautop( $content ) {
        global $shortcode_tags;
        // 登録されているショートコードを退避して空に
        $orig_shortcode_tags = $shortcode_tags;
        remove_all_shortcodes();
        // wpautop関数実行前に処理したショートコードをここで追加
        add_shortcode( 'period', 'shortcode_period' );
        $content = do_shortcode( $content );
        // 退避したショートコードを元に戻す
        $shortcode_tags = $orig_shortcode_tags;
        return $content;
    }
    function shortcode_remove_p($content) {
        $array = array (
            '<p>[' => '[',
            ']</p>' => ']',
            ']<br />' => ']'
        );
        $content = strtr($content, $array);
        return $content;
    }
    // posts
    function posts($atts,$content=NULL){
        //$content = $this->shortcode_remove_p($content);
        //test($content);
        $data = shortcode_atts( array(
            'post_type'      => 'post',
            'category_name'  => '',
            'posts_per_page' => get_option('posts_per_page'),
            'none'           => '現在表示可能なリストはありません...',
            'pagination'     => false,
        ),$atts);
        if(!empty($data['pagination'])){
            $paged = get_query_var('paged') ? get_query_var('paged') : 1;
            $data['paged'] = $paged;
        }
        $the_query = new WP_Query($data);
        // The Loop
        $html = '';
        if($the_query->have_posts()){
            $html = '<div class="shortcode posts post_type-'.$data['post_type'].' '.$data['category_name'].'">';
            while($the_query->have_posts()){
                $the_query->the_post();
                $postclass = implode(' ',get_post_class());
                $html .= '<article class="'.$postclass.'">'.do_shortcode($content).'</article>';
            }
            if(!empty($data['pagination'])){
                $pagination = new Pagination();
                $html .= $pagination->get_the_pagination();
            }
            $html .= '</div>';
        }else{
            $html .= '<p class="none-list">'.$data['none'].'</p>';
        }
        wp_reset_postdata();
        return $html;
    }
    function the_time($atts){
        $data[0] = $atts[0]?$atts[0]:'Y.m.d';
        if($data[0] == 'Y.m.d'){
            return '<time class="date time" datetime="'.date(DATE_RFC3339,get_post_time('U',true)).'">'.get_the_time('Y.m.d').'</time>';
        }elseif($data[0] == 'datetime'){
            return date(DATE_RFC3339,get_post_time('U',true));
        }else{
            return get_the_time($data[0]);
        }
    }
    function the_content(){
        return get_the_content();
    }
    function the_term_link($atts){
        $data = shortcode_atts( array(
            'term' => '',
            'taxonomy' => 'category',
        ),$atts);
        if(!empty($data['term']) && !empty($data['taxonomy'])){
            $term = get_term_by('id',$data['term'],$data['taxonomy']);
            if(empty($term) || is_wp_error($term)){
                $term = get_term_by('slug',$data['term'],$data['taxonomy']);
            }
            if(empty($term) || is_wp_error($term)){
                $term = get_term_by('name',$data['term'],$data['taxonomy']);
            }
            if(!empty($term) && !is_wp_error($term)){
                return get_term_link($term,$data['taxonomy']);
            }
        }
        return '#';
    }
    function the_permalink(){
        return get_the_permalink();
    }
    function the_title(){
        return get_the_title();
    }
    function the_post_thumbnail($size='theme_thumbnail'){
        if(has_post_thumbnail()){
            //return get_the_post_thumbnail(get_the_ID(),$size);
            $src = wp_get_attachment_image_src(get_post_thumbnail_id(),$size);
            return '<span class="image" style="background-image:url('.$src[0].');"></span>';
        }else{
            return '<span class="image no-image"></span>';
        }
    }
    function the_excerpt(){
        return get_the_excerpt();
    }
    function the_ID(){
        return get_the_ID();
    }
    function the_terms($atts){
        //test($atts);
        $data[0] = $atts[0]?$atts[0]:'category';
        $terms = get_the_terms(get_the_ID(),$data[0]);
        if(!empty($terms) && !empty($terms[0]->name))return '<span class="cat '.esc_attr($terms[0]->slug).'">'.mb_strtolower($terms[0]->name).'</span>';
        return '';
    }
    function the_terms_full($atts){
        $html = '';
        $data[0] = $atts[0]?$atts[0]:'category';
        $terms = get_the_terms(get_the_ID(),$data[0]);
        if(is_array($terms)){foreach($terms as $term){
            $html .= '<span class="cat '.esc_attr($term->slug).'">'.$term->name.'</span>';
        }}
        return $html;
    }
    function the_post_meta($atts){
        $data = shortcode_atts( array(
            0 => '',
        ),$atts);
        $meta = get_post_meta(get_the_ID(),$data[0],true);
        return $meta;
    }
    function bloginfo($atts){
        $data = shortcode_atts( array(
            0 => '',
        ),$atts);
        if(empty($data[0]))return;
        return get_bloginfo($data[0]);
    }
    function contactBanner(){
        return '<div class="contactbanner"><a href="/%e3%81%8a%e5%95%8f%e3%81%84%e5%90%88%e3%81%9b/"><span><i class="flaticon-email"></i>お問い合わせはこちらから</span></a></div>';
    }
    function the_post_type_archive_link($atts){
        $data = shortcode_atts( array(
            0 => '',
        ),$atts);
        if(empty($data[0]))return;
        return get_post_type_archive_link($data[0]);
    }
    // ショートコードが効かないプロパティーへの対処
    function wp_kses_allowed_html($tags,$context){
        foreach($tags as $k => $v)$tags[$k]['data-image'] = true;
        return $tags;
    }
    function safe_style_css($style_css){
        //$style_css[] = 'background-image';
        foreach($style_css as $k => $v){
            if($v == 'background' || $v == 'background-color'){
                unset($style_css[$k]);
            }
        }
        return $style_css;
    }
    // テーマディレクトリショートコード
    function home_url($atts){
        $data = shortcode_atts( array(
            0 => '',
        ),$atts);
        if(mb_substr($data[0],0,1) != '/')$data[0] = "/{$data[0]}";
        return home_url($data[0]);
    }
    // テーマディレクトリショートコード
    function diruri(){
        return get_bloginfo('stylesheet_directory');
    }
    function assets(){
        return get_bloginfo('stylesheet_directory').'/assets';
    }
    //固定ページのスラッグからページを取得
    function page_slug($atts){
        $data = shortcode_atts( array(
            'slug' => '',
        ),$atts);
        if($data['slug']){
            //固定ページのスラッグからページを取得
            $page = get_page_by_path($data['slug']);
            if(!empty($page) && !empty($page->ID)){
                //ページIDからURLを取得
                return get_permalink($page->ID);
            }else{
                $explode = explode("/",$data['slug']);
                $explode = $explode[(count($explode) - 1)];
                $the_query = new WP_Query(array(
                    'name'           => $explode,
                    'posts_per_page' => 1,
                    'post_type'      => 'page',
                ));
                if(!$the_query->have_posts()){
                    return get_permalink($the_query->posts[0]->ID);
                }
            }
        }
        return '#';
    }
    //投稿スラッグ（固定ページは除く） から 投稿idを取得。
    function post_slug($post_slug){
        if(empty($post_slug))return '#';
        $args = array(
            'name' => $post_slug,
            'post_type' => 'post',
            'post_status' => 'publish',
            'numberposts' => 1
        );
        $found_posts = get_posts($args);
        if($found_posts && !empty($found_posts[0]->ID)){
            return get_permalink($found_posts[0]->ID);
        }else{
            return '#';
        }
    }
    //カスタムメニュー
    function shortcode_wp_nav_menu($atts, $content = null){
        extract(shortcode_atts(
            array(
                'menu'            => '', 
                'container'       => 'div', 
                //'container_class' => '', 
                //'container_id'    => '', 
                //'menu_class'      => 'menu', 
                //'menu_id'         => '',
                'echo'            => true,
                'fallback_cb'     => 'wp_page_menu',
                'before'          => '',
                'after'           => '',
                'link_before'     => '<span>',
                'link_after'      => '</span>',
                'depth'           => 0,
                'walker'          => '',
                'theme_location'  => '',
            ), 
            $atts
        ));

        return wp_nav_menu(array(
            'menu'            => $menu, 
            'container'       => $container, 
            //'container_class' => $container_class, 
            //'container_id'    => $container_id, 
            //'menu_class'      => $menu_class, 
            //'menu_id'         => $menu_id,
            'echo'            => false,
            'fallback_cb'     => $fallback_cb,
            'before'          => $before,
            'after'           => $after,
            'link_before'     => $link_before,
            'link_after'      => $link_after,
            'depth'           => $depth,
            'walker'          => $walker,
            'theme_location'  => $theme_location,
        ));
    }
}
$GLOBALS['ThemeShortCode'] = new ThemeShortCode();
