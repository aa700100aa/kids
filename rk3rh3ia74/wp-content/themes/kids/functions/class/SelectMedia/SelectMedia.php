<?php
if(!class_exists('SelectMedia')){
    class SelectMedia {
        function __construct($init = array()) {
            //初期設定
            foreach($init as $k => $v)$this->$k = $v;
            //フッターにスタイル等表示
            add_action('admin_footer',array('SelectMedia','admin_footer'));
        }
        static function admin_footer(){
            ?><div id="SelectMedia-media-colorbox-content" style="display:none;">
                <div id="videoInlineContent" class="video inline_content" style="width: 100%;height: 100%;">
                    <video id="videoTag" src="" style="width: 100%;height: auto;display: block;" muted controls preload autoplay>
                        <p>ご利用のブラウザはvideo タグによる動画の再生に対応していません。</p>
                    </video>
                    <div style="display:none;">
                        <canvas id="videoCanvas"></canvas>
                        <div id="videoThumnail"></div>
                    </div>
                </div>
                <div id="audioInlineContent" class="audio inline_content" style="width: 100%;height: 100%;">
                    <audio id="audioTag" src="" style="width: 100%;height: auto;display: block;" controls preload autoplay>
                        <p>ご利用のブラウザはaudio タグによる動画の再生に対応していません。</p>
                    </audio>
                </div>
            </div><?php
            wp_enqueue_media();
            wp_enqueue_script('colorbox',get_stylesheet_directory_uri().'/functions/class/SelectMedia/assets/js/colorbox-master/jquery.colorbox-min.js',array('jquery'),'1.6.4');
            wp_enqueue_style('colorbox',get_stylesheet_directory_uri().'/functions/class/SelectMedia/assets/js/colorbox-master/example4/colorbox.css');
            if(!extension_loaded('imagick')){
                wp_enqueue_script('b64',get_stylesheet_directory_uri().'/functions/class/SelectMedia/assets/js/jsgif-master/b64.js');
                wp_enqueue_script('LZWEncoder',get_stylesheet_directory_uri().'/functions/class/SelectMedia/assets/js/jsgif-master/LZWEncoder.js');
                wp_enqueue_script('NeuQuant',get_stylesheet_directory_uri().'/functions/class/SelectMedia/assets/js/jsgif-master/NeuQuant.js');
                wp_enqueue_script('GIFEncoder',get_stylesheet_directory_uri().'/functions/class/SelectMedia/assets/js/jsgif-master/GIFEncoder.js');
                wp_enqueue_script('SelectMediaEncode',get_stylesheet_directory_uri().'/functions/class/SelectMedia/assets/js/SelectMediaEncode.js');
            }
            wp_enqueue_script('SelectMedia',get_stylesheet_directory_uri().'/functions/class/SelectMedia/assets/js/SelectMedia.js');
            wp_localize_script(
                'SelectMedia',
                '$SelectMediaData', 
                array(
                    'get_wp_mime_type_icon' => array(
                        'endpoint' => admin_url('admin-ajax.php'),
                        'action'   => 'get_wp_mime_type_icon',
                        'secure'   => wp_create_nonce('get_wp_mime_type_icon'),
                    ),
                    'get_post_thumbnail_id' => array(
                        'endpoint' => admin_url('admin-ajax.php'),
                        'action'   => 'get_post_thumbnail_id',
                        'secure'   => wp_create_nonce('get_post_thumbnail_id'),
                    ),
                    'videoThumnailUpload' => array(
                        'endpoint' => admin_url('admin-ajax.php'),
                        'action'   => 'videoThumnailUpload',
                        'secure'   => wp_create_nonce('videoThumnailUpload'),
                    ),
                )
            );
            wp_enqueue_style('SelectMedia',get_stylesheet_directory_uri().'/functions/class/SelectMedia/assets/css/SelectMedia.css');
        }
        static function select_html($name="images[attachment_id][]",$attachment_id='',$size='full',$id=NULL){
            add_action('admin_footer',array('SelectMedia','admin_footer'),0);
            if(empty($name)){
                echo '<p class="error">ERROR： SelectMedia(17)</p>';
                return;
            }
            //$attachment_metadata = wp_get_attachment_metadata($attachment_id);
            //test($attachment_metadata);
            //test($src = wp_get_attachment_url( $attachment_id ));
            if(!empty($attachment_id) && $src = wp_get_attachment_url( $attachment_id )){
                switch(SelectMedia::get_mime_type_for_attachment($attachment_id)){
                    case 'image':
                        $thumbnail = wp_get_attachment_image_src($attachment_id,'medium');
                        $img = '<a class="colorbox" href="#" data-href="'.$src.'"><span><span class="image" style="background-image:url('.$thumbnail[0].');"></span></span></a>';
                        break;
                    default:
                        if($thumbnail_id = get_post_meta($attachment_id,'_thumbnail_id',true)){
                            $thumbnail = wp_get_attachment_image_src($thumbnail_id,'medium');
                            $thumbnail = $thumbnail[0];
                            if(empty($thumbnail)){
                                $thumbnail = wp_mime_type_icon(get_post_mime_type($attachment_id));
                            }
                        }else{
                            $thumbnail = wp_mime_type_icon(get_post_mime_type($attachment_id));
                        }
                        $img = '<a class="colorbox" href="#" data-href="'.$src.'"><span><span class="image" style="background-image:url('.$thumbnail.');"></span></span></a>';
                        break;
                }
            }else{
                $img = '';
            }
            ?><div class="upload_image">
                <span class="media button-secondary">メディアを選択</span>
                <div class="img_box"><div class="dummy"><?php echo $img; ?><a href="#" class="del"><span class="dashicons dashicons-no-alt"></span></a></div></div>
                <input class="img-id" name="<?php echo $name; ?>" type="hidden" value="<?php echo $attachment_id; ?>" <?php if($id): ?>id="<?php echo esc_attr($id); ?>"<?php endif; ?>>
            </div><?php
        }
        static function get_mime_type_for_attachment( $post_id ) {
            $type = get_post_mime_type( $post_id );
            switch ( $type ) {
                case 'image/jpeg':
                case 'image/jpg':
                case 'image/png':
                case 'image/gif':
                    return 'image'; break;
                case 'video/mpeg':
                case 'video/mp4': 
                case 'video/quicktime':
                    return 'video'; break;
                case 'text/csv':
                    return 'csv'; break;
                case 'text/plain': 
                    return 'txt'; break;
                case 'audio/mp3':
                case 'audio/mpeg':
                case 'audio/x-realaudio':
                case 'audio/wav':
                case 'audio/ogg':
                case 'audio/midi':
                case 'audio/x-ms-wma':
                case 'audio/x-ms-wax':
                case 'audio/x-matroska':
                    return 'audio'; break;
                case 'text/xml':
                    return 'xml'; break;
                default:
                    return '?'; break;
            }
        }
    }
}
//_wp_attachment_metadata
add_filter('wp_update_attachment_metadata',function($data,$attachment_id){
    if(SelectMedia::get_mime_type_for_attachment($attachment_id) == 'video'){
        $file = get_attached_file($attachment_id);
        if(!class_exists('getID3',false)){
            require(ABSPATH.WPINC.'/ID3/getid3.php');
            require(ABSPATH.WPINC.'/ID3/getid3.lib.php');
        }
        if(!file_exists($file)){
            return $data;
        }
        $id3  = new getID3();
        $info = $id3->analyze($file);
        getid3_lib::CopyTagsToComments($info);
        if(isset($info['video']['fourcc'])){
             $data['fourcc'] = $info['video']['fourcc'];
        }
        if(isset($info[$info['video']['dataformat']])){
            $dataformat = $info[$info['video']['dataformat']];
            if(isset($dataformat['comments'])){
                $data[$info['video']['dataformat']]['comments'] = $dataformat['comments'];
            }
            if(isset($dataformat['video'])){
                $data[$info['video']['dataformat']]['video'] = $dataformat['video'];
            }
            if(isset($dataformat['audio'])){
                $data[$info['video']['dataformat']]['audio'] = $dataformat['audio'];
            }
        }
        if(isset($info['playtime_seconds'])){
             $data['playtime_seconds'] = ceil($info['playtime_seconds']);//四捨五入
        }
        if(isset($data['filesize'])){
             $data['filesize_formatted'] = byte_format($info['filesize'],2,true);
        }
        //test($info);
    }
    return $data;
},10,2);
//[PHP]ファイル容量（Byte）をメガバイト(MB)、ギガバイト(GB)などの単位で表す
if(!function_exists('byte_format')){
    function byte_format($size, $dec=-1, $separate=false){
        $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
        $digits = ($size == 0) ? 0 : floor( log($size, 1024) );
         
        $over = false;
        $max_digit = count($units) -1 ;
     
        if($digits == 0){
            $num = $size;
        } else if(!isset($units[$digits])) {
            $num = $size / (pow(1024, $max_digit));
            $over = true;
        } else {
            $num = $size / (pow(1024, $digits));
        }
         
        if($dec > -1 && $digits > 0) $num = sprintf("%.{$dec}f", $num);
        if($separate && $digits > 0) $num = number_format($num, $dec);
         
        return ($over) ? $num . $units[$max_digit] : $num . $units[$digits];
    }
}
//Ajax
add_action('wp_ajax_get_wp_mime_type_icon',function(){
    check_ajax_referer('get_wp_mime_type_icon','secure');
    if($_POST['attachment_id']){
        $attachment_id = $_POST['attachment_id'];
        if(!empty($attachment_id) && $src = wp_get_attachment_url( $attachment_id )){
            switch(SelectMedia::get_mime_type_for_attachment($attachment_id)){
                case 'image':
                    $thumbnail = wp_get_attachment_image_src($attachment_id,'medium');
                    $thumbnail = $thumbnail[0];
                    break;
                default:
                    if($thumbnail_id = get_post_meta($attachment_id,'_thumbnail_id',true)){
                        $thumbnail = wp_get_attachment_image_src($thumbnail_id,'medium');
                        $thumbnail = $thumbnail[0];
                        if(empty($thumbnail)){
                            $thumbnail = wp_mime_type_icon(get_post_mime_type($attachment_id));
                        }
                    }else{
                        $thumbnail = wp_mime_type_icon(get_post_mime_type($attachment_id));
                    }
                    break;
            }
        }else{
            $thumbnail = '';
        }
    }
    echo $thumbnail;
    die();
});
add_action('wp_ajax_get_post_thumbnail_id',function(){
    check_ajax_referer('get_post_thumbnail_id','secure');
    if($_POST['post_id']){
        $post_id = $_POST['post_id'];
        if(!empty($post_id) && $thumbnail_id = get_post_thumbnail_id( $post_id )){
        }else{
            $thumbnail_id = '';
        }
    }
    echo $thumbnail_id;
    die();
});
add_action('wp_ajax_videoThumnailUpload',function(){
    check_ajax_referer('videoThumnailUpload','secure');
    if($_FILES && $_FILES['image']['size'] > 0){
        $attach_id = media_handle_upload('image',0);
        if($attach_id){
            $return      = $attach_id;
            $attach_data = wp_generate_attachment_metadata($attach_id,$fName);
            wp_update_attachment_metadata($attach_id,$attach_data);
            if($post = get_post($_POST['post_id'])){
                set_post_thumbnail($post,$attach_id);
            }
        }
    }
    /*if(isset($_POST['image'])){
        $image = filter_input(INPUT_POST,'image');
        $image = str_replace('data:image/gif;base64,','',$image);
        $image = str_replace(' ', '+', $image);
        $image = base64_decode($image);

        $fp = tmpfile();
        fwrite($fp, $image);
        fwrite($fp,base64_decode($imageData));
        fflush($fp);
        $fmeta       = stream_get_meta_data($fp);
        $fName       = "{$fmeta['uri']}.gif";
        $wp_filetype = wp_check_filetype($fName);

        $wp_upload_dir = wp_upload_dir();
        $attachment = array(
            'guid'           => $wp_upload_dir['url'] . '/' . basename($fName), 
            'post_mime_type' => $wp_filetype['type'],
            'post_title'     => preg_replace('/\.[^.]+$/', '', basename($fName)),
            'post_content'   => '',
            'post_status'    => 'inherit',
        );
        $attach_id = wp_insert_attachment($attachment,$fName);
        $attach_data = wp_generate_attachment_metadata($attach_id,$fName);
        wp_update_attachment_metadata($attach_id,$attach_data);
        if($post = get_post($_POST['post_id'])){
            set_post_thumbnail($post,$attach_id);
        }

        //fclose($fp);
        $return = true;
    }else{
        $return = false;
    }*/
    echo $return;
    die();
});




































