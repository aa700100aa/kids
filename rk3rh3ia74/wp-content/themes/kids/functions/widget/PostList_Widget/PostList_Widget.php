<?php
class PostList_Widget extends WP_Widget{
    /**
     * Widgetを登録する
     */
    function __construct() {
        parent::__construct(
            'post_list_widget', // Base ID
            '投稿一覧', // Name
            array( // Args
                'classname'   => 'post_list_widget',
                'description' => '選択した投稿タイプの投稿一覧を表示する',
            )
        );
    }
 
    /**
     * 表側の Widget を出力する
     *
     * @param array $args      'register_sidebar'で設定した「before_title, after_title, before_widget, after_widget」が入る
     * @param array $instance  Widgetの設定項目
     */
    public function widget( $args, $instance ) {
        global $post;
        $get_post_types       = array('cause','flow','exp','qa',);
        $p_classes            = array('close');
        $classes              = array();
        $post_parent          = '';
        if(is_single() && !empty($post->post_parent) && in_array(get_post_type($post->post_parent),array($instance['post_type']))){
            $p_classes = array();
            $post_parent = $post->post_parent;
        }elseif(is_single() && in_array($post->post_type,array($instance['post_type']))){
            $p_classes = array();
            $post_parent = $post->ID;
        }elseif(is_archive() && get_query_var('post_type') == $instance['post_type']){
            $p_classes = array();
        }
        //test($args);
        $the_query = new WP_Query(array(
            'post_type'    => $instance['post_type'],
            'orderby'      => 'menu_order',
            'order'        => 'ASC',
            'nopaging'     => true,
            'post__not_in' => $instance['post__not_in'],
        ));
        if(!$the_query->have_posts())return;
        echo $args['before_widget']; ?><div class="widget_nav_menu"><ul class="menu">
            <li class="accordion <?php echo implode(' ',$p_classes); ?>">
                <span><span><?php if($instance['title']){
                    echo $instance['title'];
                }else{
                     $obj = get_post_type_object($instance['post_type']);
                     echo $obj->labels->name;
                } ?></span></span>
                <ul class="sub-menu"><?php while($the_query->have_posts()){$the_query->the_post(); ?><li>
                    <a href="<?php the_permalink(); ?>"><span><?php the_title_attribute(); ?></span></a>
                    <?php if($post_parent == get_the_ID() && $instance['children'] == 1){ ?>
                        <?php
                            $posts = array();
                            foreach($get_post_types as $now_get_post_type){
                                $get_posts = get_posts(array(
                                    'posts_per_page'   => 1,
                                    'post_type'        => $now_get_post_type,
                                    'post_parent'      => get_the_ID(),
                                ));
                                //test($get_posts);
                                if(is_array($get_posts) && count($get_posts)){
                                    $obj = get_post_type_object($now_get_post_type);
                                    $posts[] = array(
                                        'ID'         => $get_posts[0]->ID,
                                        'post_title' => $obj->label,
                                    );
                                }
                            }
                            //test($get_posts);
                            if(is_array($posts) && count($posts)){
                                ?><ul class="sub-sub-menu"><?php
                                foreach($posts as $now_post){
                                    
                                    ?><li><a href="<?php echo get_the_permalink($now_post['ID']); ?>"><span><?php echo $now_post['post_title']; ?></span></a></li><?php
                                }
                                ?></ul><?php
                            }
                        ?>
                    <?php } ?>
                </li><?php }wp_reset_postdata(); ?></ul>
            </li>
        </ul></div><?php
        echo $args['after_widget'];
        /*$title = $instance['title'];
        echo $args['before_widget'];
 
        echo "<p>Email: ${title}</p>";
 
        echo $args['after_widget'];*/
    }
 
    /** Widget管理画面を出力する
     *
     * @param array $instance 設定項目
     * @return string|void
     */
    public function form( $instance ){
        $value = $instance['title'];
        $name  = $this->get_field_name('title');
        $id    = $this->get_field_id('title');
        ?>
        <p>
            <label for="<?php echo $id; ?>">タイトル:</label>
            <input class="widefat" id="<?php echo $id; ?>" name="<?php echo $name; ?>" type="text" value="<?php echo esc_attr( $value ); ?>">
        </p><?php
        $value      = $instance['post_type'];
        $name       = $this->get_field_name('post_type');
        $id         = $this->get_field_id('post_type');
        $post_types = get_post_types(array(
            'public'   => true,
            '_builtin' => false
        ),'objects');
        //test($post_types);
        ?><p>
            <label for="<?php echo $id; ?>">投稿タイプ:</label>
            <select class="widefat" id="<?php echo $id; ?>" name="<?php echo $name; ?>">
                <?php foreach($post_types as $name => $post_type){ ?><option value="<?php echo $name; ?>" <?php selected($name,$value); ?>>
                    <?php echo $post_type->labels->name; ?>
                </option><?php } ?>
            </select>
        </p><?php
        $value      = $instance['children'];
        $name       = $this->get_field_name('children');
        $id         = $this->get_field_id('children');
        //test($instance);
        ?><p>
            <input type="hidden" name="<?php echo $name; ?>" value="0">
            <label>子ページ表示:<br>
                <input type="checkbox" name="<?php echo $name; ?>" value="1" <?php checked(1,$value); ?>> 子ページ表示する
            </label>
        </p><?php
        $value      = $instance['post__not_in'];
        $name       = $this->get_field_name('post__not_in');
        $id         = $this->get_field_id('post__not_in');
        ?><p>
            <label for="<?php echo $id; ?>">除外ページ:</label>
            <input class="widefat" id="<?php echo $id; ?>" name="<?php echo $name; ?>" type="text" value="<?php echo esc_attr( $value ); ?>">
            <br>
            <small>ページ ID を入力。複数の場合はコンマで区切る。</small>
        </p><?php
        }
 
    /** 新しい設定データが適切なデータかどうかをチェックする。
     * 必ず$instanceを返す。さもなければ設定データは保存（更新）されない。
     *
     * @param array $new_instance  form()から入力された新しい設定データ
     * @param array $old_instance  前回の設定データ
     * @return array               保存（更新）する設定データ。falseを返すと更新しない。
     */
    function update($new_instance, $old_instance) {
        $instance                 = $old_instance;
        $instance['title']        = esc_html($new_instance['title']);
        $instance['post_type']    = $new_instance['post_type'];
        $instance['post__not_in'] = $new_instance['post__not_in'];
        $instance['children']     = $new_instance['children'];
        return $instance;
    }
}

add_action('widgets_init',function(){
    register_widget('PostList_Widget');  //WidgetをWordPressに登録する
});