<?php
if(!class_exists('GoogleMapsModel')){
    //GoogleMapsMVC 定数
    //test(json_encode(array('zoom'=>16,'map-lat'=>35.681281,'map-lng'=>139.766397,'icon-lat'=>35.681281,'icon-lng'=>139.766397,'icon'=>'','info'=>'','style'=>'')));
    define('GoogleMapsMVC_DefaultOption' , '{"zoom":17,"map-lat":35.681281,"map-lng":139.766397,"icon-lat":35.681281,"icon-lng":139.766397,"icon":"","info":"","style":""}');
    define('GoogleMapsMVC_OptionKey'     , 'google-maps-data');
    define('GoogleMapsMVC_PostKey'       , 'GoogleMaps');

    /* Model */
    class GoogleMapsModel {
        function __construct($init = array()){
            //デフォルト値
            $this->default_option = json_decode(GoogleMapsMVC_DefaultOption,true);
            $this->option_key     = GoogleMapsMVC_OptionKey;
            foreach($init as $k => $v)$this->$k = $v;
            //Google Map スタイル
            $this->style_url   = get_stylesheet_directory_uri().'/functions/class/GoogleMap/assets/json/';
            $this->style_dir   = dirname(__FILE__).'/assets/json/';
            $this->style_files = array();
            // ディレクトリの存在を確認し、ハンドルを取得
            if(is_dir($this->style_dir) && $handle = opendir($this->style_dir)){
                //ループ処理
                while(($file = readdir($handle)) !== false){
                    // ファイルのみ取得
                    if(filetype($path = $this->style_dir.$file) == "file"){
                        $this->style_files[] = array(
                            'dir'  => $path,
                            'url'  => "{$this->style_url}{$file}",
                            'file' => $file,
                            'name' => basename($file,'.json'),
                        );
                    }
                }
            }
            //バリデーション情報
            $this->option_info = array(
                'zoom' => array(
                    'numerical' => array(
                        0,22
                    ),
                ),
                'map-lat' => array(
                    'numerical',
                ),
                'map-lng' => array(
                    'numerical',
                ),
                'icon-lat' => array(
                    'numerical',
                ),
                'icon-lng' => array(
                    'numerical',
                ),
                'icon' => array(
                    'numerical',
                ),
                'info' => array(
                    'html',//wp_kses_post
                ),
                'style' => array(
                    'select' => $this->style_files,
                ),
            );
            //保存情報取得
            $this->set(get_option(GoogleMapsMVC_OptionKey,array()));
        }

        //スタイル情報取得
        function get_styles(){
            return $this->style_files;
        }

        //バリデーション情報取得
        function get_option_info(){
            return $this->option_info;
        }

        /*********************************
         * Google Map 値設定
         * 
         * $atts - 属性の連想配列
         *  ┣ zoom     - 拡大具合
         *  ┣ map-lat  - 地図緯度
         *  ┣ map-lng  - 地図経度
         *  ┣ icon-lat - マーカー緯度
         *  ┣ icon-lng - マーカー経度
         *  ┣ icon     - マーカー(marker)
         *  ┣ info     - 吹き出しテキスト
         *  ┗ style    - スタイル（※01）
         * ※01 - 「https://snazzymaps.com/」などからスタイル情報を取得するか、「https://ezmap.co/」で作成するかなどなど
        *********************************/
        function set($atts=array()){
            if(!is_array($atts))$atts = array();
            $this->atts = array_merge($this->default_option,$atts);
            //test($atts);
            $this->atts = $this->get();
        }

        /*********************************
         * Google Map 保存
        *********************************/
        function update(){
            update_option(GoogleMapsMVC_OptionKey,$this->get());
        }

        /*********************************
         * Google Map 値取得
        *********************************/
        function get(){
            $return = array();
            if(!is_array($this->atts))$this->atts = array();
            foreach($this->atts as $k => $v){
                if(in_array($k,array_keys($this->option_info))){
                    $return[$k] = $v;
                }
            }
            return $return;
        }
    }

    /* View */
    class GoogleMapsView {
        function __construct($init = array()){
            //デフォルト値
            $this->default_option = json_decode(GoogleMapsMVC_DefaultOption,true);
            $this->post_key       = GoogleMapsMVC_PostKey;
            $this->uniqid         = md5(uniqid(rand(),1));
            foreach($init as $k => $v)$this->$k = $v;
            //フッターにスタイル等表示
            add_action('admin_footer',array($this,'admin_footer'),0);
            add_action('wp_footer',array($this,'wp_footer'),0);
        }
        //スクリプト・スタイル追加
        function admin_footer(){
            wp_enqueue_style("{$this->uniqid}-GoogleMapsMVC",get_stylesheet_directory_uri().'/functions/class/GoogleMap/assets/css/admin.css');
            wp_enqueue_script("{$this->uniqid}-GoogleMapsMVC",get_stylesheet_directory_uri().'/functions/class/GoogleMap/assets/js/admin.js',array('jquery'),'');
            wp_localize_script("{$this->uniqid}-GoogleMapsMVC",'$GoogleMapsMVC_data',array(
                'id' => $this->uniqid,
            ));
        }
        function wp_footer(){
            wp_enqueue_style("{$this->uniqid}-GoogleMapsMVC",get_stylesheet_directory_uri().'/functions/class/GoogleMap/assets/css/front.css');
        }
        //スタイルデータをセットする
        function set_styles($styles = array()){
            $this->style_files = $styles;
        }
        /*********************************
         * Google Map admin html
         * 
         * $atts - 属性の連想配列
         *  ┣ zoom    - 拡大具合
         *  ┣ map-lat  - 地図緯度
         *  ┣ map-lng  - 地図経度
         *  ┣ icon-lat - マーカー緯度
         *  ┣ icon-lng - マーカー経度
         *  ┣ icon    - マーカー(marker)
         *  ┣ info    - 吹き出しテキスト
         *  ┗ style   - スタイル（※01）
         * ※01 - 「https://snazzymaps.com/」などからスタイル情報を取得するか、「https://ezmap.co/」で作成するかなどなど
        *********************************/
        function admin_html($atts=array()){
            $SelectImage = new SelectImage();
            $this->atts = array_merge($this->default_option,$atts);
            ob_start();
            wp_nonce_field(wp_create_nonce(md5(__FILE__)),md5(__FILE__));
            ?><div class="GoogleMapsView">
                <div class="row row01 google-maps">
                    <div id="<?php echo esc_attr($this->uniqid); ?>" class="google-maps-iframe"></div>
                </div>
                <div class="row row02 load-button">
                    <?php if(empty($atts['address-element'])): ?>
                        <input id="<?php echo esc_attr($this->uniqid); ?>-address" class="large-text" type="text" style="margin-bottom: 1em;" placeholder="例）東京都千代田区丸の内1-9-1">
                    <?php else: ?>
                        <input id="<?php echo esc_attr($this->uniqid); ?>-address-element" type="hidden" value="<?php echo esc_attr($atts['address-element']); ?>">
                    <?php endif; ?>
                    <span id="<?php echo esc_attr($this->uniqid); ?>-address-load-button" class="button">住所情報から読み込み</span>
                </div>
                <div class="row row03 zoom">
                    <div class="colbox">
                        <div class="col col01" data-label="ズームレベル">
                            <select id="<?php echo esc_attr($this->uniqid); ?>-zoom" name="<?php echo $this->post_key; ?>[zoom]" class="short-text"><?php for($i=0;$i<23;$i++): ?>
                                <option value="<?php echo $i; ?>" <?php selected($i,$this->atts['zoom']); ?>><?php echo $i; ?></option>
                            <?php endfor; ?></select>
                        </div>
                        <div class="col col02" data-label="スタイル">
                            <select id="<?php echo esc_attr($this->uniqid); ?>-style" name="<?php echo $this->post_key; ?>[style]" class="short-text">
                                <option value="">default</option>
                                <?php foreach($this->style_files as $k => $v): ?><option value="<?php echo esc_attr($v['url']); ?>" <?php selected($v['url'],$this->atts['style']); ?>><?php echo esc_html($v['name']); ?></option><?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <?php /*<div class="row row04 lat-lng">
                    <div class="colbox">
                        <div class="col col01" data-label="地図緯度">
                            <input id="<?php echo esc_attr($this->uniqid); ?>-map-lat" type="text" class="short-text" name="<?php echo $this->post_key; ?>[map-lat]" value="<?php echo esc_attr($this->atts['map-lat']); ?>">
                        </div>
                        <div class="col col02" data-label="地図経度">
                            <input id="<?php echo esc_attr($this->uniqid); ?>-map-lng" type="text" class="short-text" name="<?php echo $this->post_key; ?>[map-lng]" value="<?php echo esc_attr($this->atts['map-lng']); ?>">
                        </div>
                    </div>
                </div>*/ ?>
                <?php /*<div class="row row05 lat-lng">
                    <div class="colbox">
                        <div class="col col01" data-label="アイコン緯度">
                            <input id="<?php echo esc_attr($this->uniqid); ?>-icon-lat" type="text" class="short-text" name="<?php echo $this->post_key; ?>[icon-lat]" value="<?php echo esc_attr($this->atts['icon-lat']); ?>">
                        </div>
                        <div class="col col02" data-label="アイコン経度">
                            <input id="<?php echo esc_attr($this->uniqid); ?>-icon-lng" type="text" class="short-text" name="<?php echo $this->post_key; ?>[icon-lng]" value="<?php echo esc_attr($this->atts['icon-lng']); ?>">
                        </div>
                    </div>
                </div> */ ?>
                <div class="row row06 icon" data-label="アイコン">
                    <?php $SelectImage->select_html($name=($this->post_key.'[icon]'),$attachment_id=$this->atts['icon'],$size='full',$id="{$this->uniqid}-icon"); ?>
                </div>
                <div class="row row07 info-text" data-label="吹き出し">
                    <textarea id="<?php echo esc_attr($this->uniqid); ?>-info" class="large-text" name="<?php echo $this->post_key; ?>[info]" placeholder="例）<?php echo esc_attr(get_bloginfo('name')); ?>"><?php echo esc_attr($this->atts['info']); ?></textarea>
                </div>
                <input id="<?php echo esc_attr($this->uniqid); ?>-map-lat" type="hidden" class="short-text" name="<?php echo $this->post_key; ?>[map-lat]" value="<?php echo esc_attr($this->atts['map-lat']); ?>">
                <input id="<?php echo esc_attr($this->uniqid); ?>-map-lng" type="hidden" class="short-text" name="<?php echo $this->post_key; ?>[map-lng]" value="<?php echo esc_attr($this->atts['map-lng']); ?>">
                <input id="<?php echo esc_attr($this->uniqid); ?>-icon-lat" type="hidden" class="short-text" name="<?php echo $this->post_key; ?>[icon-lat]" value="<?php echo esc_attr($this->atts['icon-lat']); ?>">
                <input id="<?php echo esc_attr($this->uniqid); ?>-icon-lng" type="hidden" class="short-text" name="<?php echo $this->post_key; ?>[icon-lng]" value="<?php echo esc_attr($this->atts['icon-lng']); ?>">
            </div><?php
            $buffer = ob_get_contents();
            ob_end_clean();
            return $buffer;
        }
        /*********************************
         * Google Map html
         *  ┣ zoom    - 拡大具合
         *  ┣ lat     - 緯度
         *  ┣ lng     - 経度
         *  ┣ icon    - マーカー(marker)
         *  ┣ inf     - 吹き出しテキスト
         *  ┗ style   - スタイル（※01）
         * ※01 - 「https://snazzymaps.com/」などからスタイル情報を取得するか、「https://ezmap.co/」で作成するかなどなど
        *********************************/
        function front_html($atts=array()){
            $SelectImage = new SelectImage();
            $this->atts = array_merge(array(
                'zoom'    => 16,
                'lat'     => 35.681281,
                'lng'     => 139.766397,
                'address' => '',
                'icon'    => '',
                'info'    => '',
                'style'   => '',
            ),$atts);
            if($this->atts['icon']){
                $icon = wp_get_attachment_image_src($this->atts['icon'],'full');
                $icon = $icon[0];
            }else{
                $icon = '';
            }
            ob_start();
            ?><div class="GoogleMapsView">
                <div class="row row01 google-maps">
                    <div class="google-maps-iframe" data-zoom="<?php echo esc_attr($this->atts['zoom']); ?>" data-lat="<?php echo esc_attr($this->atts['lat']); ?>" data-lng="<?php echo esc_attr($this->atts['lng']); ?>" data-icon="<?php echo $icon; ?>" data-info="<?php echo esc_attr($this->atts['info']); ?>" data-style="<?php echo esc_attr($this->atts['style']); ?>"></div>
                </div>
            </div><?php
            $buffer = ob_get_contents();
            ob_end_clean();
            return $buffer;
        }
    }

    /* Controller */
    class GoogleMapsController {
        /*********************************
         * 設定可能値
         *  ┣ default_option (modelで使用するオプションの初期値)
         *  ┣ option_key (modelで使用するデータ保存のkey)
         *  ┣ post_key (viewで仕様するデータ送信時の親key)
         *  ┣ uniqid (viewで仕様する各要素の元となるid)
         *  ┣ 
         *  ┣ 
        *********************************/
        function __construct($init = array()){
            //デフォルト値
            $this->default_option = json_decode(GoogleMapsMVC_DefaultOption,true);
            //初期処理
            foreach($init as $k => $v)$this->$k = $v;
            //Model
            $this->model = new GoogleMapsModel();
            //View
            $this->view = new GoogleMapsView(array('style_files' => $this->model->get_styles()));
        }
        function get_latlng($address){
            if(empty($address) || !is_string($address))return false;
            $res  = array();
            $req  = 'http://maps.google.com/maps/api/geocode/xml';
            $req .= '?address='.urlencode($address);
            $req .= '&sensor=false';    
            $xml  = simplexml_load_file($req) or die('XML parsing error');
            if($xml->status == 'OK'){
                $location = $xml->result->geometry->location;
                $res['lat'] = (string)$location->lat;
                $res['lng'] = (string)$location->lng;
            }
            return $res;
        }
        function get_option(){
            return $this->model->get();
        }
        function is_default_option(){
            return ((count(array_diff_assoc($this->model->get(),$this->default_option)))?false:true);
        }
        /*********************************
         * Google Map admin html
         * 
         * $atts - 属性の連想配列
         *  ┣ zoom     - 拡大具合
         *  ┣ map-lat  - 地図緯度
         *  ┣ map-lng  - 地図経度
         *  ┣ icon-lat - マーカー緯度
         *  ┣ icon-lng - マーカー経度
         *  ┣ icon     - マーカー(marker)
         *  ┣ info     - 吹き出しテキスト
         *  ┗ style    - スタイル（※01）
         * ※01 - 「https://snazzymaps.com/」などからスタイル情報を取得するか、「https://ezmap.co/」で作成するかなどなど
        *********************************/
        function set($atts){
            if(empty($atts) || !is_array($atts))return false;
            $this->model->set($atts);
            return true;
        }
        function update(){
            if($_SERVER["REQUEST_METHOD"] == "POST"){
                $my_nonce = filter_input(INPUT_POST,md5(__FILE__));
                if(wp_verify_nonce($my_nonce, wp_create_nonce(md5(__FILE__)))){
                    $options = filter_input(INPUT_POST,GoogleMapsMVC_PostKey,FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
                    $data    = array();
                    foreach($this->model->get_option_info() as $k => $v){
                        $data[$k] = $options[$k];
                        foreach($v as $kk => $vv){
                            if(is_array($vv)){
                                if($kk == 'select'){
                                    if(in_array($options[$k],$vv))$data[$k] = $options[$k];
                                }elseif($kk == 'numerical'){
                                    if($vv[0] <= $options[$k] && $vv[0] >= $options[$k])$data[$k] = $options[$k];
                                }
                            }elseif($vv == 'numerical'){
                                if(intval($options[$k]))$data[$k] = intval($options[$k]);
                            }elseif($vv == 'html'){
                                if(wp_kses_post($options[$k]))$data[$k] = wp_kses_post($options[$k]);
                            }
                        }
                    }
                    $this->model->set($data);
                    $this->model->update();
                }
            }
        }
        function show($atts=array()){
            if(is_admin()){
                echo $this->view->admin_html(($this->model->get() + $atts));
            }else{
                echo $this->view->front_html($this->model->get());
            }
        }
    }

    /* ajax等の登録用 */
    class GoogleMapsMVC {
        function __construct($init = array()){
            //ajax
            add_action('wp_ajax_wp_get_attachment_image_url',array($this,'wp_get_attachment_image_url'));
            add_action('admin_head',array($this,'admin_head'),1);
        }
        function admin_head() {
            ?><script>
                var $GoogleMapsMVC_ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
            </script><?php
        }
        function wp_get_attachment_image_url(){
            $attachment_id = $_POST['attachment_id'];
            echo wp_get_attachment_image_url($attachment_id,'full');
            die();
        }
    }
}
$GLOBALS['GoogleMapsMVC'] = new GoogleMapsMVC();
