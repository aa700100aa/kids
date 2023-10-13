<?php
if(!class_exists('SelectZIP')){
    class SelectZIP {
        function __construct($init = array()) {
            //初期設定
            foreach($init as $k => $v)$this->$k = $v;
            //フッターにスタイル等表示
            add_action('admin_footer',array($this,'admin_footer'),0);
        }
        function admin_footer() {
            wp_enqueue_media();
            wp_enqueue_script('colorbox',get_template_directory_uri().'/functions/class/SelectZIP/assets/js/colorbox-master/jquery.colorbox-min.js',array('jquery'),'1.6.4');
            wp_enqueue_style('colorbox',get_template_directory_uri().'/functions/class/SelectZIP/assets/js/colorbox-master/example4/colorbox.css');
            wp_enqueue_script('SelectZIP',get_template_directory_uri().'/functions/class/SelectZIP/assets/js/SelectZIP.js');
            wp_enqueue_style('SelectZIP',get_template_directory_uri().'/functions/class/SelectZIP/assets/css/SelectZIP.css');
        }
        function select_html($name="images[attachment_id][]",$attachment_id='',$size='full',$id=NULL){
            if(empty($name)){
                echo '<p class="error">ERROR： SelectZIP(17)</p>';
                return;
            }
            if($attachment_id && $src = wp_get_attachment_image_src($attachment_id,$size)){
                //$thumbnail = wp_get_attachment_image_src($attachment_id,'theme_admin_thumbnail');
                $thumbnail = wp_get_attachment_image_src($attachment_id,'medium');
                $img = '<a class="colorbox" href="#" data-href="'.$src[0].'"><span><span class="image" style="background-image:url('.$thumbnail[0].');"></span></span></a>';
            }else{
                $img = '';
            }
            ?><div class="upload_image">
                <span class="media button-secondary">ZIPファイルを選択</span>
                <div class="img_box"><div class="dummy"><?php echo $img; ?><a href="#" class="del"><span class="dashicons dashicons-no-alt"></span></a></div></div>
                <input class="img-id" name="<?php echo $name; ?>" type="hidden" value="<?php echo $attachment_id; ?>" <?php if($id): ?>id="<?php echo esc_attr($id); ?>"<?php endif; ?>>
            </div><?php
        }
    }
}