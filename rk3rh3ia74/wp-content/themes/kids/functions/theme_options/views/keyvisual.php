<?php
global $theme_options;
$Related_Posts     = new Theme_Smart_Custom_Fields_Field_Related_Posts();
$SelectImage       = new SelectImage();
$page              = filter_input(INPUT_GET,'page');
$sidebar_campaigns = get_option('キービジュアル',array());
?>
<div class="wrap">
    <h1>キービジュアルの編集</h1>
    <?php if($_GET['update']=='ok'): ?><div id="message" class="updated notice is-dismissible">
        <p>キービジュアルの編集に成功しました。</p>
        <button type="button" class="notice-dismiss"><span class="screen-reader-text">この通知を非表示にする</span></button>
    </div><?php endif; ?>
    <div class="fileedit-sub">
    </div>
    <div id="my-data">
        <form action="<?php echo admin_url('admin.php?page='.$page); ?>" method="post" autocomplete="off">
            <?php wp_nonce_field(wp_create_nonce($theme_options->create_nonce),$theme_options->my_nonce); ?>
            <ul class="sortable"><?php if(count($sidebar_campaigns)):foreach($sidebar_campaigns as $k => $v): ?>
                <li class="sort">
                    <div class="table">
                        <div class="table-cell col00 handle">
                            <span class="dashicons dashicons-leftright"></span>
                        </div>
                        <div class="table-cell col01">
                            <label>
                                <span class="label block">画像</span>
                            </label>
                            <div class="info">
                                <?php $SelectImage->select_html("{$this->post_key}[campaign][image][]",$v['image']); ?>
                            </div>
                        </div>
                        <div class="table-cell col02">
                            <label>
                                <span class="label block">URL</span>
                                <span class="input block"><input type="text" name="<?php echo $this->post_key; ?>[campaign][url][]" value="<?php echo esc_attr($v['url']); ?>"></span>
                                <span class="select block"><select name="<?php echo $this->post_key; ?>[campaign][target][]">
                                    <option value="">現在のWindowに表示</option>
                                    <option value="_blank" <?php selected($v['target'],'_blank'); ?>>新規のWindowに表示</option>
                                </select></span>
                            </label>
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
                                <span class="label block">画像</span>
                            </label>
                            <div class="info">
                                <?php $SelectImage->select_html("{$this->post_key}[campaign][image][]"); ?>
                            </div>
                        </div>
                        <div class="table-cell col02">
                            <label>
                                <span class="label block">URL</span>
                                <span class="input block"><input type="text" name="<?php echo $this->post_key; ?>[campaign][url][]" value=""></span>
                                <span class="select block"><select name="<?php echo $this->post_key; ?>[campaign][target][]">
                                    <option value="">現在のWindowに表示</option>
                                    <option value="_blank">新規のWindowに表示</option>
                                </select></span>
                            </label>
                        </div>
                        <div class="table-cell col03">
                            <button class="add button-secondary"><span class="dashicons dashicons-plus"></span></button>
                            <span>/</span>
                            <button class="remove button-secondary"><span class="dashicons dashicons-minus"></span></button>
                        </div>
                    </div>
                </li>
            <?php endif; ?></ul>
            <p class="submit"><input name="submit" id="submit" class="button button-primary" value="更新" type="submit"></p>
        </form>
    </div>
</div>