<?php
if(!class_exists('themeCustomSerchModel')){
    define('CSTABLE','custom_serch_table');
    define('CSTABLE_Ver','0.1');

    /* Model */
    class themeCustomSerchModel {
        function __construct($init = array()) {
            //$this->check_custom_serch_table(); 今後必要になるかも...
            $the_query = new WP_Query(array(
                'post_type' => 'smart-custom-fields',
                'name'      => 'カードローン情報',
            ));
            $this->CustomField = array();
            if($the_query->have_posts()){
                while($the_query->have_posts()){
                    $the_query->the_post();
                    $filds = get_post_meta(get_the_ID(),'smart-cf-setting',true);
                    foreach($filds as $k => $v){
                        foreach($v['fields'] as $kk => $vv){
                            if(!empty($vv['choices'])){
                                //改行コードを置換してLF改行コードに統一
                                $str = str_replace(array("\r\n","\r","\n"),"\n",$vv['choices']);
                                //LF改行コードで配列に格納
                                $vv['choices'] = explode("\n",$str);
                            }
                            $this->CustomField[$vv['name']] = $vv;
                        }
                    }
                }
                wp_reset_postdata();
            }
            //初期設定
            $this->serch_keys = array(
                'cs00' => '金融機関名',
                'cs01' => '最低年齢',
                'cs02' => '最高年齢',
                'cs03' => '職業',
                'cs04' => '居住地',
                'cs05' => '最低金利',
                'cs06' => '最大金利',
                'cs07' => '最低借入金額',
                'cs08' => '最大借入金額',
                'cs09' => '即日審査可',
                'cs10' => '即日借入可',
                'cs11' => '振込融資可',
                'cs12' => 'おまとめ・借換えにも対応',
                'cs13' => 'ネットバンキング利用可',
                'cs14' => '提携ATM',
            );
            $this->infeed_ad_posts();
            foreach($init as $k => $v)$this->$k = $v;
        }
        /* 今後必要になるかも...
        function check_custom_serch_table(){
            global $wpdb; //グローバル変数「$wpdb」を使うよっていう記述
            $this->table_name = $wpdb->prefix . CSTABLE;
            if(CSTABLE_Ver != get_option("CSTABLE_Ver")){
                $sql = "CREATE TABLE " . $this->table_name . " (
                    meta_id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                    post_id bigint(20) UNSIGNED DEFAULT '0' NOT NULL,
                    cs01 int(11) DEFAULT '0' NOT NULL,
                    cs02 int(11) DEFAULT '0' NOT NULL,
                    cs03 text DEFAULT '' NOT NULL,
                    cs04 text DEFAULT '' NOT NULL,
                    cs05 float DEFAULT '0' NOT NULL,
                    cs06 float DEFAULT '0' NOT NULL,
                    cs07 int(11) DEFAULT '0' NOT NULL,
                    cs08 int(11) DEFAULT '0' NOT NULL,
                    cs09 bit(1) DEFAULT '0' NOT NULL,
                    cs10 bit(1) DEFAULT '0' NOT NULL,
                    cs11 bit(1) DEFAULT '0' NOT NULL,
                    cs12 bit(1) DEFAULT '0' NOT NULL,
                    cs13 bit(1) DEFAULT '0' NOT NULL,
                    cs14 text DEFAULT '' NOT NULL,
                    UNIQUE KEY meta_id (meta_id)
                )
                CHARACTER SET 'utf8';";
                require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
                dbDelta($sql);
                update_option("CSTABLE_Ver",CSTABLE_Ver);
            }
        }*/
        function wp_query($query_args){
            $this->query_args = $query_args;
            unset($query_args['meta_query']);
            //test($this->query_args);
            add_filter('posts_join',array($this,'posts_join'));
            $the_query = (is_array($query_args) && count($query_args))?new WP_Query($query_args):new WP_Query();
            //$the_query->get_posts();
            remove_filter('posts_join',array($this,'posts_join'));
            return $the_query;
        }
        function posts_join($join){
            //test($this->query_args);
            if(!empty($this->query_args['meta_query']) && is_array($this->query_args['meta_query']) && count($this->query_args['meta_query'])){
                $join_sql = $this->join_sql($this->query_args['meta_query']);
                $join    .= $join_sql;
            }
            return $join;
        }
        function join_sql($query_args,$index = 0){
            global $wpdb;
            $meta_value  = 'CAST(meta_value AS '.($query_args[$index]['type']?$query_args[$index]['type']:'CHAR').') '.$query_args[$index]['compare'].' ';
            $meta_value .= is_array($query_args[$index]['value'])?"('".implode("','",$query_args[$index]['value'])."')":"'{$query_args[$index]['value']}'";
            $join = $wpdb->prepare('
    INNER JOIN (
        SELECT DISTINCT cs'.$index.'.post_id
        FROM '.$wpdb->postmeta.' AS cs'.$index.'
        '.(isset($query_args[($index + 1)])?$this->join_sql($query_args,($index + 1)):'').'
        WHERE meta_key = %s
          AND '.$meta_value.'
    ) AS mt'.$index.' ON ( '.($index?'cs'.($index - 1).'.post_id':$wpdb->posts.'.ID').' = mt'.$index.'.post_id )',
                $query_args[$index]['key']
            );
            return $join;
        }
        function infeed_ad_posts(){
            global $themeInfeedAd;
            $this->infeed_ad_posts = get_option('infeed_ad_posts',1);
            $this->posts_per_page  = get_option('posts_per_page',10);
            $this->infeed          = new WP_Query(array(
                'post_type' => $themeInfeedAd->post_type,
                'nopaging'  => true,
                'fields'    => 'ids',
            ));
            if($this->infeed->found_posts > $this->infeed_ad_posts && is_array($this->infeed->posts) && count($this->infeed->posts) > 1){
                shuffle($this->infeed->posts);
            }
        }
        function check_infeed($index){
            //if(floor($this->posts_per_page / ($this->infeed_ad_posts + 1) - 1) == $index){
            if(empty($index))return NULL;
            if(!(($index) % floor($this->posts_per_page / ($this->infeed_ad_posts + 1)))){
                $infeed_id = array_shift($this->infeed->posts);
                $this->infeed->posts[] = $infeed_id;
                return $infeed_id;
            }else{
                return NULL;
            }
        }
    }
    /* View */
    class themeCustomSerchView {
        function __construct($init = array()) {
            //初期設定
            foreach($init as $k => $v)$this->$k = $v;
        }
        function checked($target,$search){
            echo (($search == $this->default_values[$target] || (is_array($this->default_values[$target]) && in_array($search,$this->default_values[$target])))?'checked="checked"':'');
        }
        function selected($target,$search){
            echo (($search == $this->default_values[$target])?'selected="selected"':'');
        }
        function input_text_value($target){
            echo $this->default_values[$target];
        }
        function form(){
            wp_enqueue_style('custom_serch',get_template_directory_uri().'/functions/custom_serch/assets/css/custom_serch.css');
            if(is_admin()){
                wp_enqueue_style('custom_serch_admin',get_template_directory_uri().'/functions/custom_serch/assets/css/admin.css');
            }
            require(dirname(__FILE__).'/views/form.php');
            return $this->form_buffer;
        }
    }
    /* Controller */
    class themeCustomSerchController {
        function __construct($init = array()) {
            //初期設定
            foreach($init as $k => $v)$this->$k = $v;
            //Model
            $this->model = new themeCustomSerchModel(get_object_vars($this));
            if(isset($_GET['custom_serch'])){foreach($this->model->serch_keys as $k => $v){
                if(is_array($_GET[$k])){
                    $this->default_values[$k] = filter_input(INPUT_GET,$k,FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
                }else{
                    $this->default_values[$k] = filter_input(INPUT_GET,$k);
                }
            }}
            $this->model->default_values = $this->default_values;
            //View
            $this->view = new themeCustomSerchView(get_object_vars($this->model));
            //custom_serch
            $this->the_serch_values();
        }
        function get_the_form(){
            return $this->view->form();
        }
        function the_form(){
            echo $this->view->form();
        }
        function get_default_values(){
            return $this->default_values;
        }
        function get_serch_keys(){
            return $this->model->serch_keys;
        }
        function get_infeed_ad_posts(){
            return $this->model->infeed_ad_posts;
        }
        function the_serch_values(){
            $default_values     = $this->get_default_values();
            $serch_keys         = $this->get_serch_keys();
            if(is_home()){
                $paged = get_query_var('page') ? get_query_var('page') : 1;
            }else{
                $paged = get_query_var('paged') ? get_query_var('paged') : 1;
            }
            $this->query_args   = array(
                'post_type'      => 'post',
                'paged'          => $paged,
                'posts_per_page' => get_option('posts_per_page',10),
            );
            $this->serch_values = array();
            if(isset($_GET['custom_serch']) && !isset($default_values['cs00'])){
                //年齢
                if(!empty($default_values['cs01'])){
                    if(!isset($this->query_args['meta_query']))$this->query_args['meta_query'] = array();
                    $this->query_args['meta_query'][] = array(
                        'key'     => $serch_keys['cs01'],
                        'value'   => $default_values['cs01'],
                        'type'    => 'UNSIGNED',
                        'compare' => '<=',
                    );
                    $this->query_args['meta_query'][] = array(
                        'key'     => $serch_keys['cs02'],
                        'value'   => (int)$default_values['cs01'],
                        'type'    => 'UNSIGNED',
                        'compare' => '>=',
                    );
                    $this->serch_values[] = "{$default_values['cs01']}歳";
                }
                //職業
                if(!empty($default_values['cs03'])){
                    if(!isset($this->query_args['meta_query']))$this->query_args['meta_query'] = array();
                    $this->query_args['meta_query'][] = array(
                        'key'     => $serch_keys['cs03'],
                        'value'   => $default_values['cs03'],
                        'type'    => 'CHAR',
                        'compare' => '=',
                    );
                    $this->serch_values[] = "{$default_values['cs03']}";
                }
                //居住地
                if(!empty($default_values['cs04'])){
                    if(!isset($this->query_args['meta_query']))$this->query_args['meta_query'] = array();
                    if(!isset($this->serch_values))$this->serch_values = array();
                    $this->query_args['meta_query'][] = array(
                        'key'     => $serch_keys['cs04'],
                        'value'   => $default_values['cs04'],
                        'type'    => 'CHAR',
                        'compare' => '=',
                    );
                    $this->serch_values[] = "{$default_values['cs04']}";
                }
                //借入金利
                if(!empty($default_values['cs06'])){
                    if(!isset($this->query_args['meta_query']))$this->query_args['meta_query'] = array();
                    if((int)$default_values['cs06'] < 0){
                        $this->query_args['meta_query'][] = array(
                            'key'     => $serch_keys['cs05'],
                            'value'   => abs(floatval($default_values['cs06'])),
                            'type'    => 'DECIMAL',
                            'compare' => '<=',
                        );
                        $this->serch_values[] = "最低金利が".abs(floatval($default_values['cs06']))."％以下";
                    }else{
                        $this->query_args['meta_query'][] = array(
                            'key'     => $serch_keys['cs06'],
                            'value'   => floatval($default_values['cs06']),
                            'type'    => 'DECIMAL',
                            'compare' => '<=',
                        );
                        $this->serch_values[] = "最大金利が".floatval($default_values['cs06'])."％以下";
                    }
                }
                //最大借入金額
                if(!empty($default_values['cs08'])){
                    if(!isset($this->query_args['meta_query']))$this->query_args['meta_query'] = array();
                    $this->query_args['meta_query'][] = array(
                        'key'     => $serch_keys['cs07'],
                        'value'   => $default_values['cs08'],
                        'type'    => 'UNSIGNED',
                        'compare' => '<=',
                    );
                    $this->query_args['meta_query'][] = array(
                        'key'     => $serch_keys['cs08'],
                        'value'   => $default_values['cs08'],
                        'type'    => 'UNSIGNED',
                        'compare' => '>=',
                    );
                    $this->serch_values[] = number_format($default_values['cs08'])."万円以内";
                }
                //即日審査可
                if(!empty($default_values['cs09'])){
                    if(!isset($this->query_args['meta_query']))$this->query_args['meta_query'] = array();
                    $this->query_args['meta_query'][] = array(
                        'key'     => $serch_keys['cs09'],
                        'value'   => $default_values['cs09'],
                        'type'    => 'UNSIGNED',
                        'compare' => '=',
                    );
                    $this->serch_values[] = '即日審査可';
                }
                //即日借入可
                if(!empty($default_values['cs10'])){
                    if(!isset($this->query_args['meta_query']))$this->query_args['meta_query'] = array();
                    $this->query_args['meta_query'][] = array(
                        'key'     => $serch_keys['cs10'],
                        'value'   => $default_values['cs10'],
                        'type'    => 'UNSIGNED',
                        'compare' => '=',
                    );
                    $this->serch_values[] = '即日借入可';
                }
                //振込融資可
                if(!empty($default_values['cs11'])){
                    if(!isset($this->query_args['meta_query']))$this->query_args['meta_query'] = array();
                    $this->query_args['meta_query'][] = array(
                        'key'     => $serch_keys['cs11'],
                        'value'   => $default_values['cs11'],
                        'type'    => 'UNSIGNED',
                        'compare' => '=',
                    );
                    $this->serch_values[] = '振込融資可';
                }
                //おまとめ・借換えにも対応
                if(!empty($default_values['cs12'])){
                    if(!isset($this->query_args['meta_query']))$this->query_args['meta_query'] = array();
                    $this->query_args['meta_query'][] = array(
                        'key'     => $serch_keys['cs12'],
                        'value'   => $default_values['cs12'],
                        'type'    => 'UNSIGNED',
                        'compare' => '=',
                    );
                    $this->serch_values[] = 'おまとめ・借換えにも対応';
                }
                //ネットバンキング利用可
                if(!empty($default_values['cs13'])){
                    if(!isset($this->query_args['meta_query']))$this->query_args['meta_query'] = array();
                    $this->query_args['meta_query'][] = array(
                        'key'     => $serch_keys['cs13'],
                        'value'   => $default_values['cs13'],
                        'type'    => 'UNSIGNED',
                        'compare' => '=',
                    );
                    $this->serch_values[] = 'ネットバンキング利用可';
                }
                //提携ATM
                if(!empty($default_values['cs14']) && is_array($default_values['cs14']) && count($default_values['cs14'])){
                    if(!isset($this->query_args['meta_query']))$this->query_args['meta_query'] = array();
                    $this->query_args['meta_query'][] = array(
                        'key'     => $serch_keys['cs14'],
                        'value'   => $default_values['cs14'],
                        'type'    => 'CHAR',
                        'compare' => 'IN',
                    );
                    $this->serch_values[] = implode('／',$default_values['cs14']);
                }
            }elseif(!empty($default_values['cs00'])){
                //金融機関名
                if(!isset($this->query_args['meta_query']))$this->query_args['meta_query'] = array();
                $this->query_args['meta_query'][] = array(
                    'key'     => $serch_keys['cs00'],
                    'value'   => $default_values['cs00'],
                    'type'    => 'CHAR',
                    'compare' => '=',
                );
                $this->serch_values[] = $default_values['cs00'];
            }
            $this->serch_values = implode('／',$this->serch_values);
        }
        function get_the_serch_values(){
            return $this->serch_values;
        }
        function check_infeed($index){
            return $this->model->check_infeed($index);
        }
        function wp_query(){
            //test($this->model->join_sql($this->query_args['meta_query']));exit;
            $this->the_query = $this->model->wp_query($this->query_args);
            return $this->the_query;
        }
    }
}