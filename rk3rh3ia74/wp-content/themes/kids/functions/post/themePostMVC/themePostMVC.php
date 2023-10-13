<?php
if(!class_exists('themePostModel')){
    /* Model */
    class themePostModel {
        function __construct($init = array()) {
            //初期設定
            foreach($init as $k => $v)$this->$k = $v;
            //カスタム投稿登録
            add_action('init',array($this,'register_post_type'));
            //カスタム投稿（アーカイブ表示件数）
            add_action('current_screen',array($this,'current_screen_posts_per_page'));
            add_action('pre_get_posts', array($this,'archive_pre_get_posts'));
            //カスタム投稿一覧ページ設定時の処理
            add_filter('display_post_states',array($this,'display_post_states'),PHP_INT_MAX,2);
            add_filter('post_type_archive_link',array($this,'post_type_archive_link'),PHP_INT_MAX,2);
            //カスタム分類登録
            if(isset($this->register_taxonomy) && is_array($this->register_taxonomy) && count($this->register_taxonomy)){
                add_action('init',          array($this,'register_taxonomy'));
                add_action('pre_get_posts', array($this,'pre_get_posts'));
                //ラベル変更
                add_action('current_screen',array($this,'edit_taxonomies_label'));
                //termのデフォルト値
                add_action('load-options-writing.php',array($this,'add_default_term_setting_item'));
                add_filter('whitelist_options',       array($this,'allow_default_term_setting'));
                add_action('wp_insert_post',          array($this,'add_post_type_default_term'),10,2);
                add_filter('map_meta_cap',            array($this,'map_meta_cap'),10,4);
                //カスタム分類(カスタムフィールド)
                //register_meta('term','mail_id',             array($this,'term_meta_mail_id'));
                //add_action('category_add_form_fields',      array($this,'category_add_form_fields'));
                //add_action('create_category',               array($this,'save_term_meta_mail_id'));
                //add_filter('manage_edit-category_columns',  array($this,'edit_term_columns'));
                //add_filter('manage_category_custom_column', array($this,'manage_term_custom_column'),10,3);
            }
            //既存のタクソノミーを削除
            if(!empty($this->delete_taxonomies) && $this->post_type == 'post'){
                add_action('init',array($this,'delete_taxonomies'));
            }
            //既存のタクソノミーのラベルを編集
            if(!empty($this->edit_taxonomies) && $this->post_type == 'post'){
                add_action('init',array($this,'edit_taxonomies'));
            }
            //特定機能のサポートを削除
            if(!empty($this->remove_post_type_support) && is_array($this->remove_post_type_support) && count($this->remove_post_type_support)){
                add_action('init',array($this,'remove_post_type_support'),PHP_INT_MAX);
            }
            if(!empty($this->custom_fields) && is_array($this->custom_fields)){
                //プレビューの対応
                add_action('wp_insert_post',         array($this,'save_preview_postmeta'));
                add_filter('get_post_metadata',      array($this,'get_preview_postmeta'),10,4);
                add_filter('preview_post_meta_keys', array($this,'preview_post_meta_keys'));
                //カスタムフィールド設定
                add_action('admin_menu',array($this,'add_meta_box'));
                //カスタムフィールド保存
                add_action('save_post',array($this,'save_post'),10,3);
                //管理画面 一覧カラム追加
                add_filter("manage_{$this->post_type}_posts_columns",       array($this,'manage_posts_columns'));
                add_action("manage_{$this->post_type}_posts_custom_column", array($this,'manage_posts_custom_column'),10,2);
            }
        }
        //プレビューの対応
        function get_preview_id( $post_id ) {
            global $post;
            $previewId = 0;
            if ( isset($_GET['preview'])
                    && ($post->ID == $post_id)
                        && $_GET['preview'] == true
                            &&  ($post_id == url_to_postid($_SERVER['REQUEST_URI']))
                ) {
                $preview = wp_get_post_autosave($post_id);
                if ($preview != false) { $previewId = $preview->ID; }
            }
            return $previewId;
        }
        function get_preview_postmeta($return,$post_id,$meta_key,$single){
            if(get_post_type($post_id) == $this->post_type){
                //test(get_post_type($post_id));
                if($preview_id = $this->get_preview_id($post_id)){
                    //test($preview_id);
                    if($post_id != $preview_id){
                        //$return = get_post_meta($preview_id,$meta_key,$single);
                        $return = get_post_meta($preview_id,$meta_key,$single);
                    }
                }
            }
            return $return;
        }
        function save_preview_postmeta($post_ID){
            global $wpdb;
            if(wp_is_post_revision($post_ID)){
                $my_nonce = filter_input(INPUT_POST,md5($this->post_type))?filter_input(INPUT_POST,md5($this->post_type)):null;
                if(!wp_verify_nonce($my_nonce,wp_create_nonce(md5($this->post_type)))){ return $post_ID; }
                remove_action('wp_insert_post', array($this,'save_preview_postmeta'));
                remove_action('save_post',      array($this,'save_post'));
                $wpdb->query("DELETE FROM {$wpdb->postmeta} WHERE post_id = {$post_ID}");
                // 承認ができたのでデータを探して保存
                $post_custom_fields = $this->trim_emspace(filter_input(INPUT_POST,'custom_field',FILTER_DEFAULT,FILTER_REQUIRE_ARRAY));
                //test($post_custom_fields);exit;
                foreach($this->custom_fields as $custom_fields_key => $custom_fields){
                    foreach($custom_fields['fields'] as $field_key => $field){
                        if(in_array($field['name'],array('post_title','post_name','post_parent','post_content'))){
                            $value = wp_kses_post($post_custom_fields[$field['name']]);
                            if($field['name'] == 'post_name'){
                                $post_name = $value;
                                $post_name = $post_name?$post_name:$post_ID;
                                wp_update_post(array(
                                    'ID'           => $post_ID,
                                    $field['name'] => $post_name,
                                ));
                            }else{
                                wp_update_post(array(
                                    'ID'           => $post_ID,
                                    $field['name'] => $value,
                                ));
                            }
                        }else{
                            if(is_array($field['save_post']) || function_exists($field['save_post'])){
                                call_user_func_array($field['save_post'],array($post_ID,$field,$preview_id=$post_ID));
                                //$field['save_post'][0]->$field['save_post'][1]($post_ID,$field);
                                //$field['save_post']($post_ID,$field,$preview_id=$post_ID);
                            //}elseif(function_exists($field['save_post'])){
                                //$field['save_post']($post_ID,$field,$preview_id=$post_ID);
                            }else{
                                add_metadata('post',$post_ID,$field['name'],$post_custom_fields[$field['name']]);
                                //test($post_ID);test($field['name']);test($post_custom_fields[$field['name']]);//exit;
                            }
                        }
                    }
                }
                do_action('save_preview_postmeta',$post_ID);
            }
        }
        function preview_post_meta_keys($metas){
            if($metas[0] == 'meta' && count($metas) == 1){
                $metas = array();
                // 承認ができたのでデータを探して保存
                $post_custom_fields = filter_input(INPUT_POST,'custom_field',FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
                //test($post_custom_fields);exit;
                foreach($this->custom_fields as $custom_fields_key => $custom_fields){
                    foreach($custom_fields['fields'] as $field_key => $field){
                        $metas[] = $field['name'];
                    }
                }
            }
            return $metas;
        }
        //カスタム投稿登録
        function register_post_type(){
            if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['post_type_posts_per_page-submit']) && $_POST['post_type_posts_per_page-submit'] == '変更する' && isset($_POST['post_type']) && $_POST['post_type'] == $this->post_type){
                $my_nonce = filter_input(INPUT_POST,'post_type_posts_per_page_nonce');
                if(wp_verify_nonce($my_nonce, wp_create_nonce('post_type_posts_per_page_nonce'))) {
                    //$new_posts_per_page = intval(trim(mb_convert_kana($_POST['post_type_posts_per_page'],'as')));
                    $new_posts_per_page = intval($_POST['post_type_posts_per_page']);
                    $post_type_object   = get_post_type_object( $this->post_type );
                    if($this->post_type == 'post'){
                        if(!empty($new_posts_per_page) && $new_posts_per_page != 10 && $new_posts_per_page > 0){
                            if(get_option("posts_per_page") != $new_posts_per_page){
                                update_option("posts_per_page",$new_posts_per_page);
                            }
                        }else{
                            update_option("posts_per_page",10);
                        }
                    }else{
                        if(!empty($new_posts_per_page) && $new_posts_per_page != 10){
                            if(get_option("{$this->post_type}-posts_per_page") != $new_posts_per_page){
                                update_option("{$this->post_type}-posts_per_page",$new_posts_per_page);
                            }
                        }else{
                            delete_option("{$this->post_type}-posts_per_page");
                        }
                    }
                }
            }
            if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['page_for_posts-submit']) && $_POST['page_for_posts-submit'] == '変更する' && isset($_POST['post_type']) && $_POST['post_type'] == $this->post_type){
                $my_nonce = filter_input(INPUT_POST,'post_type_posts_per_page_nonce');
                if(wp_verify_nonce($my_nonce, wp_create_nonce('post_type_posts_per_page_nonce'))) {
                    $new_posts_per_page = intval(trim(mb_convert_kana($_POST['page_for_posts'],'as')));
                    $post_type_object   = get_post_type_object( $this->post_type );
                    if($this->post_type == 'post'){
                        if(!empty($new_posts_per_page)){
                            if(get_option("page_for_posts") != $new_posts_per_page){
                                update_option("page_for_posts",$new_posts_per_page);
                            }
                        }else{
                            update_option("page_for_posts",'');
                        }
                    }else{
                        if(!empty($new_posts_per_page)){
                            if(get_option("{$this->post_type}-page_for_posts") != $new_posts_per_page){
                                update_option("{$this->post_type}-page_for_posts",$new_posts_per_page);
                            }
                        }else{
                            delete_option("{$this->post_type}-page_for_posts");
                        }
                    }
                }
            }
            if($this->check_register_post_type()){
                register_post_type($this->post_type,array(
                    'label' => $this->post_type_label,  //カスタム投稿タイプの名前（これが管理画面のメニューに表示される）
                    'labels' => array(
                        'name'                  => $this->post_type_label,
                        'singular_name'         => $this->post_type_label,
                        'add_new'               => "新規追加",
                        'add_new_item'          => "新規{$this->post_type_label}を追加",
                        'edit_item'             => "{$this->post_type_label}の編集",
                        'new_item'              => "新規{$this->post_type_label}",
                        'view_item'             => "{$this->post_type_label}を表示",
                        'view_items'            => "{$this->post_type_label}の表示",
                        'search_items'          => "{$this->post_type_label}を検索",
                        'not_found'             => "{$this->post_type_label}が見つかりませんでした。",
                        'not_found_in_trash'    => "ゴミ箱内に{$this->post_type_label}が見つかりませんでした。",
                        'parent_item_colon'     => "",
                        'all_items'             => "{$this->post_type_label}一覧",
                        'archives'              => "{$this->post_type_label}アーカイブ",
                        'attributes'            => "{$this->post_type_label}の属性",
                        'insert_into_item'      => "{$this->post_type_label}に挿入",
                        'uploaded_to_this_item' => "この{$this->post_type_label}へのアップロード",
                        'featured_image'        => "アイキャッチ画像",
                        'set_featured_image'    => "アイキャッチ画像を設定",
                        'remove_featured_image' => "アイキャッチ画像を削除",
                        'use_featured_image'    => "アイキャッチ画像として使用",
                        'filter_items_list'     => "{$this->post_type_label}リストの絞り込み",
                        'items_list_navigation' => "{$this->post_type_label}リストナビゲーション",
                        'items_list'            => "{$this->post_type_label}リスト",
                        'menu_name'             => $this->post_type_label,
                        'name_admin_bar'        => $this->post_type_label,
                    ),
                    'public'              => (isset($this->public)?boolval($this->public):true), //フロントエンド上で公開(true)
                    'show_ui'             => (isset($this->show_ui)?boolval($this->show_ui):true), //この投稿タイプを管理するデフォルト UI を生成するか
                    'exclude_from_search' => (isset($this->exclude_from_search)?boolval($this->exclude_from_search):true), //サイト内検索の結果に表示(true)
                    'has_archive'         => (isset($this->has_archive)?boolval($this->has_archive):true), //アーカイブページを持つ(true)
                    'menu_position'       => (isset($this->menu_position)?$this->menu_position:6), //管理画面のメニュー順位
                    'menu_icon'           => (isset($this->menu_icon)?$this->menu_icon:'dashicons-admin-post'),
                    'supports'            => ((is_array($this->supports) && count($this->supports))?$this->supports:array('title','slugdiv','author','thumbnail','editor','excerpt','comments'/*'custom-fields',*/)),
                    'rewrite'             => array(
                        'slug' => ($this->post_type_slug?$this->post_type_slug:$this->post_type),
                        'with_front' => false,
                    )
                ));
            }elseif(!empty($this->post_type_label)){
                global $wp_post_types;
                if(isset($wp_post_types[$this->post_type])){
                    $labels = (array)$wp_post_types[$this->post_type]->labels;
                    $wp_post_types[$this->post_type]->labels = (object)array(
                        'name'                  => $this->post_type_label,
                        'singular_name'         => $this->post_type_label,
                        'add_new'               => "新規追加",
                        'add_new_item'          => "新規{$this->post_type_label}を追加",
                        'edit_item'             => "{$this->post_type_label}の編集",
                        'new_item'              => "新規{$this->post_type_label}",
                        'view_item'             => "{$this->post_type_label}を表示",
                        'view_items'            => "{$this->post_type_label}の表示",
                        'search_items'          => "{$this->post_type_label}を検索",
                        'not_found'             => "{$this->post_type_label}が見つかりませんでした。",
                        'not_found_in_trash'    => "ゴミ箱内に{$this->post_type_label}が見つかりませんでした。",
                        'parent_item_colon'     => "",
                        'all_items'             => "{$this->post_type_label}一覧",
                        'archives'              => "{$this->post_type_label}アーカイブ",
                        'attributes'            => "{$this->post_type_label}の属性",
                        'insert_into_item'      => "{$this->post_type_label}に挿入",
                        'uploaded_to_this_item' => "この{$this->post_type_label}へのアップロード",
                        'featured_image'        => "アイキャッチ画像",
                        'set_featured_image'    => "アイキャッチ画像を設定",
                        'remove_featured_image' => "アイキャッチ画像を削除",
                        'use_featured_image'    => "アイキャッチ画像として使用",
                        'filter_items_list'     => "{$this->post_type_label}リストの絞り込み",
                        'items_list_navigation' => "{$this->post_type_label}リストナビゲーション",
                        'items_list'            => "{$this->post_type_label}リスト",
                        'menu_name'             => $this->post_type_label,
                        'name_admin_bar'        => $this->post_type_label,
                    );
                    $wp_post_types[$this->post_type]->label = $this->post_type_label;
                }
                //test($wp_post_types[$this->post_type]);
            }
        }
        function check_register_post_type(){
            if(empty($this->post_type))return false;
            return (!in_array($this->post_type,$this->post_type_reserved_word));
        }
        function current_screen_posts_per_page(){
            $screen = get_current_screen();
            if(!empty($this->post_type) && !in_array($this->post_type,array('page',)) && $screen->id == "edit-{$this->post_type}"){
                add_action('admin_notices',array($this,'posts_per_page_html'));
            }
        }
        function archive_pre_get_posts($wp_query){
            if(!$wp_query->is_main_query() || is_admin())return;
            if($this->post_type == get_query_var('post_type') && is_archive($this->post_type)){
                $post_type_object = get_post_type_object( $this->post_type );
                if($this->post_type == 'post'){
                    $posts_per_page = get_option("posts_per_page",10);
                }else{
                    $posts_per_page = get_option("{$this->post_type}-posts_per_page",10);
                }
                $wp_query->set('posts_per_page',$posts_per_page);
            }
        }
        function posts_per_page_html(){
            $screen           = get_current_screen();
            $post_type_object = get_post_type_object( $this->post_type );
            if(current_user_can($post_type_object->cap->edit_posts)){
                if($this->post_type == 'post'){
                    $value = get_option("posts_per_page",10);
                }else{
                    $value = get_option("{$this->post_type}-posts_per_page",10);
                }
                $action   = (empty($_SERVER["HTTPS"]) ? "http://" : "https://") . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
                ?><div class="notice notice-warning"><form id="post_type_posts_per_page-form" action="<?php echo $action; ?>" method="post" autocomplete="off">
                    <?php wp_nonce_field(wp_create_nonce('post_type_posts_per_page_nonce'),'post_type_posts_per_page_nonce'); ?>
                    <input type="hidden" name="post_type" value="<?php echo esc_attr($this->post_type); ?>">
                    <p>
                        <label>
                            <span style="display:inline-block;vertical-align:middle;">1ページに表示する最大投稿数（アーカイブ）：</span>
                            <input type="number" name="post_type_posts_per_page" value="<?php echo esc_attr($value); ?>" style="display:inline-block;vertical-align:middle;">
                            <button type="submit" name="post_type_posts_per_page-submit" class="button button-primary" style="display:inline-block;vertical-align:middle;" value="変更する"><span>変更する</span></button>
                        </label>
                    </p>
                </form></div><?php
                    if(!in_array($this->post_type,array('page',)) && (isset($this->public)?boolval($this->public):true)){
                        if($this->post_type == 'post'){
                            $value = get_option("page_for_posts");
                        }else{
                            $value = get_option("{$this->post_type}-page_for_posts");
                        }
                ?><div class="notice notice-warning"><form id="page_for_posts-form" action="<?php echo $action; ?>" method="post" autocomplete="off">
                    <?php wp_nonce_field(wp_create_nonce('post_type_posts_per_page_nonce'),'post_type_posts_per_page_nonce'); ?>
                    <input type="hidden" name="post_type" value="<?php echo esc_attr($this->post_type); ?>">
                    <p>
                        <label>
                            <span style="display:inline-block;vertical-align:middle;"><?php echo $this->post_type_label; ?>ページ：</span>
                            <?php echo wp_dropdown_pages(array('name'=>'page_for_posts','echo'=>0,'show_option_none'=>__( '&mdash; Select &mdash;' ),'option_none_value'=>'0','selected'=>$value)); ?>
                            <button type="submit" name="page_for_posts-submit" class="button button-primary" style="display:inline-block;vertical-align:middle;" value="変更する"><span>変更する</span></button>
                        </label>
                    </p>
                </form></div><?php }
            }
        }
        function display_post_states($post_states,$post){
            if(!in_array($this->post_type,array('post','page',)) && $post->ID == get_option("{$this->post_type}-page_for_posts")){
                $post_states[] = "{$this->post_type_label}ページ";
            }
            return $post_states;
        }
        function post_type_archive_link($link, $post_type){
            if($this->post_type == $post_type && $page_id = get_option("{$this->post_type}-page_for_posts")){
                $link = get_permalink($page_id);
            }
            return $link;
        }
        //カスタム分類登録
        function register_taxonomy(){
            if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_taxonomies_label-submit']) && $_POST['edit_taxonomies_label-submit'] == '変更する' && !empty($_POST['taxonomy_name'])){
                $my_nonce = filter_input(INPUT_POST,'edit_taxonomies_label_nonce');
                if(wp_verify_nonce($my_nonce, wp_create_nonce('edit_taxonomies_label_nonce'))) {
                    $new_label = esc_html(trim(mb_convert_kana($_POST['edit_taxonomies_label'],'as')));
                    $taxonomy  = get_taxonomy($_POST['taxonomy_name']);
                    if(!empty($new_label) && $taxonomy->label != $new_label){
                        if(get_option("{$taxonomy->name}-label",$taxonomy->label) != $new_label){
                            update_option("{$taxonomy->name}-label",$new_label);
                            $label = $wp_taxonomies[$taxonomy->name]->label;
                            foreach($wp_taxonomies[$taxonomy->name]->labels as $key => $value){
                                $wp_taxonomies[$taxonomy->name]->labels->$key = str_replace($label,$new_label,$value);
                            }
                        }
                    }else{
                        delete_option("{$taxonomy->name}-label");
                    }
                }
                $my_nonce = filter_input(INPUT_POST,'edit_taxonomies_default_term_nonce');
                if(wp_verify_nonce($my_nonce, wp_create_nonce('edit_taxonomies_default_term_nonce'))) {
                    $option_name  = $this->post_type . '_default_' . $_POST['taxonomy_name'];
                    $default_term = get_option( $option_name );
                    $new_value    = $_POST[$option_name];
                    if(!empty($new_value) && $default_term != $new_value){
                        update_option($option_name,$new_value);
                    }elseif(empty($new_value)){
                        delete_option($option_name);
                    }
                }
            }
            foreach($this->register_taxonomy as $key => $taxonomy){
                //test($taxonomy);
                $taxonomyslug = $taxonomy['slug']?$taxonomy['slug']:$taxonomy['name'];
                if(in_array($taxonomy['name'],array('category','post_tag',))){
                    $taxonomy_obj       = get_taxonomy($taxonomy['name']);
                    $object_type        = $taxonomy_obj->object_type;
                    $object_type[]      = $this->post_type;
                    $taxonomy_post_type = $object_type;
                }else{
                    $taxonomy_obj       = get_taxonomy($taxonomy['name']);
                    $object_type        = $taxonomy_obj->object_type;
                    $object_type[]      = $this->post_type;
                    $taxonomy_post_type = $object_type;
                }
                //test($taxonomy_post_type);
                $taxonomy['label'] = get_option("{$taxonomy['name']}-label",$taxonomy['label']);
                register_taxonomy(
                    $taxonomy['name'], // 新規カスタムタクソノミー名
                    $taxonomy_post_type, // 新規カスタムタクソノミーを反映させる投稿タイプの定義名
                    array(
                        'label'             => $taxonomy['label'],// 表示するカスタムタクソノミー名
                        'hierarchical'      => (isset($taxonomy['hierarchical'])?boolval($taxonomy['hierarchical']):true),//カテゴリーのような階層あり(true)
                        'public'            => (isset($taxonomy['public'])?boolval($taxonomy['public']):true),//タクソノミーは（パブリックに）検索可能(true)
                        'show_admin_column' => (isset($taxonomy['show_admin_column'])?boolval($taxonomy['show_admin_column']):true),//関連付けられた投稿タイプのテーブルにタクソノミーのカラムを自動生成(true)
                        'has_archive'       => true,
                        'labels' => array(
                            'name'                       => $taxonomy['label'],
                            'singular_name'              => $taxonomy['label'],
                            'search_items'               => "{$taxonomy['label']}検索",
                            'popular_items'              => "よく使うもの",
                            'all_items'                  => "全ての{$taxonomy['label']}",
                            'parent_item'                => null,
                            'parent_item_colon'          => null,
                            'edit_item'                  => "{$taxonomy['label']}編集",
                            'update_item'                => "{$taxonomy['label']}更新",
                            'add_new_item'               => "{$taxonomy['label']}追加",
                            'new_item_name'              => __( 'New Writer Name' ),
                            'separate_items_with_commas' => __( 'Separate writers with commas' ),
                            'add_or_remove_items'        => __( 'Add or remove writers' ),
                            'choose_from_most_used'      => __( 'Choose from the most used writers' ),
                            'not_found'                  => "検索「{$taxonomy['label']}」が見つかりません",
                            'menu_name'                  => $taxonomy['label'],
                        ),
                        'rewrite' => array(
                            'slug'       => $taxonomyslug,// カスタムタクソノミースラッグ名
                            'with_front' => false, // falseにすると投稿のパーマリンクで設定した文字列が付かない。初期値はtrue
                            'feeds'      => false, // falseにするとfeedを出力しない。初期値は'has_archive'の値
                            'pages'      => true, // アーカイブページは作るけどページングはいらない場合はfalse。初期値はtrue
                            'ep_mask'    => true,
                        ),
                        //'meta_box_cb' => array(new ThemMetaBoxes(),'post_categories_meta_box'),
                    )
                );
                //カスタムフィールド
                if(!empty($taxonomy['custom_fields']) && is_array($taxonomy['custom_fields']) && count($taxonomy['custom_fields'])){
                    //test($taxonomy['name']);
                    add_action("{$taxonomy['name']}_edit_form_fields",array($this,'taxonomy_edit_form_fields'),10,2);
                    add_action("edit_{$taxonomy['name']}",            array($this,'update_term_meta'));
                    add_action("delete_{$taxonomy['name']}",          array($this,'delete_term_meta'));
                }
            }
        }
        function taxonomy_edit_form_fields($term,$taxonomy){
            foreach($this->register_taxonomy as $key => $_taxonomy){
                if($_taxonomy['name'] == $taxonomy){
                    wp_nonce_field(wp_create_nonce(md5($this->post_type)),md5($this->post_type));
                    wp_enqueue_style( 'wp-color-picker');
                    wp_enqueue_script( 'wp-color-picker');
?><script>
(function($){
    $(document).ready(function(){
        if($('.color-picker').length){
            $('.color-picker').wpColorPicker();
        }
    });
}(jQuery));
</script>        
<?php
                    foreach($_taxonomy['custom_fields'] as $field){
                        $form_item_name  = 'custom_field['.esc_attr($field['name']).']';
                        $form_item_value = get_term_meta($term->term_id,$field['name'],true);
                        //test($field);
                        ?><tr class="form-field">
                            <th scope="row" valign="top"><label for="id-<?php echo md5($field['name']); ?>"><?php echo esc_html(($field['label']?$field['label']:$field['name']))?></label></th>
                            <td>
                                <?php if($field['input_type'] == 'textarea'): ?>
                                    <p><textarea id="id-<?php echo md5($field['name']); ?>" class="widefat" name="<?php echo $form_item_name; ?>" rows="5"><?php echo esc_textarea($form_item_value); ?></textarea></p>
                                <?php elseif($field['input_type'] == 'wp_editor'): ?>
                                    <?php wp_editor($form_item_value,'custom_fields-'.md5($this->post_type.$key.$field['name']),array('textarea_name'=>$form_item_name)); ?>
                                <?php elseif($field['input_type'] == 'checkbox'): ?>
                                    <input id="id-<?php echo md5($field['name']); ?>" class="widefat" type="hidden" name="<?php echo $form_item_name; ?>" value="0">
                                    <p><label><input class="widefat" type="checkbox" name="<?php echo $form_item_name; ?>" value="1" <?php checked($form_item_value,1); ?>> <span class="label"><?php echo esc_html(($field['label']?$field['label']:$field['name'])); ?></span></label></p>
                                    <?php if($description): ?><p class="description">※<?php echo $description; ?></p><?php endif; ?>
                                <?php elseif($field['input_type'] == 'image'): ?>
                                    <?php
                                        $SelectImage = new SelectImage();
                                        $SelectImage->select_html($name=$form_item_name,$attachment_id=$form_item_value);
                                    ?>
                                    <?php if($description): ?><p class="description">※<?php echo $description; ?></p><?php endif; ?>
                                <?php elseif($field['input_type'] == 'media'): ?>
                                    <?php
                                        $SelectMedia = new SelectMedia();
                                        $SelectMedia->select_html($name=$form_item_name,$attachment_id=$form_item_value);
                                    ?>
                                    <?php if($description): ?><p class="description">※<?php echo $description; ?></p><?php endif; ?>
                                <?php elseif(is_array($field['input_type']) || function_exists($field['input_type'])): ?>
                                    <?php
                                        call_user_func_array($field['input_type'],array($post,$form_item_name,$field['name']));
                                        //$field['input_type'][0]->$field['input_type'][1]($post,$form_item_name,$field['name']);
                                    ?>
                                <?php elseif($field['input_type'] == 'color'): ?>
                                    <p><input id="id-<?php echo md5($field['name']); ?>" class="color-picker" type="text" name="<?php echo $form_item_name; ?>" value="<?php echo esc_attr($form_item_value); ?>" <?php echo $placeholder; ?>></p>
                                    <?php if($description): ?><p class="description">※<?php echo $description; ?></p><?php endif; ?>
                                <?php else: ?>
                                    <?php
                                        $placeholder = !empty($field['placeholder'])?'placeholder="'.esc_attr($field['placeholder']).'"':'';
                                        $description = !empty($field['description'])?$field['description']:'';
                                    ?>
                                    <p><input id="id-<?php echo md5($field['name']); ?>" class="widefat" type="<?php echo esc_attr($field['input_type']); ?>" name="<?php echo $form_item_name; ?>" value="<?php echo esc_attr($form_item_value); ?>" <?php echo $placeholder; ?>></p>
                                    <?php if($description): ?><p class="description">※<?php echo $description; ?></p><?php endif; ?>
                                <?php endif; ?>
                            </td>
                        </tr><?php
                    }
                    break;
                }
            }
        }
        function update_term_meta($term_id){
            //2度処理をしないように
            $term = get_term($term_id);
            remove_action("edit_{$term->taxonomy}",array($this,'update_term_meta'));

            //セキュリティー
            $my_nonce = filter_input(INPUT_POST,md5($this->post_type))?filter_input(INPUT_POST,md5($this->post_type)):null;
            if(!wp_verify_nonce($my_nonce,wp_create_nonce(md5($this->post_type)))){ return $term_id; }

            // 承認ができたのでデータを探して保存
            $post_custom_fields = filter_input(INPUT_POST,'custom_field',FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
            foreach($this->register_taxonomy as $key => $_taxonomy){
                if($term->taxonomy == $_taxonomy['name']){
                    foreach($_taxonomy['custom_fields'] as $field){
                        delete_term_meta($term_id,$field['name']);
                        if(!empty($post_custom_fields[$field['name']])){
                            update_term_meta($term_id,$field['name'],trim(mb_convert_kana($post_custom_fields[$field['name']],'as')));
                        }elseif(is_array($field['save_post']) || function_exists($field['save_post'])){
                            call_user_func_array($field['save_post'],array($term_id,$field));
                            //call_user_func($field['save_post'],$term_id,$field);
                        }
                    }
                    break;
                }
            }
        }
        function delete_term_meta($term_id){
            $term = get_term($term_id);
            remove_action("delete_{$term->taxonomy}",array($this,'delete_term_meta'));
            //test($term);exit;
            foreach($this->register_taxonomy as $key => $_taxonomy){
                if($term->taxonomy == $_taxonomy['name']){
                    foreach($_taxonomy['custom_fields'] as $field){
                        delete_term_meta($term_id,$field['name']);
                    }
                    break;
                }
            }
        }
        function pre_get_posts($query){
            if(!is_admin() && $query->is_main_query() && is_tax()){
                foreach($this->register_taxonomy as $key => $taxonomy){
                    if(is_tax($taxonomy['name'])){
                        if(in_array($taxonomy['name'],array('category','post_tag',))){
                            $taxonomy_obj       = get_taxonomy($taxonomy['name']);
                            $object_type        = $taxonomy_obj->object_type;
                            $object_type[]      = $this->post_type;
                            $taxonomy_post_type = $object_type;
                        }else{
                            $taxonomy_post_type = array($this->post_type);
                        }
                        if(in_array($this->post_type,$taxonomy_post_type)){
                            $post_type_object = get_post_type_object( $this->post_type );
                            if($this->post_type == 'post'){
                                $posts_per_page = get_option("posts_per_page",10);
                            }else{
                                $posts_per_page = get_option("{$this->post_type}-posts_per_page",10);
                            }
                            $query->set('posts_per_page',$posts_per_page);
                        }
                        $query->set('post_type',$taxonomy_post_type);
                        break;
                    }
                }
            }
        }
        //ラベル変更・termのデフォルト値
        function edit_taxonomies_label(){
            $screen = get_current_screen();
            //test($screen);
            foreach($this->register_taxonomy as $key => $taxonomy){
                //test($taxonomy);
                if(!in_array($taxonomy['name'],array('category','post_tag',)) && $screen->id == "edit-{$taxonomy['name']}" && $screen->base == 'edit-tags' && $screen->post_type == $this->post_type){
                    add_action('admin_notices',array($this,'edit_taxonomies_label_html'));
                    break;
                }
            }
        }
        function edit_taxonomies_label_html(){
            $screen   = get_current_screen();
            $taxonomy = get_taxonomy($screen->taxonomy);
            if(current_user_can($taxonomy->cap->manage_terms)){
                $value    = get_option("{$taxonomy->name}-label",$taxonomy->label);
                $action   = (empty($_SERVER["HTTPS"]) ? "http://" : "https://") . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
                ?><div class="notice notice-warning"><form id="edit_taxonomies_label-form" action="<?php echo $action; ?>" method="post" autocomplete="off">
                    <?php wp_nonce_field(wp_create_nonce('edit_taxonomies_label_nonce'),'edit_taxonomies_label_nonce'); ?>
                    <input type="hidden" name="taxonomy_name" value="<?php echo esc_attr($taxonomy->name); ?>">
                    <div>
                        <label>
                            <span style="display:inline-block;vertical-align:middle;">名称：</span>
                            <input type="text" name="edit_taxonomies_label" value="<?php echo esc_attr($value); ?>" style="display:inline-block;vertical-align:middle;">
                            <button type="submit" name="edit_taxonomies_label-submit" class="button button-primary" style="display:inline-block;vertical-align:middle;" value="変更する"><span>変更する</span></button>
                        </label>
                    </div>
                </form></div><div class="notice notice-warning"><form id="edit_taxonomies_default_term-form" action="<?php echo $action; ?>" method="post" autocomplete="off">
                    <?php wp_nonce_field(wp_create_nonce('edit_taxonomies_default_term_nonce'),'edit_taxonomies_default_term_nonce'); ?>
                    <input type="hidden" name="taxonomy_name" value="<?php echo esc_attr($taxonomy->name); ?>">
                    <div>
                        <label>
                            <span style="display:inline-block;vertical-align:middle;">初期設定：</span>
                            <?php $this->default_term_setting_field(array('post_type' => $this->post_type,'taxonomy' => $taxonomy )); ?>
                            <button type="submit" name="edit_taxonomies_label-submit" class="button button-primary" style="display:inline-block;vertical-align:middle;" value="変更する"><span>変更する</span></button>
                        </label>
                    </div>
                </form></div><style>
                    #edit_taxonomies_label-form > div ,
                    #edit_taxonomies_default_term-form > div {
                        margin: .5em 0;
                        padding: 2px;
                    }
                    #edit_taxonomies_label-form > div > label > * ,
                    #edit_taxonomies_default_term-form > div > label > * {
                        display: inline-block;
                        vertical-align: middle;
                        margin-top: 0;
                        margin-bottom: 0;
                    }
                </style><?php
            }
        }
        //termのデフォルト値
        function add_default_term_setting_item() {
            $post_type_slug       = $this->post_type;
            $post_type            = get_post_type_object($this->post_type);
            $post_type_taxonomies = get_object_taxonomies( $post_type_slug, false );
            if($post_type_taxonomies){
                foreach ( $post_type_taxonomies as $tax_slug => $taxonomy ) {
                    if (!($post_type_slug == 'post' && $tax_slug == 'category') && $taxonomy->show_ui ) {
                        add_settings_field( $post_type_slug . '_default_' . $tax_slug, $post_type->label . '用' . $taxonomy->label . 'の初期設定' ,array($this,'default_term_setting_field'), 'writing', 'default', array( 'post_type' => $post_type_slug, 'taxonomy' => $taxonomy ) );
                    }
                }
            }
        }
        function default_term_setting_field( $args ) {
            $option_name = $args['post_type'] . '_default_' . $args['taxonomy']->name;
            $default_term = get_option( $option_name );
            $terms = get_terms( $args['taxonomy']->name, 'hide_empty=0' );
            if ( $terms ){
                ?><select name="<?php echo $option_name; ?>" style="display:inline-block;vertical-align:middle;">
                        <option value="0">設定しない</option>
                        <?php foreach ( $terms as $term ){ ?>
                                <option value="<?php echo esc_attr( $term->term_id ); ?>"<?php echo $term->term_id == $default_term ? ' selected="selected"' : ''; ?>><?php echo esc_html( $term->name ); ?></option>
                        <?php } ?>
                </select>
            <?php }else{ ?>
                <p><?php echo esc_html( $args['taxonomy']->label ); ?>が登録されていません。</p><?php
            }
        }
        function allow_default_term_setting( $whitelist_options ) {
            $post_type_slug       = $this->post_type;
            $post_type            = get_post_type_object($this->post_type);
            $post_type_taxonomies = get_object_taxonomies( $post_type_slug, false );
            if ( $post_type_taxonomies ) {
                foreach ( $post_type_taxonomies as $tax_slug => $taxonomy ) {
                    if ( ! ( $post_type_slug == 'post' && $tax_slug == 'category' ) && $taxonomy->show_ui ) {
                        $whitelist_options['writing'][] = $post_type_slug . '_default_' . $tax_slug;
                    }
                }
            }
            return $whitelist_options;
        }
        function add_post_type_default_term( $post_id, $post ) {
            if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || $post->post_status == 'auto-draft' ) { return; }
            $taxonomies = get_object_taxonomies( $post, false );
            if ( $taxonomies ) {
                foreach ( $taxonomies as $tax_slug => $taxonomy ) {
                    $default = get_option( $post->post_type . '_default_' . $tax_slug );
                    if ( ! ( $post->post_type == 'post' && $tax_slug == 'category' ) && $taxonomy->show_ui && $default && ! ( $terms = get_the_terms( $post_id, $tax_slug ) ) ) {
                        if ( $taxonomy->hierarchical ) {
                            $term = get_term( $default, $tax_slug );
                            if ( $term ) {
                                wp_set_post_terms( $post_id, array_filter( array( $default ) ), $tax_slug );
                            }
                        } else {
                            $term = get_term( $default, $tax_slug );
                            if ( $term ) {
                                wp_set_post_terms( $post_id, $term->name, $tax_slug );
                            }
                        }
                    }
                }
            }
        }
        function map_meta_cap($caps, $cap, $user_id, $args){
            if($cap == 'delete_term'){
                $term_id = (int) $args[0];
                $term    = get_term( $term_id );
                if($term->term_id == get_option( $this->post_type.'_default_'.$term->taxonomy)){
                    $caps[] = 'do_not_allow';
                }
            }
            return $caps;
        }
        //カスタムフィールド設定
        function add_meta_box(){
            //test($this->custom_fields);
            /*global $post;
            add_meta_box('test','test',function($post){
                test(get_post_meta($post->ID));
            },'csv_import');*/
            if(is_array($this->custom_fields)){foreach($this->custom_fields as $key => $custom_fields){
                //test($custom_fields);
                add_meta_box('addMetaBox-'.md5($this->post_type.$key),esc_html($key),array($this,'add_meta_box_html'),$this->post_type,$custom_fields['context'],$custom_fields['priority'],array_merge($custom_fields,array('key'=>$key)));
            }}
        }
        function add_meta_box_html($post,$self){
            wp_nonce_field(wp_create_nonce(md5($this->post_type)),md5($this->post_type));
            $custom_fields = $self['args'];
            //test($custom_fields);
            ?><style>
.custom_field .tmce-active .switch-tmce {
    background-color: #fff;
}
.custom_field .wp-editor-tools::after {
    display: none;
}
.custom_field textarea {
    min-height: 120px;
}
.custom_fields p {
    margin: 0;
}
            </style>
            <div class="custom_fields" style="margin: 0 -12px;"><?php
                if(isset($custom_fields['loop']) && $custom_fields['loop'] === true){
                    //jquery ui
                    wp_enqueue_script('jqueryui','//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js',array('jquery'),'',true);
                    wp_enqueue_style('jqueryui','//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css');
                    //sortable
                    wp_enqueue_script('themestyle-sortable-loop',get_stylesheet_directory_uri().'/functions/post/themePostMVC/assets/js/sortable-loop.js',array('jquery','jqueryui',),'');
                    wp_enqueue_style('themestyle-sortable-loop',get_stylesheet_directory_uri().'/functions/post/themePostMVC/assets/css/sortable-loop.css');
                    $form_item_name_key = $custom_fields['key'];
                    $values             = get_post_meta($post->ID,$form_item_name_key,true);
                    //test(get_post_meta($post->ID));
                    ?><div class="sortable-loop-list-action">
                        <span class="dashicons dashicons-list-view"></span>
                        <span class="dashicons dashicons-exerpt-view"></span>
                    </div><?php
                    if(is_array($values) && count($values)){
                        ?><div class="sortable loop"><?php
                        foreach($values as $key => $value){
                            ?>
                                <div class="sortable-loop-item close">
                                    <div class="sortable-loop-header">
                                        <span class="dashicons dashicons-sort"></span>
                                        <span class="dashicons dashicons-plus-alt"></span>
                                        <span class="dashicons dashicons-dismiss"></span>
                                        <span class="dashicons dashicons-arrow-up"></span>
                                    </div>
                                <div class="sortable-loop-body">
                                    <?php
                                    foreach($custom_fields['fields'] as $field_key => $field){
                                        $form_item_name  = "custom_field[{$form_item_name_key}][{$field['name']}][]";
                                        $form_item_value = $value[$field['name']];
                                        ?><div class="custom_field" style="background: #fff;border-bottom: #eee solid 1px;padding: 10px 12px;"><table style="width: 100%;"><tbody><tr>
                                            <?php if(count($custom_fields['fields']) > 1){ ?><th style="width:25%;font-weight: normal;text-align: left;padding: 7px 5px;vertical-align: top;">
                                                <p><?php echo esc_html(($field['label']?$field['label']:$field['name'])); ?></p>
                                            </th><?php } ?><td style="font-weight: normal;text-align: left;padding: 7px 5px;vertical-align: top;">
                                                <?php if(is_array($field['input_type'])): ?>
                                                    <?php
                                                        call_user_func_array($field['input_type'],array($post,$form_item_name,$field['name']));
                                                        //$field['input_type'][0]->$field['input_type'][1]($post,$form_item_name,$field['name']);
                                                    ?>
                                                <?php elseif($field['input_type'] == 'textarea'): ?>
                                                    <p><textarea class="widefat" name="<?php echo $form_item_name; ?>" rows="5"><?php echo esc_textarea($form_item_value); ?></textarea></p>
                                                <?php elseif($field['input_type'] == 'wp_editor'): ?>
                                                    <?php wp_editor($form_item_value,'custom_fields-'.md5($this->post_type.$key.$field['name']),array('textarea_name'=>$form_item_name)); ?>
                                                <?php elseif(strpos($field['input_type'],'callback_') === 0): ?>
                                                    <?php $field['input_type']($post,$form_item_name,$field['name']); ?>
                                                <?php elseif($field['input_type'] == 'checkbox'): ?>
                                                    <input class="widefat" type="hidden" name="<?php echo $form_item_name; ?>" value="0">
                                                    <p><label><input class="widefat" type="checkbox" name="<?php echo $form_item_name; ?>" value="1" <?php checked($form_item_value,1); ?>> <span class="label"><?php echo esc_html(($field['label']?$field['label']:$field['name'])); ?></span></label></p>
                                                    <?php if(!empty($description)): ?><p class="description">※<?php echo $description; ?></p><?php endif; ?>
                                                <?php elseif($field['input_type'] == 'image'): ?>
                                                    <?php
                                                        $SelectImage = new SelectImage();
                                                        $SelectImage->select_html($name=$form_item_name,$attachment_id=$form_item_value);
                                                    ?>
                                                    <?php if(!empty($description)): ?><p class="description">※<?php echo $description; ?></p><?php endif; ?>
                                                <?php elseif($field['input_type'] == 'images'): ?>
                                                    <?php
                                                            wp_enqueue_script('sortable-form-field',get_stylesheet_directory_uri().'/functions/post/themePostMVC/assets/js/themePostMVC.js',array('jquery'),'');
                                                            wp_enqueue_style('sortable-form-field',get_stylesheet_directory_uri().'/functions/post/themePostMVC/assets/css/themePostMVC.css');
                                                    ?>
                                                    <?php $SelectImage = new SelectImage(); ?>
                                                    <div class="sortable-form-field images"><?php if(is_array($form_item_value) && count($form_item_value)){ ?>
                                                        <?php foreach($form_item_value as $form_item_value_key => $form_item){ ?><div class="sortable-item">
                                                            <div class="row infoArea table">
                                                                <div class="table-cell col01 colbox ar">
                                                                    <span class="handle dashicons dashicons-move"></span>
                                                                </div>
                                                                <div class="table-cell col02 colbox ar">
                                                                    <span class="col col01_01 button-add"><i class="dashicons dashicons-plus-alt"></i></span>
                                                                    <span class="col col01_02 button-remove"><i class="dashicons dashicons-dismiss"></i></span>
                                                                </div>
                                                            </div>
                                                            <?php echo $SelectImage->select_html($name="{$form_item_name}[]",$attachment_id=$form_item); ?>
                                                        </div><?php } ?>
                                                    <?php }else{ ?>
                                                        <div class="sortable-item">
                                                            <div class="row infoArea table">
                                                                <div class="table-cell col01 colbox ar">
                                                                    <span class="handle dashicons dashicons-move"></span>
                                                                </div>
                                                                <div class="table-cell col02 colbox ar">
                                                                    <span class="col col01_01 button-add"><i class="dashicons dashicons-plus-alt"></i></span>
                                                                    <span class="col col01_02 button-remove"><i class="dashicons dashicons-dismiss"></i></span>
                                                                </div>
                                                            </div>
                                                            <?php echo $SelectImage->select_html($name="{$form_item_name}[]"); ?>
                                                        </div>
                                                    <?php } ?></div>
                                                    <?php if($description): ?><p class="description">※<?php echo $description; ?></p><?php endif; ?>
                                                <?php elseif($field['input_type'] == 'media'): ?>
                                                    <?php
                                                        //test(get_post_meta($post->ID));
                                                        $SelectMedia = new SelectMedia();
                                                        $SelectMedia->select_html($name=$form_item_name,$attachment_id=$form_item_value);
                                                    ?>
                                                    <?php if(!empty($description)): ?><p class="description">※<?php echo $description; ?></p><?php endif; ?>
                                                <?php elseif($field['input_type'] == 'select'): ?>
                                                    <select name="<?php echo $form_item_name; ?>">
                                                        <?php foreach($field['values'] as $k => $v){ ?><option value="<?php echo esc_attr($k); ?>" <?php selected($form_item_value,$k); ?>><?php echo $v; ?></option><?php } ?>
                                                    </select>
                                                    <?php if(!empty($description)): ?><p class="description">※<?php echo $description; ?></p><?php endif; ?>
                                                <?php else: ?>
                                                    <?php
                                                        $placeholder = !empty($field['placeholder'])?'placeholder="'.esc_attr($field['placeholder']).'"':'';
                                                        $description = !empty($field['description'])?$field['description']:'';
                                                    ?>
                                                    <p><input class="widefat" type="<?php echo esc_attr($field['input_type']); ?>" name="<?php echo $form_item_name; ?>" value="<?php echo esc_attr($form_item_value); ?>" <?php echo $placeholder; ?>></p>
                                                    <?php if(!empty($description)): ?><p class="description">※<?php echo $description; ?></p><?php endif; ?>
                                                <?php endif; ?>
                                            </td>
                                        </tr></tbody></table></div><?php
                                    }
                                ?></div>
                            </div><?php
                        }
                        ?></div><?php
                    }else{
                        ?><div class="sortable loop">
                            <div class="sortable-loop-item">
                                <div class="sortable-loop-header">
                                    <span class="dashicons dashicons-sort"></span>
                                    <span class="dashicons dashicons-plus-alt"></span>
                                    <span class="dashicons dashicons-dismiss"></span>
                                    <span class="dashicons dashicons-arrow-up"></span>
                                </div>
                                <div class="sortable-loop-body">
                                    <?php foreach($custom_fields['fields'] as $field_key => $field){
                                        $form_item_name  = "custom_field[{$form_item_name_key}][{$field['name']}][]";
                                        $form_item_value = '';
                                        ?><div class="custom_field" style="background: #fff;border-bottom: #eee solid 1px;padding: 10px 12px;"><table style="width: 100%;"><tbody><tr>
                                            <?php if(count($custom_fields['fields']) > 1){ ?><th style="width:25%;font-weight: normal;text-align: left;padding: 7px 5px;vertical-align: top;">
                                                <p><?php echo esc_html(($field['label']?$field['label']:$field['name'])); ?></p>
                                            </th><?php } ?><td style="font-weight: normal;text-align: left;padding: 7px 5px;vertical-align: top;">
                                                <?php if(is_array($field['input_type'])): ?>
                                                    <?php
                                                        call_user_func_array($field['input_type'],array($post,$form_item_name,$field['name']));
                                                        //$field['input_type'][0]->$field['input_type'][1]($post,$form_item_name,$field['name']);
                                                    ?>
                                                <?php elseif($field['input_type'] == 'textarea'): ?>
                                                    <p><textarea class="widefat" name="<?php echo $form_item_name; ?>" rows="5"><?php echo esc_textarea($form_item_value); ?></textarea></p>
                                                <?php elseif($field['input_type'] == 'wp_editor'): ?>
                                                    <?php wp_editor($form_item_value,'custom_fields-'.md5($this->post_type.$key.$field['name']),array('textarea_name'=>$form_item_name)); ?>
                                                <?php elseif(strpos($field['input_type'],'callback_') === 0): ?>
                                                    <?php $field['input_type']($post,$form_item_name,$field['name']); ?>
                                                <?php elseif($field['input_type'] == 'checkbox'): ?>
                                                    <input class="widefat" type="hidden" name="<?php echo $form_item_name; ?>" value="0">
                                                    <p><label><input class="widefat" type="checkbox" name="<?php echo $form_item_name; ?>" value="1" <?php checked($form_item_value,1); ?>> <span class="label"><?php echo esc_html(($field['label']?$field['label']:$field['name'])); ?></span></label></p>
                                                    <?php if(!empty($description)): ?><p class="description">※<?php echo $description; ?></p><?php endif; ?>
                                                <?php elseif($field['input_type'] == 'image'): ?>
                                                    <?php
                                                        $SelectImage = new SelectImage();
                                                        $SelectImage->select_html($name=$form_item_name,$attachment_id=$form_item_value);
                                                    ?>
                                                    <?php if(!empty($description)): ?><p class="description">※<?php echo $description; ?></p><?php endif; ?>
                                                <?php elseif($field['input_type'] == 'images'): ?>
                                                    <?php
                                                            wp_enqueue_script('sortable-form-field',get_stylesheet_directory_uri().'/functions/post/themePostMVC/assets/js/themePostMVC.js',array('jquery'),'');
                                                            wp_enqueue_style('sortable-form-field',get_stylesheet_directory_uri().'/functions/post/themePostMVC/assets/css/themePostMVC.css');
                                                    ?>
                                                    <?php $SelectImage = new SelectImage(); ?>
                                                    <div class="sortable-form-field images"><?php if(is_array($form_item_value) && count($form_item_value)){ ?>
                                                        <?php foreach($form_item_value as $form_item_value_key => $form_item){ ?><div class="sortable-item">
                                                            <div class="row infoArea table">
                                                                <div class="table-cell col01 colbox ar">
                                                                    <span class="handle dashicons dashicons-move"></span>
                                                                </div>
                                                                <div class="table-cell col02 colbox ar">
                                                                    <span class="col col01_01 button-add"><i class="dashicons dashicons-plus-alt"></i></span>
                                                                    <span class="col col01_02 button-remove"><i class="dashicons dashicons-dismiss"></i></span>
                                                                </div>
                                                            </div>
                                                            <?php echo $SelectImage->select_html($name="{$form_item_name}[]",$attachment_id=$form_item); ?>
                                                        </div><?php } ?>
                                                    <?php }else{ ?>
                                                        <div class="sortable-item">
                                                            <div class="row infoArea table">
                                                                <div class="table-cell col01 colbox ar">
                                                                    <span class="handle dashicons dashicons-move"></span>
                                                                </div>
                                                                <div class="table-cell col02 colbox ar">
                                                                    <span class="col col01_01 button-add"><i class="dashicons dashicons-plus-alt"></i></span>
                                                                    <span class="col col01_02 button-remove"><i class="dashicons dashicons-dismiss"></i></span>
                                                                </div>
                                                            </div>
                                                            <?php echo $SelectImage->select_html($name="{$form_item_name}[]"); ?>
                                                        </div>
                                                    <?php } ?></div>
                                                    <?php if($description): ?><p class="description">※<?php echo $description; ?></p><?php endif; ?>
                                                <?php elseif($field['input_type'] == 'media'): ?>
                                                    <?php
                                                        //test(get_post_meta($post->ID));
                                                        $SelectMedia = new SelectMedia();
                                                        $SelectMedia->select_html($name=$form_item_name,$attachment_id=$form_item_value);
                                                    ?>
                                                    <?php if(!empty($description)): ?><p class="description">※<?php echo $description; ?></p><?php endif; ?>
                                                <?php elseif($field['input_type'] == 'select'): ?>
                                                    <select name="<?php echo $form_item_name; ?>">
                                                        <?php foreach($field['values'] as $k => $v){ ?><option value="<?php echo esc_attr($k); ?>" <?php selected($form_item_value,$k); ?>><?php echo $v; ?></option><?php } ?>
                                                    </select>
                                                    <?php if(!empty($description)): ?><p class="description">※<?php echo $description; ?></p><?php endif; ?>
                                                <?php else: ?>
                                                    <?php
                                                        $placeholder = !empty($field['placeholder'])?'placeholder="'.esc_attr($field['placeholder']).'"':'';
                                                        $description = !empty($field['description'])?$field['description']:'';
                                                    ?>
                                                    <p><input class="widefat" type="<?php echo esc_attr($field['input_type']); ?>" name="<?php echo $form_item_name; ?>" value="<?php echo esc_attr($form_item_value); ?>" <?php echo $placeholder; ?>></p>
                                                    <?php if(!empty($description)): ?><p class="description">※<?php echo $description; ?></p><?php endif; ?>
                                                <?php endif; ?>
                                            </td>
                                        </tr></tbody></table></div><?php
                                    } ?>
                                </div>
                            </div>
                        </div><?php
                    }
                }else{
                    foreach($custom_fields['fields'] as $field_key => $field){
                        $form_item_name  = in_array($field['name'],array('post_title','post_name','post_parent',))?$field['name']:'custom_field['.esc_attr($field['name']).']';
                        if($field['name'] == 'post_name'){
                            $form_item_value = esc_attr(apply_filters('editable_slug',$post->post_name,$post));
                        }elseif(in_array($field['name'],array('post_title','post_parent',))){
                            $form_item_value = $post->$field['name'];
                        }elseif($field['name'] == 'post_content'){
                            $form_item_value = $post->post_content;
                        }else{
                            $form_item_value = get_post_meta($post->ID,$field['name'],true);
                            if(empty($form_item_value) && !empty($field['defalt'])){
                                $form_item_value = $field['defalt'];
                            }
                        }
                        ?><div class="custom_field" style="background: #fff;border-bottom: #eee solid 1px;padding: 10px 12px;"><table style="width: 100%;"><tbody><tr>
                            <?php if(count($custom_fields['fields']) > 1){ ?><th style="width:25%;font-weight: normal;text-align: left;padding: 7px 5px;vertical-align: top;">
                                <p><?php echo esc_html(($field['label']?$field['label']:$field['name'])); ?></p>
                            </th><?php } ?><td style="font-weight: normal;text-align: left;padding: 7px 5px;vertical-align: top;">
                                <?php if(is_array($field['input_type'])): ?>
                                    <?php
                                        call_user_func_array($field['input_type'],array($post,$form_item_name,$field['name']));
                                        //$field['input_type'][0]->$field['input_type'][1]($post,$form_item_name,$field['name']);
                                    ?>
                                <?php elseif($field['input_type'] == 'textarea'): ?>
                                    <p><textarea class="widefat" name="<?php echo $form_item_name; ?>" rows="5"><?php echo esc_textarea($form_item_value); ?></textarea></p>
                                <?php elseif($field['input_type'] == 'wp_editor'): ?>
                                    <?php wp_editor($form_item_value,'custom_fields-'.md5($this->post_type.$key.$field['name']),array('textarea_name'=>$form_item_name)); ?>
                                <?php elseif(strpos($field['input_type'],'callback_') === 0): ?>
                                    <?php $field['input_type']($post,$form_item_name,$field['name']); ?>
                                <?php elseif($field['input_type'] == 'checkbox'): ?>
                                    <input class="widefat" type="hidden" name="<?php echo $form_item_name; ?>" value="0">
                                    <p><label><input class="widefat" type="checkbox" name="<?php echo $form_item_name; ?>" value="1" <?php checked($form_item_value,1); ?>> <span class="label"><?php echo esc_html(($field['label']?$field['label']:$field['name'])); ?></span></label></p>
                                    <?php if(!empty($description)): ?><p class="description">※<?php echo $description; ?></p><?php endif; ?>
                                <?php elseif($field['input_type'] == 'image'): ?>
                                    <?php
                                        $SelectImage = new SelectImage();
                                        $SelectImage->select_html($name=$form_item_name,$attachment_id=$form_item_value);
                                    ?>
                                    <?php if(!empty($description)): ?><p class="description">※<?php echo $description; ?></p><?php endif; ?>
                                <?php elseif($field['input_type'] == 'images'): ?>
                                    <?php
                                            wp_enqueue_script('sortable-form-field',get_stylesheet_directory_uri().'/functions/post/themePostMVC/assets/js/themePostMVC.js',array('jquery'),'');
                                            wp_enqueue_style('sortable-form-field',get_stylesheet_directory_uri().'/functions/post/themePostMVC/assets/css/themePostMVC.css');
                                    ?>
                                    <?php $SelectImage = new SelectImage(); ?>
                                    <div class="sortable-form-field images"><?php if(is_array($form_item_value) && count($form_item_value)){ ?>
                                        <?php foreach($form_item_value as $form_item_value_key => $form_item){ ?><div class="sortable-item">
                                            <div class="row infoArea table">
                                                <div class="table-cell col01 colbox ar">
                                                    <span class="handle dashicons dashicons-move"></span>
                                                </div>
                                                <div class="table-cell col02 colbox ar">
                                                    <span class="col col01_01 button-add"><i class="dashicons dashicons-plus-alt"></i></span>
                                                    <span class="col col01_02 button-remove"><i class="dashicons dashicons-dismiss"></i></span>
                                                </div>
                                            </div>
                                            <?php echo $SelectImage->select_html($name="{$form_item_name}[]",$attachment_id=$form_item); ?>
                                        </div><?php } ?>
                                    <?php }else{ ?>
                                        <div class="sortable-item">
                                            <div class="row infoArea table">
                                                <div class="table-cell col01 colbox ar">
                                                    <span class="handle dashicons dashicons-move"></span>
                                                </div>
                                                <div class="table-cell col02 colbox ar">
                                                    <span class="col col01_01 button-add"><i class="dashicons dashicons-plus-alt"></i></span>
                                                    <span class="col col01_02 button-remove"><i class="dashicons dashicons-dismiss"></i></span>
                                                </div>
                                            </div>
                                            <?php echo $SelectImage->select_html($name="{$form_item_name}[]"); ?>
                                        </div>
                                    <?php } ?></div>
                                    <?php if($description): ?><p class="description">※<?php echo $description; ?></p><?php endif; ?>
                                <?php elseif($field['input_type'] == 'media'): ?>
                                    <?php
                                        //test(get_post_meta($post->ID));
                                        $SelectMedia = new SelectMedia();
                                        $SelectMedia->select_html($name=$form_item_name,$attachment_id=$form_item_value);
                                    ?>
                                    <?php if(!empty($description)): ?><p class="description">※<?php echo $description; ?></p><?php endif; ?>
                                <?php elseif($field['input_type'] == 'select'): ?>
                                    <select name="<?php echo $form_item_name; ?>">
                                        <?php foreach($field['values'] as $k => $v){ ?><option value="<?php echo esc_attr($k); ?>" <?php selected($form_item_value,$k); ?>><?php echo $v; ?></option><?php } ?>
                                    </select>
                                    <?php if(!empty($description)): ?><p class="description">※<?php echo $description; ?></p><?php endif; ?>
                                <?php else: ?>
                                    <?php
                                        $placeholder = !empty($field['placeholder'])?'placeholder="'.esc_attr($field['placeholder']).'"':'';
                                        $description = !empty($field['description'])?$field['description']:'';
                                    ?>
                                    <p><input class="widefat" type="<?php echo esc_attr($field['input_type']); ?>" name="<?php echo $form_item_name; ?>" value="<?php echo esc_attr($form_item_value); ?>" <?php echo $placeholder; ?>></p>
                                    <?php if(!empty($description)): ?><p class="description">※<?php echo $description; ?></p><?php endif; ?>
                                <?php endif; ?>
                            </td>
                        </tr></tbody></table></div><?php
                    }
                }
            ?></div><?php
        }
        function save_post($post_id, $post, $update){//カスタムフィールド保存
            //2度処理をしないように
            remove_action('save_post',array($this,'save_post'));

            global $post;
            $my_nonce = filter_input(INPUT_POST,md5($this->post_type))?filter_input(INPUT_POST,md5($this->post_type)):null;
            if(!wp_verify_nonce($my_nonce,wp_create_nonce(md5($this->post_type)))){ return $post_id; }
            if($update && wp_is_post_revision($post_id)){ return $post_id; }
            if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE){ return $post_id; }
            if(!current_user_can('edit_post', $post->ID)){ return $post_id; }

            // 承認ができたのでデータを探して保存
            $post_custom_fields = filter_input(INPUT_POST,'custom_field',FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
            foreach($this->custom_fields as $custom_fields_key => $custom_fields){
                if(isset($custom_fields['loop']) && $custom_fields['loop'] === true){
                    $loop_post_values = $post_custom_fields[$custom_fields_key];
                    $first_key        = key(array_slice($post_custom_fields[$custom_fields_key],0,1,true));
                    $update_value     = array();
                    foreach($loop_post_values[$first_key] as $i => $value){
                        foreach($custom_fields['fields'] as $field_key => $field){
                            if(!empty($value)){
                                $update_value[$i][$field['name']] = wp_kses_post($this->trim_emspace($loop_post_values[$field['name']][$i]));
                            }
                        }
                    }
                    update_post_meta($post_id,$custom_fields_key,$update_value);
                }else{
                    foreach($custom_fields['fields'] as $field_key => $field){
                        if(!isset($post_custom_fields[$field['name']]))continue;
                        //test($post_custom_fields[$field['name']]);exit;
                        if(in_array($field['name'],array('post_title','post_name','post_parent','post_content'))){
                            $value = wp_kses_post($this->trim_emspace($post_custom_fields[$field['name']]));
                            if($post->$field['name'] != $value){
                                if($field['name'] == 'post_name'){
                                    $post_name = $value;
                                    $post_name = $post_name?$post_name:$post_id;
                                    wp_update_post(array(
                                        'ID'           => $post_id,
                                        $field['name'] => $post_name,
                                    ));
                                }else{
                                    wp_update_post(array(
                                        'ID'           => $post_id,
                                        $field['name'] => $value,
                                    ));
                                }
                            }
                        }else{
                            if(is_array($field['save_post'])){
                                call_user_func_array($field['save_post'],array($post_id,$field));
                                //$field['save_post'][0]->$field['save_post'][1]($post_id,$field);
                            }elseif(function_exists($field['save_post'])){
                                $field['save_post']($post_id,$field);
                            }else{
                                //update_post_meta($post_id,$field['name'],trim(mb_convert_kana($post_custom_fields[$field['name']],'as')));
                                update_post_meta($post_id,$field['name'],$post_custom_fields[$field['name']]);
                                /*test($post_id);
                                test($field['name']);
                                test($post_custom_fields[$field['name']]);
                                exit;*/
                            }
                        }
                    }
                }
            }
            add_action('save_post',array($this,'save_post'));
            return $post_id;
        }
        //前後の空白削除
        function trim_emspace($str) {
            if(is_array($str)){
                foreach($str as $k => $v){
                    if(is_array($v)){
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
        function manage_posts_columns($columns){
            $this->add_columns = array();
            foreach($this->custom_fields as $key => $custom_fields){
                foreach($custom_fields['fields'] as $custom_field){
                    if(!in_array($custom_field['name'],$custom_fields['hidden_columns'])){
                        $this->add_columns['columns'][$custom_field['name']] = ($custom_field['label']?$custom_field['label']:$custom_field['name']);
                        $this->add_columns['field'][$custom_field['name']]   = $custom_field;
                    }
                }
            }
            if(!empty($this->add_columns['columns']) && is_array($this->add_columns['columns']) && count($this->add_columns['columns'])){
                $columns = array_merge($columns,$this->add_columns['columns']);
            }
            return $columns;
        }
        function manage_posts_custom_column($column,$post_id){
            if(isset($this->add_columns['columns'][$column])){
                if(is_array($this->add_columns['field'][$column]['manage_columns'])){
                    $manage_columns = $this->add_columns['field'][$column]['manage_columns'];
                    $manage_columns[0]->$manage_columns[1]($this->add_columns['field'][$column],$post_id);
                }elseif(function_exists($this->add_columns['field'][$column]['manage_columns'])){
                    $this->add_columns['field'][$column]['manage_columns']($this->add_columns['field'][$column],$post_id);
                }else{
                    $meta_value = get_post_meta($post_id,$column);
                    if(count($meta_value) == 1){
                        if($this->add_columns['field'][$column][input_type] == 'checkbox'){
                            if($meta_value[0]){
                                echo '<span class="dashicons dashicons-yes"></span>';
                            }
                        }else{
                            echo $meta_value[0];
                        }
                    }else{
                        echo implode(',',$meta_value);
                    }
                }
            }
        }
        //既存のタクソノミーを削除
        function delete_taxonomies(){
            global $wp_taxonomies;
            if(is_array($this->delete_taxonomies)){foreach($this->delete_taxonomies as $taxonomy){
                if(!empty($wp_taxonomies[$taxonomy]->object_type)) {
                    foreach ($wp_taxonomies[$taxonomy]->object_type as $i => $object_type) {
                        if($object_type == 'post') {
                            unset($wp_taxonomies[$taxonomy]->object_type[$i]);
                        }
                    }
                }
            }}else{
                if(!empty($wp_taxonomies[$this->delete_taxonomies]->object_type)) {
                    foreach ($wp_taxonomies[$this->delete_taxonomies]->object_type as $i => $object_type) {
                        if($object_type == 'post') {
                            unset($wp_taxonomies[$this->delete_taxonomies]->object_type[$i]);
                        }
                    }
                }
            }
        }
        //既存のタクソノミーのラベルを編集
        function edit_taxonomies(){
            global $wp_taxonomies;
            if(is_array($this->edit_taxonomies)){foreach($this->edit_taxonomies as $taxonomy_name => $taxonomy_label){
                if(!empty($wp_taxonomies[$taxonomy_name])){
                    $label = $wp_taxonomies[$taxonomy_name]->label;
                    foreach($wp_taxonomies[$taxonomy_name]->labels as $key => $value){
                        $wp_taxonomies[$taxonomy_name]->labels->$key = str_replace($label,$taxonomy_label,$value);
                    }
                }
            }}
        }
        //特定機能のサポートを削除
        function remove_post_type_support(){
            foreach($this->remove_post_type_support as $support){
                remove_post_type_support($this->post_type,$support);
            }
        }
    }

    /* View */
    class themePostView {
        function __construct($init = array()) {
            //初期設定
            foreach($init as $k => $v)$this->$k = $v;
            //プレースホルダー変更
            if(!empty($this->enter_title_here)){
                add_filter('enter_title_here',array($this,'enter_title_here'));
            }
            //カスタムメニューにcurrentを追加
            add_filter('nav_menu_css_class',array($this,'nav_menu_css_class'),10,2);
            //管理画面スタイル
            if(!empty($this->admin_print_styles)){
                add_action('admin_print_styles',array($this,'admin_print_styles'));
            }
            //管理画面スタイル or スクリプト
            add_action('admin_enqueue_scripts',array($this,'admin_enqueue_scripts'));
        }
        //プレースホルダー変更
        function enter_title_here($title){
            $screen = get_current_screen();
            if($this->post_type == $screen->post_type){
                $title = $this->enter_title_here;
            }
            return $title;
        }
        //カスタムメニューにcurrentを追加
        function nav_menu_css_class($classes,$item){
            if($item->url == get_post_type_archive_link($this->post_type) && get_query_var('post_type') == $this->post_type){
                $classes[] = 'current-menu-item';
                $classes[] = "current-menu-item-{$this->post_type}";
            }
            if(is_array($classes) && count($classes)){
                $classes = array_unique( $classes );
            }
            return $classes;
        }
        //スタイル
        function admin_print_styles(){
            //global $editor_styles;
            $stylesheet = false;
            $current_screen = get_current_screen();
            //test($current_screen);
            if($current_screen->post_type == $this->post_type){
                ?><style><?php echo $this->admin_print_styles; ?></style><?php
            }
        }
        function admin_enqueue_scripts(){
            $current_screen = get_current_screen();
            if($current_screen->post_type == $this->post_type){
                if(!empty($this->admin_enqueue_scripts)){
                    if(isset($this->admin_enqueue_scripts['css']) && is_array($this->admin_enqueue_scripts['css'])){
                        foreach($this->admin_enqueue_scripts['css'] as $k => $v){
                            wp_enqueue_style('themestyle-'.md5($v),$v);
                        }
                    }
                    if(isset($this->admin_enqueue_scripts['js']) && is_array($this->admin_enqueue_scripts['js'])){
                        foreach($this->admin_enqueue_scripts['js'] as $k => $v){
                            wp_enqueue_script('themestyle-'.md5($v),$v);
                        }
                    }
                }
            }
        }
    }

    /* Controller */
    class themePostController {
        function __construct($init = array()) {
            //予約語 ※使用禁止語
            $this->post_type_reserved_word = array(
                'post',//投稿
                'page',//固定ページ
                'attachment',//添付ファイル
                'revision',//リビジョン
                'nav_menu_item',//ナビゲーションメニュー
                'action',
                'order',
                'theme',
            );
            //初期設定
            foreach($init as $k => $v)$this->$k = $v;
            if(!empty($this->add_filter) && is_array($this->add_filter)){foreach($this->add_filter as $filter_key => $filter){
                add_filter($filter_key,$filter[0],$filter[1],$filter[2]);
            }}
            //Model
            $this->model = new themePostModel(get_object_vars($this));
            //View
            $this->view = new themePostView(get_object_vars($this->model));
        }
    }
}