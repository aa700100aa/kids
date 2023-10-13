<?php
class theme_options {
    function __construct($init = array()) {
        //初期値定義
        $this->classname    = 'theme_options';
        $this->my_nonce     = ('n'.md5(dirname(__FILE__)));
        $this->create_nonce = 'theme_options';
        $this->post_key     = 'theme_options';
        foreach($init as $k => $v)$this->$k = $v;
        //初期設定
        add_action('init',array($this,'init'),PHP_INT_MAX);
        //テーマオプションの設定
        add_action('admin_menu',array($this,'admin_menu'));
        //管理画面メニュー非表示
        add_action('admin_head',array($this,'admin_head'));
    }
    //管理画面メニュー非表示
    function admin_head(){
        //if(!current_user_can('edit_theme_options') && $options = get_option('管理メニュー表示')){
            global $current_user,$menu,$submenu;
            $roles = $current_user->roles;
            if(!empty($options[$roles[0]])){
                $option_submenus = array();
                foreach($options[$roles[0]] as $menu_id => $menu_item){
                    if(!empty($menu_item['submenu'])){
                        foreach($menu_item['submenu'] as $submenu_id => $submenu_item){
                            if(is_array($submenu_item) && count($submenu_item)){
                                $option_submenus[$submenu_id] = $submenu_item;
                            }
                        }
                    }
                }
                foreach($menu as $menu_key => $menu_item){
                    $menu_id = $menu_item[2];
                    if(!empty($options[$roles[0]][$menu_id]['menu_id'])){
                        $menu[$menu_key][1] = 'edit_theme_options';
                    }elseif(!empty($options[$roles[0]][$menu_id]['text'])){
                        $menu[$menu_key][0] = $options[$roles[0]][$menu_id]['text'];
                    }
                    if(!empty($submenu[$menu_id])){
                        foreach($submenu[$menu_id] as $submenu_key => $submenu_item){
                            $submenu_id = $submenu_item[2];
                            if(!empty($options[$roles[0]][$menu_id]['submenu'][$submenu_id]['submenu_id'])){
                                $submenu[$menu_id][$submenu_key][1] = 'edit_theme_options';
                            }elseif(!empty($options[$roles[0]][$menu_id]['submenu'][$submenu_id]['text'])){
                                $submenu[$menu_id][$submenu_key][0] = $options[$roles[0]][$menu_id]['submenu'][$submenu_id]['text'];
                            }
                        }
                    }
                    if(!empty($option_submenus[$menu_id])){
                        if($option_submenus[$menu_id]['submenu_id']){
                            $menu[$menu_key][1] = 'edit_theme_options';
                        }
                        if($option_submenus[$menu_id]['text']){
                            $menu[$menu_key][0] = $option_submenus[$menu_id]['text'];
                        }
                    }
                }
            }
        //}
    }
    //初期設定
    function init(){
        /*$post_types = get_post_types(array('_builtin'=>false,),$output='objects');
        foreach($post_types as $post_type){
            $this->default_options["{$post_type->name}_label"] = array(
                'label'=>"{$post_type->label}ラベル変更",
                'type'=>'text',
                'default'=>$post_type->label,
            );
        }*/
        //ラベル変更
        global $wp_post_types;
        foreach($wp_post_types as $k => $post_type){
            if($label = get_option("{$k}_label")){
                foreach($wp_post_types[$k]->labels as $key => $value){
                    $wp_post_types[$k]->labels->$key = str_replace($post_type->label,$label,$value);
                }
                $wp_post_types[$k]->label = $label;
            }
        }
    }
    //テーマオプションの設定
    function admin_menu(){
        //テーマオプション
        $hook = add_menu_page('テーマオプション','テーマオプション','edit_others_posts','theme_options',array($this,'theme_options'),'dashicons-admin-settings',4);
        add_submenu_page('theme_options','オプション設定','オプション設定','edit_others_posts','theme_options',array($this,'theme_options'),'',4);
        add_action('load-'.$hook,array($this,'update'));
        add_action('load-'.$hook,array($this,'wp_enqueue_styles'));
        $this->menu_hook_name = $hook;
        /*//記事一覧スライダー
        $hook = add_submenu_page('theme_options','記事一覧スライダー','記事一覧スライダー','edit_others_posts','slider',array($this,'slider_brands'),'',4);
        add_action('load-'.$hook,array($this,'update'));
        add_action('load-'.$hook,array($this,'wp_enqueue_styles'));
        $this->slider = $hook;
        //記事一覧サイドバー
        $hook = add_submenu_page('theme_options','記事一覧サイドバー','記事一覧サイドバー','edit_others_posts','sidebar',array($this,'slider_brands'),'',4);
        add_action('load-'.$hook,array($this,'update'));
        add_action('load-'.$hook,array($this,'wp_enqueue_styles'));
        $this->sidebar = $hook;*/
    }
    //メニュー
    function theme_options(){
        require_once(dirname(__FILE__).'/views/menu.php');
    }
    //記事選択
    function slider_brands(){
        wp_enqueue_style('theme_slider_post_editor',get_stylesheet_directory_uri(). '/functions/' . Theme_SCF_Config::NAME . '/css/editor.css');
        require_once(dirname(__FILE__).'/views/slider_brands.php');
    }
    function keyvisual(){
        require_once(dirname(__FILE__).'/views/keyvisual.php');
    }
    function footer(){
        require_once(dirname(__FILE__).'/views/footer.php');
    }
    //更新処理
    function update(){
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $my_nonce = filter_input(INPUT_POST,$this->my_nonce);
            if(wp_verify_nonce($my_nonce, wp_create_nonce($this->create_nonce))) {
                $screen = get_current_screen();
                //テーマオプション
                if($screen->id == $this->menu_hook_name){
                    $theme_options = filter_input(INPUT_POST,$this->post_key,FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
                    //削除処理
                    $keys = array_values($theme_options['label']);
                    foreach($keys as $k => $v)$keys[$k] = strip_tags(trim($v));
                    $add_options = array();
                    $options = get_alloptions();
                    foreach($options as $k => $v){
                        if(is_serialized($v)){
                            $v = maybe_unserialize($v);
                            if(isset($v['option_type']) && $v['option_type'] == 'add_options'){
                                /*if(!in_array($k,$keys)){
                                    delete_option($k);
                                }*/
                                delete_option($k);
                            }
                        }
                    }
                    //更新処理
                    $keys = array_keys($this->default_options);
                    foreach($theme_options['label'] as $k => $v){
                        if(in_array($k,$keys,true)){
                            if($k == 'address'){
                                foreach($theme_options['value']['address'] as $key => $value){
                                    if(trim($value)){
                                        update_option($key,wp_kses_post(mb_convert_kana(trim($value),'a')));
                                    }else{
                                        delete_option($key);
                                    }
                                }
                            }elseif($k == 'blog_yoyaku_form' || $k == 'blog_contact_form'){
                                $post_id = intval($theme_options['value'][$k]['post_id']);
                                $image   = intval($theme_options['value'][$k]['image']);
                                //test($post_id);exit;
                                if(empty($post_id) && empty($image)){
                                    delete_option($k);
                                }else{
                                    update_option($k,array(
                                        'post_id' => $post_id,
                                        'image'   => $image,
                                    ));
                                }
                            }elseif($k == 'business_hours' || $k == 'reception_time'){
                                foreach($theme_options['value'][$k] as $key => $value){
                                    if($value !== ''){
                                        if($key == 'b_text' || $key == 'r_text'){
                                            update_option($key,wp_kses_post(mb_convert_kana(trim($value),'a')));
                                        }else{
                                            update_option($key,intval($value));
                                        }
                                    }else{
                                        delete_option($key);
                                    }
                                }
                            }elseif($this->default_options[$k]['type'] == 'number'){
                                update_option($k,intval($theme_options['value'][$k]));
                            }elseif($this->default_options[$k]['type'] == 'images'){
                                $data = array();
                                if(is_array($theme_options['value'][$k])){
                                    foreach($theme_options['value'][$k] as $kk => $vv){
                                        if(!empty($vv)){
                                            $data[] = $vv;
                                        }
                                    }
                                }
                                update_option($k,$data);
                            }elseif($this->default_options[$k]['type'] == 'custom-post-types'){
                                $post_labels     = $this->trim_emspace($theme_options['value'][$k]['post_label']);
                                $post_slugs      = $this->trim_emspace($this->mb_convert_change($theme_options['value'][$k]['post_slug'],"ka","UTF-8"));
                                $taxonomy_labels = $this->trim_emspace($theme_options['value'][$k]['taxonomy_label']);
                                $taxonomy_slugs  = $this->trim_emspace($this->mb_convert_change($theme_options['value'][$k]['taxonomy_slug'],"ka","UTF-8"));
                                $post_labels     = array_unique($post_labels);
                                $post_slugs      = array_unique($post_slugs);
                                $taxonomy_labels = array_unique($taxonomy_labels);
                                $taxonomy_slugs  = array_unique($taxonomy_slugs);
                                $post_types      = array();
                                foreach($post_slugs as $key => $post_slug){
                                    if(!empty($post_labels[$key]) && !empty($post_slugs[$key]) && !empty($taxonomy_labels[$key]) && !empty($taxonomy_slugs[$key]) && !in_array($post_slugs[$key],array('post','page',)) && !in_array($taxonomy_slugs[$key],array('category','post_tag',)) && preg_match("/^[a-zA-Z0-9\-_]+$/",$post_slugs[$key]) && preg_match("/^[a-zA-Z0-9\-_]+$/",$taxonomy_slugs[$key])){
                                        $post_types[] = array(
                                            'post_label'     => $post_labels[$key],
                                            'post_slug'      => $post_slugs[$key],
                                            'taxonomy_label' => $taxonomy_labels[$key],
                                            'taxonomy_slug'  => $taxonomy_slugs[$key],
                                        );
                                    }
                                }
                                //test($post_types);exit;
                                update_option($k,$post_types);
                            }else{
                                update_option($k,wp_kses_post(trim($theme_options['value'][$k])));
                            }
                        }else{
                            if(strip_tags(trim($v))){
                                //test($theme_options['type'][$k]);
                                if($theme_options['type'][$k] == 'image'){
                                    update_option(strip_tags(trim($v)),array(
                                        'imagevalue' => $theme_options['imagevalue'][$k],
                                        'value' => wp_kses_post(trim($theme_options['value'][$k])),
                                        'option_type' => 'add_options',
                                        'order' => $k,
                                        'type' => $theme_options['type'][$k],
                                    ));
                                }else{
                                    update_option(strip_tags(trim($v)),array(
                                        'imagevalue' => '',
                                        'value' => wp_kses_post(trim($theme_options['value'][$k])),
                                        'option_type' => 'add_options',
                                        'order' => $k,
                                        'type' => $theme_options['type'][$k],
                                    ));
                                }
                            }
                        }
                    }
                //キービジュアル
                }elseif($screen->id == $this->keyvisual){
                    $theme_options = filter_input(INPUT_POST,$this->post_key,FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
                    //キャンペーン
                    if(is_array($theme_options['campaign']['image']) && count($theme_options['campaign']['image'])){
                        $campaigns = array();
                        foreach($theme_options['campaign']['image'] as $key => $value){
                            if(!empty($theme_options['campaign']['image'])){
                                $campaigns[] = array(
                                    'image'  => $value,
                                    'url'    => esc_attr(trim($theme_options['campaign']['url'][$key])),
                                    'target' => esc_attr(trim($theme_options['campaign']['target'][$key])),
                                );
                            }
                        }
                        if(is_array($campaigns) && count($campaigns)){
                            update_option('キービジュアル',$campaigns);
                        }else{
                            delete_option('キービジュアル');
                        }
                    }else{
                        delete_option('キービジュアル');
                    }
                //スライダー
                }elseif($screen->id == $this->slider){
                    $theme_options = filter_input(INPUT_POST,$this->post_key,FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
                    if(is_array($theme_options) && count($theme_options)){
                        update_option('記事一覧スライダー',$theme_options);
                    }else{
                        delete_option('記事一覧スライダー');
                    }
                //サイドバー
                }elseif($screen->id == $this->sidebar){
                    $theme_options = filter_input(INPUT_POST,$this->post_key,FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
                    if(is_array($theme_options) && count($theme_options)){
                        update_option('記事一覧サイドバー',$theme_options);
                    }else{
                        delete_option('記事一覧サイドバー');
                    }
                }
                //リダイレクト処理
                $page      = filter_input(INPUT_GET,'page');
                $tabvalue  = filter_input(INPUT_POST,'tabvalue')?'&tabvalue='.filter_input(INPUT_POST,'tabvalue'):'';
                $page_slug = filter_input(INPUT_GET,'page_slug')?'&page_slug='.filter_input(INPUT_GET,'page_slug'):'';
                wp_safe_redirect(admin_url('admin.php?page='.$page.'&update=ok'.$tabvalue.$page_slug));
                exit;
            }else{
                
            }
        }
    }
    function mb_convert_change($array, $option, $encoding){
        if(is_array($array)){
            foreach($array as $i => $key){   //解説②     
                if(is_array($key)){
                    $array[$i] = $this->mb_convert_change($array[$i], $option, $encoding);     //解説③
                }else{
                    $array[$i] = mb_convert_kana($key, $option, $encoding);    //解説④
                }
            }
        }else{
            $array = mb_convert_kana($array, $option, $encoding);   //解説⑤
        }
        return $array;
    }
    function trim_emspace($str) {
        if(is_array($str)){
            foreach($str as $k => $v){
                if(is_array($key)){
                    $str[$k] = $this->trim_emspace($v);
                }else{
                    // 先頭の半角、全角スペースを、空文字に置き換える
                    $str[$k] = preg_replace('/^[ 　]+/u', '', $v);
                    // 最後の半角、全角スペースを、空文字に置き換える
                    $str[$k] = preg_replace('/[ 　]+$/u', '', $v);
                }
            }
        }else{
            // 先頭の半角、全角スペースを、空文字に置き換える
            $str = preg_replace('/^[ 　]+/u', '', $str);
            // 最後の半角、全角スペースを、空文字に置き換える
            $str = preg_replace('/[ 　]+$/u', '', $str);
        }
        return $str;
    }
    //css・javascript
    function wp_enqueue_styles(){
        //jquery ui
        wp_enqueue_script('jqueryui','//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js',array('jquery'),'',true);
        wp_enqueue_style('jqueryui','//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css');
        //jquery ui
        wp_enqueue_script($this->classname.'-script',get_stylesheet_directory_uri().'/functions/'.$this->classname.'/assets/js/admin.js',array('jquery'),'',true);
        wp_enqueue_style($this->classname.'-style',get_stylesheet_directory_uri().'/functions/'.$this->classname.'/assets/css/admin.css');
    }
}

if(!function_exists('get_the_myoption')){
    function the_myoption($key){
        if(is_array($key))$key=$key[0];
        echo get_the_myoption($key);
    }
    function get_the_myoption($key){
        global $theme_options;
        if(is_array($key))$key=$key[0];
        $value = get_option($key);
        $default_options = array();
        foreach($theme_options->default_options as $k => $v)$default_options[$k] = $v['label'];
        if(in_array($key,$default_options)){
            if($key == '住所'){
                if(($pref = get_option('pref')) && ($address01 = get_option('address01')) && ($address02 = get_option('address02'))){
                    $return = '';
                    if($zip = get_option('zip')){
                        $return .= '<span class="zip">〒'.substr($zip, 0, 3).'-'.substr($zip, 3).'</span>';
                    }
                    $return .= '<span class="pref address01">'.$pref.$address01.'</span>';
                    $return .= '<span class="address02 building">'.$address02.get_option('building').'</span>';
                }
            }elseif($key == '営業時間' || $key == '受付時間'){
                if($key == '営業時間'){
                    $name  = 'business_hours';
                    $start = get_option('b_start');
                    $end   = get_option('b_end');
                    $text  = get_option('b_text');
                }else{
                    $name  = 'reception_time';
                    $start = get_option('r_start');
                    $end   = get_option('r_end');
                    $text  = get_option('r_text');
                }
                if($start === NULL || $end === NULL || $start >= $end){
                    $text = '24時間営業';
                }
                if(!empty($text)){
                    $return = '<span class="'.$name.' start">'.$text.'</span>';
                }else{
                    $return = '<span class="'.$name.' start">'.second_format($start).'</span><span class="'.$name.' end">'.second_format($end).'</span>';
                }
            }else{
                $k = array_search($key,$default_options);
                if($theme_options->default_options[$k]['type'] == 'image'){
                    $value = get_option($k);
                    if($value){
                        $return = wp_get_attachment_image($value,'full');
                    }else{
                        $return = '';
                    }
                }else{
                    $return = do_shortcode(ThemeShortCode::wpautop(get_option($k)));
                }
            }
        }elseif(!empty($value) && $value['type'] == 'image'){
            if($value['imagevalue']){
                $return = wp_get_attachment_image($value['imagevalue'],'full');
            }else{
                $return = '';
            }
        }else{
            $return = do_shortcode(ThemeShortCode::wpautop($value['value']));
        }
        return $return;
    }
    function second_format($i){
        if($i < 86400 || (get_option('time_show_type') && !is_admin())){
            return sprintf('%02d',floor($i/3600)) . gmdate(":i", $i);
        }else{
            return '翌'. sprintf('%02d',floor(($i-86400)/3600)) . gmdate(":i", $i);
        }
    }
    add_shortcode('the_myoption','get_the_myoption');
}
