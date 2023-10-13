/* アクセス直後 */
jQuery(document).ready(function() {
    /* 検索 */
    (function($){
        $('#searchform').submit(function($event){
            $event.preventDefault();
            var $s      = $.trim($('#searchform input[name="s"]').val());
            var $action = $searchform.search_link + $s + ($s != ''?$searchform.wp_rewrite_front:'');
            window.location.href = $action;
            return false;
        });
    }(jQuery));
});

/* 読み込み完了後 */
jQuery(window).load(function () {
});
