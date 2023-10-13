<?php
global $theme_options;
$Related_Posts           = new Theme_Smart_Custom_Fields_Field_Related_Posts();
$page                    = filter_input(INPUT_GET,'page');
$footer_slider_brand     = get_option('注目のカーリース',array());
$footer_brand_list       = get_option('フッターカーリース一覧',array());
$footer_arearanking_list = get_option('フッターエリア別カーリース',array());
$footer_page_list        = get_option('フッター固定ページ',array());
?>
<div class="wrap">
    <h1>フッター関連の編集</h1>
    <?php if($_GET['update']=='ok'): ?><div id="message" class="updated notice is-dismissible">
        <p>フッター関連の編集に成功しました。</p>
        <button type="button" class="notice-dismiss"><span class="screen-reader-text">この通知を非表示にする</span></button>
    </div><?php endif; ?>
    <div class="fileedit-sub">
    </div>
    <div id="my-data" style="max-width: 1280px;width: 100%;">
        <form action="<?php echo admin_url('admin.php?page='.$page); ?>" method="post" autocomplete="off">
            <?php wp_nonce_field(wp_create_nonce($theme_options->create_nonce),$theme_options->my_nonce); ?>
            <div class="smart-cf-meta-box" style="margin: 0;border: 1px solid #ccc;">
                <div class="smart-cf-meta-box-table">
                    <table>
                        <tbody>
                            <tr>
                                <th>注目のカーリース</th>
                                <td>
                                    <?php echo $Related_Posts->get_option_field($name="{$theme_options->post_key}[slider]",$post_type=array('brand'),$limit=99,$footer_slider_brand); ?>
                                </td>
                            </tr>
                            <tr>
                                <th>カーリース一覧</th>
                                <td>
                                    <?php echo $Related_Posts->get_option_field($name="{$theme_options->post_key}[brand_list]",$post_type=array('brand'),$limit=99,$footer_brand_list); ?>
                                </td>
                            </tr>
                            <tr>
                                <th>エリア別カーリース</th>
                                <td>
                                    <?php echo $Related_Posts->get_option_field($name="{$theme_options->post_key}[arearanking_list]",$post_type=array('arearanking'),$limit=99,$footer_arearanking_list); ?>
                                </td>
                            </tr>
                            <tr>
                                <th>固定ページへのリンク</th>
                                <td>
                                    <?php echo $Related_Posts->get_option_field($name="{$theme_options->post_key}[page_list]",$post_type=array('page'),$limit=99,$footer_page_list); ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <p class="submit"><input name="submit" id="submit" class="button button-primary" value="更新" type="submit"></p>
        </form>
    </div>
</div>