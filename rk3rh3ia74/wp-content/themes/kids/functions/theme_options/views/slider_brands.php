<?php
global $theme_options;
$Related_Posts  = new Theme_Smart_Custom_Fields_Field_Related_Posts();
$page           = filter_input(INPUT_GET,'page');
$slider_brands  = get_option($GLOBALS['title'],array());
?>
<div class="wrap">
    <h1><?php echo $GLOBALS['title']; ?>の編集</h1>
    <?php if($_GET['update']=='ok'): ?><div id="message" class="updated notice is-dismissible">
        <p><?php echo $GLOBALS['title']; ?>の編集に成功しました。</p>
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
                                <td>
                                    <p style="margin-top: 0;">表示する記事</p>
                                    <?php echo $Related_Posts->get_option_field($name=$theme_options->post_key,$post_type=array('post','news'),$limit=99,$slider_brands); ?>
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