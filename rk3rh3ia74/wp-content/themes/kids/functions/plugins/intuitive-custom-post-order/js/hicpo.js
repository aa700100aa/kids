(function($){
    
    // posts

    $('table.posts #the-list, table.pages #the-list').sortable({
        'items': 'tr',
        'axis': (($themedata.post_type == 'staffs')?false:'y'),
        'helper': fixHelper,
        'update' : function(e, ui) {
            $.post( ajaxurl, {
                action: 'update-menu-order',
                order: $('#the-list').sortable('serialize'),
            });
        }
    });
    if(($themedata.post_type == 'staffs')){
        $('table.posts #the-list, table.pages #the-list').on('sortupdate',function(e, ui) {
            $.post( ajaxurl, {
                action: 'update-menu-order',
                order: $('#the-list').sortable('serialize'),
            });
        });
    }
    //$("#the-list").disableSelection();
    
    // tags
    
    $('table.tags #the-list').sortable({
        'items': 'tr',
        'axis': 'y',
        'helper': fixHelper,
        'update' : function(e, ui) {
            $.post( ajaxurl, {
                action: 'update-menu-order-tags',
                order: $('#the-list').sortable('serialize'),
            });
        }
    });
    //$("#the-list").disableSelection();
    
    var fixHelper = function(e, ui) {
        ui.children().children().each(function() {
            $(this).width($(this).width());
        });
        return ui;
    };
    
})(jQuery)
