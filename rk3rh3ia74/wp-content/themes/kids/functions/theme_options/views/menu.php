<?php
global $theme_options;
$SelectImage = new SelectImage();
$page = filter_input(INPUT_GET,'page');
$prefecture = array("北海道", "青森県", "岩手県", "宮城県", "秋田県", "山形県", "福島県", "茨城県", "栃木県", "群馬県", "埼玉県", "千葉県", "東京都", "神奈川県", "新潟県", "富山県", "石川県", "福井県", "山梨県", "長野県", "岐阜県", "静岡県", "愛知県", "三重県", "滋賀県", "京都府", "大阪府", "兵庫県", "奈良県", "和歌山県", "鳥取県", "島根県", "岡山県", "広島県", "山口県", "徳島県", "香川県", "愛媛県", "高知県", "福岡県", "佐賀県", "長崎県", "熊本県", "大分県", "宮崎県", "鹿児島県", "沖縄県");
//デフォルトデータ取得
$options = wp_load_alloptions();//get_alloptions();
//test($options);
$default_options = $theme_options->default_options;
//test($options);
foreach($default_options as $k => $v){
    if($k == 'address'){
        $default_options['address']['zip'] = $options['zip']?$options['zip']:'';
        $default_options['address']['pref'] = $options['pref']?$options['pref']:'';
        $default_options['address']['address01'] = $options['address01']?$options['address01']:'';
        $default_options['address']['address02'] = $options['address02']?$options['address02']:'';
        $default_options['address']['building'] = $options['building']?$options['building']:'';
    }elseif($k == 'business_hours'){
        $default_options['business_hours']['b_start'] = $options['b_start'];
        $default_options['business_hours']['b_end'] = $options['b_end'];
        $default_options['business_hours']['b_text'] = $options['b_text'];
    }elseif($k == 'reception_time'){
        $default_options['reception_time']['r_start'] = $options['r_start'];
        $default_options['reception_time']['r_end'] = $options['r_end'];
        $default_options['reception_time']['r_text'] = $options['r_text'];
    }else{
        if(isset($options[$k])){
            $default_options[$k]['value'] = is_serialized($options[$k])?maybe_unserialize($options[$k]):$options[$k];
        }else{
            $default_options[$k]['value'] = '';
        }
        if(empty($default_options[$k]['value']) && !empty($v['default']))$default_options[$k]['value'] = do_shortcode($v['default']);
    }
}
//test($default_options);
//追加データ取得
$add_options = array();
foreach($options as $k => $v){
    if(is_serialized($v)){
        //test($k);
        $v = maybe_unserialize($v);
        if(isset($v['option_type']) && $v['option_type'] == 'add_options'){
            //test($k);
            //test($v);
            if(empty($add_options[$v['order']])){
                $add_options[$v['order']] = $v;
                $add_options[$v['order']]['label'] = $k;
            }else{
                $key = $v['order'] + 1;
                while(!empty($add_options[$key])){
                    $key++;
                }
                $add_options[$key] = $v;
                $add_options[$key]['label'] = $k;
            }
        }
    }
}
ksort($add_options);
?>
<div class="wrap">
    <h1>テーマオプションの編集</h1>
    <?php if(!empty($_GET['update']) && $_GET['update'] == 'ok'): ?><div id="message" class="updated notice is-dismissible">
        <p>テーマオプションの編集に成功しました。</p>
        <button type="button" class="notice-dismiss"><span class="screen-reader-text">この通知を非表示にする</span></button>
    </div><?php endif; ?>
    <div class="fileedit-sub">
    </div>
    <div id="my-data">
        <form action="<?php echo admin_url('admin.php?page='.$page); ?>" method="post" autocomplete="off">
            <?php wp_nonce_field(wp_create_nonce($theme_options->create_nonce),$theme_options->my_nonce); ?>
            <ul><?php foreach($default_options as $k => $v): ?>
                <li class="default_options cancel">
                    <div class="table">
                        <div class="table-cell col00 handle">
                            
                        </div>
                        <div class="table-cell col01">
                            <label>
                                <span class="label block">項目</span>
                                <span class="input block" style="font-weight:bold;"><?php echo $v['label']; ?></span>
                                <input type="hidden" name="<?php echo $this->post_key; ?>[label][<?php echo $k; ?>]" value="<?php echo esc_attr($v['label']); ?>">
                            </label>
                        </div>
                        <div class="table-cell col02">
                            <?php if(!in_array($v['type'],array('address','custom-post-types',))): ?><label><?php endif; ?>
                                <span class="label block">内容</span>
                                <?php if($k == 'copyright'): ?>
                                    <span class="input block"><input type="<?php echo $v['type']; ?>" name="<?php echo $this->post_key; ?>[value][<?php echo $k; ?>]" value="<?php echo esc_attr($v['value']); ?>"></span>
                                <?php elseif($v['type'] == 'image'): ?>
                                    <span class="imagebox block"><?php $SelectImage->select_html("{$this->post_key}[value][{$k}]",$v['value']); ?></span>
                                <?php elseif($v['type'] == 'images'): ?>
                                    <span class="imagebox block imagessortable">
                                        <?php //test($v); ?>
                                        <?php if(is_array($v['value']) && count($v['value'])){ ?>
                                            <?php foreach($v['value'] as $item){ ?><span class="images-item">
                                                <div class="row infoArea table">
                                                    <div class="table-cell col01 colbox ar">
                                                        <span class="handle dashicons dashicons-move"></span>
                                                    </div>
                                                    <div class="table-cell col02 colbox ar">
                                                        <span class="col col01_01 button-add"><i class="dashicons dashicons-plus-alt"></i></span>
                                                        <span class="col col01_02 button-remove"><i class="dashicons dashicons-dismiss"></i></span>
                                                    </div>
                                                </div>
                                                <?php $SelectImage->select_html("{$this->post_key}[value][{$k}][]",$item); ?>
                                            </span><?php } ?><?php }else{ ?><span class="images-item">
                                                <div class="row infoArea table">
                                                    <div class="table-cell col01 colbox ar">
                                                        <span class="handle dashicons dashicons-move"></span>
                                                    </div>
                                                    <div class="table-cell col02 colbox ar">
                                                        <span class="col col01_01 button-add"><i class="dashicons dashicons-plus-alt"></i></span>
                                                        <span class="col col01_02 button-remove"><i class="dashicons dashicons-dismiss"></i></span>
                                                    </div>
                                                </div>
                                                <?php $SelectImage->select_html("{$this->post_key}[value][{$k}][]"); ?>
                                            </span><?php } ?>
                                    </span>
                                <?php elseif($v['type'] == 'textarea'): ?>
                                    <span class="textarea block"><textarea name="<?php echo $this->post_key; ?>[value][<?php echo $k; ?>]"><?php echo esc_textarea($v['value']); ?></textarea></span>
                                <?php elseif($v['type'] == 'business_hours'): ?>
                                    <span class="business_hours block">
                                        <?php
                                            $start = $default_options['business_hours']['b_start'];
                                            $end   = $default_options['business_hours']['b_end'];
                                            if($start === NULL || $end === NULL || $start >= $end){
                                                $start = 0;
                                                $end   = 86400;
                                            }
                                        ?>
                                        <span class="block">
                                            <span class="inline-block">開始時間：</span>
                                            <select name="<?php echo $this->post_key; ?>[value][business_hours][b_start]"><?php for($i=0;$i<=(3600 * 32);$i+=1800): ?>
                                                <option value="<?php echo $i; ?>" <?php selected($start,$i); ?>><?php echo second_format($i); ?></option>
                                            <?php endfor; ?></select>
                                        </span>
                                        <span class="block">
                                            <span class="inline-block">終了時間：</span>
                                            <select name="<?php echo $this->post_key; ?>[value][business_hours][b_end]"><?php for($i=0;$i<=(3600 * 32);$i+=1800): ?>
                                                <option value="<?php echo $i; ?>" <?php selected($end,$i); ?>><?php echo second_format($i); ?></option>
                                            <?php endfor; ?></select>
                                        </span>
                                        <span class="block">
                                            <span class="inline-block">フロント側テキスト：</span>
                                            <input type="text" name="<?php echo $this->post_key; ?>[value][business_hours][b_text]" value="<?php echo esc_attr($default_options['business_hours']['b_text']); ?>">
                                        </span>
                                    </span>
                                <?php elseif($v['type'] == 'reception_time'): ?>
                                    <span class="business_hours block">
                                        <?php
                                            $start = $default_options['reception_time']['r_start'];
                                            $end   = $default_options['reception_time']['r_end'];
                                            if($start === NULL || $end === NULL || $start >= $end){
                                                $start = 0;
                                                $end   = 86400;
                                            }
                                        ?>
                                        <span class="block">
                                            <span class="inline-block">開始時間：</span>
                                            <select name="<?php echo $this->post_key; ?>[value][reception_time][r_start]"><?php for($i=0;$i<=(3600 * 32);$i+=1800): ?>
                                                <option value="<?php echo $i; ?>" <?php selected($start,$i); ?>><?php echo second_format($i); ?></option>
                                            <?php endfor; ?></select>
                                        </span>
                                        <span class="block">
                                            <span class="inline-block">終了時間：</span>
                                            <select name="<?php echo $this->post_key; ?>[value][reception_time][r_end]"><?php for($i=0;$i<=(3600 * 32);$i+=1800): ?>
                                                <option value="<?php echo $i; ?>" <?php selected($end,$i); ?>><?php echo second_format($i); ?></option>
                                            <?php endfor; ?></select>
                                        </span>
                                        <span class="block">
                                            <span class="inline-block">フロント側テキスト：</span>
                                            <input type="text" name="<?php echo $this->post_key; ?>[value][reception_time][r_text]" value="<?php echo esc_attr($default_options['reception_time']['r_text']); ?>">
                                        </span>
                                    </span>
                                <?php elseif($v['type'] == 'address'): ?>
                                    <div class="address block">
                                        <span class="zip block">
                                            <span class="block"><span class="inline-block">〒</span><input name="<?php echo $this->post_key; ?>[value][address][zip]" type="text" maxlength="7" value="<?php echo esc_attr($default_options['address']['zip']); ?>"></span>
                                            <span class="description">※ハイフンなしで入力してください。</span>
                                        </span>
                                        <span class="pref block">
                                            <span class="block"><span class="inline-block">都道府県：</span><select id="address-pref" name="<?php echo $this->post_key; ?>[value][address][pref]">
                                                <option value="">--</option>
                                                <?php foreach($prefecture as $k => $v): ?>
                                                    <option value="<?php echo esc_attr($v); ?>"<?php if($v == $default_options['address']['pref']): ?> selected="selected"<?php endif; ?>><?php echo esc_attr($v); ?></option>
                                                <?php endforeach; ?>
                                            </select></span>
                                        </span>
                                        <span class="address01 block">
                                            <span class="block"><span class="inline-block">市区町村：</span><input type="text" id="address-address01" name="<?php echo $this->post_key; ?>[value][address][address01]" value="<?php echo esc_attr($default_options['address']['address01']); ?>"></span>
                                        </span>
                                        <span class="address02 block">
                                            <span class="block"><span class="inline-block">町名番地：</span><input type="text" id="address-address02" name="<?php echo $this->post_key; ?>[value][address][address02]" value="<?php echo esc_attr($default_options['address']['address02']); ?>"></span>
                                        </span>
                                        <span class="building block">
                                            <span class="block"><span class="inline-block">建物名等：</span><input type="text" name="<?php echo $this->post_key; ?>[value][address][building]" value="<?php echo esc_attr($default_options['address']['building']); ?>"></span>
                                        </span>
                                        <span class="google-map-area block">
                                            <?php
                                                $GoogleMapsController = new GoogleMapsController();
                                                if($GoogleMapsController->is_default_option()){
                                                    if(!empty($default_options['address']['pref']) && !empty($default_options['address']['address01']) && !empty($default_options['address']['address02'])){
                                                        $latlng = $GoogleMapsController->get_latlng($default_options['address']['pref'].$default_options['address']['address01'].$default_options['address']['address02']);
                                                        $GoogleMapsController->set(array(
                                                            'map-lat'  => $latlng['lat'],
                                                            'map-lng'  => $latlng['lng'],
                                                            'icon-lat' => $latlng['lat'],
                                                            'icon-lng' => $latlng['lng'],
                                                        ));
                                                    }
                                                }
                                                echo $GoogleMapsController->show(array('address-element'=>'#address-pref,#address-address01,#address-address02'));
                                            ?>
                                        </span>
                                        
                                    </div>
                                <?php elseif($v['type'] == 'checkbox'): ?>
                                    <?php //test($v); ?>
                                    <span class="input block checkbox"><input type="<?php echo $v['type']; ?>" name="<?php echo $this->post_key; ?>[value][<?php echo $k; ?>]" value="<?php echo true; ?>" <?php if($v['value'] == true): ?>checked="checked"<?php endif; ?>><span class="input_label"><?php echo $v['input_label']; ?></span></span>
                                <?php elseif($v['type'] == 'medical_menu_archive'): ?>
                                    <?php $the_query = new WP_Query(array('post_type'=>'page','nopaging'=>true)); ?>
                                    <select name="<?php echo $this->post_key; ?>[value][<?php echo $k; ?>]"><option value="">--</option><?php while($the_query->have_posts()){$the_query->the_post(); ?>
                                        <option value="<?php the_ID(); ?>" <?php selected($v['value'],get_the_ID()); ?>><?php the_title(); ?></option>
                                    <?php }wp_reset_postdata(); ?></select>
                                <?php elseif($v['type'] == 'blog_contact_form'): ?>
                                    <?php $the_query = new WP_Query(array('post_type'=>'form','nopaging'=>true)); ?>
                                    <select name="<?php echo $this->post_key; ?>[value][<?php echo $k; ?>][post_id]"><option value="">--</option><?php while($the_query->have_posts()){$the_query->the_post(); ?>
                                        <option value="<?php the_ID(); ?>" <?php selected($v['value']['post_id'],get_the_ID()); ?>><?php the_title(); ?></option>
                                    <?php }wp_reset_postdata(); ?></select>
                                    <span class="imagebox block" style="margin-top: 1em;"><?php $SelectImage->select_html("{$this->post_key}[value][{$k}][image]",$v['value']['image']); ?></span>
                                <?php elseif($v['type'] == 'blog_yoyaku_form'): ?>
                                    <?php $the_query = new WP_Query(array('post_type'=>'form','nopaging'=>true)); ?>
                                    <select name="<?php echo $this->post_key; ?>[value][<?php echo $k; ?>][post_id]"><option value="">--</option><?php while($the_query->have_posts()){$the_query->the_post(); ?>
                                        <option value="<?php the_ID(); ?>" <?php selected($v['value']['post_id'],get_the_ID()); ?>><?php the_title(); ?></option>
                                    <?php }wp_reset_postdata(); ?></select>
                                    <span class="imagebox block" style="margin-top: 1em;"><?php $SelectImage->select_html("{$this->post_key}[value][{$k}][image]",$v['value']['image']); ?></span>
                                <?php elseif($v['type'] == 'custom-post-types'): ?>
                                    <div class="custom-post-types" data-max-number="<?php echo $v['max-number']; ?>">
                                        <div class="buttons">
                                            <span class="dashicons dashicons-list-view"></span>
                                            <span class="dashicons dashicons-exerpt-view"></span>
                                        </div>
                                        <?php if(is_array($v['value']) && count($v['value'])):foreach($v['value'] as $cpt_key => $cpt_value): ?><div class="item close">
                                            <div class="row row01">
                                                <div class="table">
                                                    <div class="table-cell col00 handle">
                                                        <span class="dashicons dashicons-leftright"></span>
                                                    </div>
                                                    <div class="table-cell col01">
                                                        <span class="dashicons dashicons-admin-collapse"></span>
                                                    </div>
                                                    <div class="table-cell col02">
                                                        <input type="text" name="<?php echo $this->post_key; ?>[value][custom-post-types][post_label][]" value="<?php echo esc_attr($cpt_value['post_label']); ?>">
                                                    </div>
                                                    <div class="table-cell col03">
                                                        <span class="add"><span class="dashicons dashicons-plus-alt"></span></span>
                                                        <span class="remove"><span class="dashicons dashicons-dismiss"></span></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row row02">
                                                <div class="row row02_01">
                                                    <input type="text" name="<?php echo $this->post_key; ?>[value][custom-post-types][post_slug][]" value="<?php echo esc_attr($cpt_value['post_slug']); ?>">
                                                </div>
                                                <div class="row row02_02">
                                                    <input type="text" name="<?php echo $this->post_key; ?>[value][custom-post-types][taxonomy_label][]" value="<?php echo esc_attr($cpt_value['taxonomy_label']); ?>">
                                                </div>
                                                <div class="row row02_03">
                                                    <input type="text" name="<?php echo $this->post_key; ?>[value][custom-post-types][taxonomy_slug][]" value="<?php echo esc_attr($cpt_value['taxonomy_slug']); ?>">
                                                </div>
                                            </div>
                                            <p class="row row03 description">※全て必須項目です。</p>
                                        </div><?php endforeach;else: ?><div class="item">
                                            <div class="row row01">
                                                <div class="table">
                                                    <div class="table-cell col00 handle">
                                                        <span class="dashicons dashicons-leftright"></span>
                                                    </div>
                                                    <div class="table-cell col01">
                                                        <span class="dashicons dashicons-admin-collapse"></span>
                                                    </div>
                                                    <div class="table-cell col02">
                                                        <input type="text" name="<?php echo $this->post_key; ?>[value][custom-post-types][post_label][]" value="">
                                                    </div>
                                                    <div class="table-cell col03">
                                                        <span class="add"><span class="dashicons dashicons-plus-alt"></span></span>
                                                        <span class="remove"><span class="dashicons dashicons-dismiss"></span></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row row02">
                                                <div class="row row02_01">
                                                    <input type="text" name="<?php echo $this->post_key; ?>[value][custom-post-types][post_slug][]" value="">
                                                </div>
                                                <div class="row row02_02">
                                                    <input type="text" name="<?php echo $this->post_key; ?>[value][custom-post-types][taxonomy_label][]" value="">
                                                </div>
                                                <div class="row row02_03">
                                                    <input type="text" name="<?php echo $this->post_key; ?>[value][custom-post-types][taxonomy_slug][]" value="">
                                                </div>
                                            </div>
                                            <p class="row row03 description">※全て必須項目です。</p>
                                        </div><?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    <span class="input block"><input type="<?php echo $v['type']; ?>" name="<?php echo $this->post_key; ?>[value][<?php echo $k; ?>]" value="<?php echo esc_attr($v['value']); ?>"></span>
                                <?php endif; ?>
                            <?php if(!in_array($v['type'],array('address','custom-post-types',))): ?></label><?php endif; ?>
                            <?php if(!empty($v['description'])): ?><p class="description"><?php echo nl2br(esc_html($v['description'])); ?></p><?php endif; ?>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?></ul>
            <?php /*<p style="margin:2em 0 -0.5em;font-weight:bold;">メモ用にお使いください</p>
            <ul class="sortable"><?php if(count($add_options)):foreach($add_options as $k => $v): ?>
                <li class="sort">
                    <div class="table">
                        <div class="table-cell col00 handle">
                            <span class="dashicons dashicons-leftright"></span>
                        </div>
                        <div class="table-cell col01">
                            <label>
                                <span class="label block">項目</span>
                                <span class="input block"><input type="text" name="<?php echo $this->post_key; ?>[label][]" value="<?php echo esc_attr($v['label']); ?>"></span>
                            </label>
                        </div>
                        <div class="table-cell col02">
                            <label>
                                <span class="label block">内容</span>
                            </label>
                            <div class="info">
                                <select class="add_feild_type" name="<?php echo $this->post_key; ?>[type][]">
                                    <option value="textarea">テキスト</option>
                                    <option value="image" <?php if($v['type'] == 'image'): ?>selected="selected"<?php endif; ?>>画像</option>
                                </select>
                            </div>
                            <div class="naiyoubox <?php if($v['type'] == 'image'): ?>image<?php endif; ?>">
                                <?php if($v['type'] == 'image'): ?>
                                    <textarea name="<?php echo $this->post_key; ?>[value][]"></textarea>
                                    <?php $SelectImage->select_html("{$this->post_key}[imagevalue][]",$v['imagevalue']); ?>
                                <?php else: ?>
                                    <textarea name="<?php echo $this->post_key; ?>[value][]"><?php echo esc_textarea($v['value']); ?></textarea>
                                    <?php $SelectImage->select_html("{$this->post_key}[imagevalue][]"); ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="table-cell col03">
                            <button class="add button-secondary"><span class="dashicons dashicons-plus"></span></button>
                            <span>/</span>
                            <button class="remove button-secondary"><span class="dashicons dashicons-minus"></span></button>
                        </div>
                    </div>
                </li>
            <?php endforeach;else: ?>
                <li class="sort">
                    <div class="table">
                        <div class="table-cell col00 handle">
                            <span class="dashicons dashicons-leftright"></span>
                        </div>
                        <div class="table-cell col01">
                            <label>
                                <span class="label block">項目</span>
                                <span class="input block"><input type="text" name="<?php echo $this->post_key; ?>[label][]" value=""></span>
                            </label>
                        </div>
                        <div class="table-cell col02">
                            <label>
                                <span class="label block">内容</span>
                            </label>
                            <div class="info">
                                <select class="add_feild_type" name="<?php echo $this->post_key; ?>[type][]">
                                    <option value="textarea">テキスト</option>
                                    <option value="image">画像</option>
                                </select>
                            </div>
                            <div class="naiyoubox">
                                <textarea name="<?php echo $this->post_key; ?>[value][]"></textarea>
                                <?php $SelectImage->select_html("{$this->post_key}[imagevalue][]"); ?>
                            </div>
                        </div>
                        <div class="table-cell col03">
                            <button class="add button-secondary"><span class="dashicons dashicons-plus"></span></button>
                            <span>/</span>
                            <button class="remove button-secondary"><span class="dashicons dashicons-minus"></span></button>
                        </div>
                    </div>
                </li>
            <?php endif; ?></ul>*/ ?>
            <p class="submit"><input name="submit" id="submit" class="button button-primary" value="テーマオプションを更新" type="submit"></p>
        </form>
    </div>
</div>