jQuery.event.add(window,"load",function(){
    /* sortable */
    (function($){
        var $flg = true;
        if($('.sortable.loop').length){
            $('.sortable.loop').each(function(){
                var $_self = $(this);
                $_self.sortable({
                    cancel: '.cancel',
                    handle: '.sortable-loop-header .dashicons-sort',
                    axis: 'y',
                    cursor: 'ns-resize',
                    items: '.sortable-loop-item',
                    forcePlaceholderSize: true,
                    forceHelperSize: true,
                });
                //$('#sortable').disableSelection();
                if($_self.find('.sortable-loop-item').length < 2){
                    $_self.sortable('disable');
                    $flg = false;
                }
                $_self.find('.sortable-loop-header .dashicons-plus-alt').click(function($e){
                    var $clone = $(this).closest('.sortable-loop-item').clone(true);
                    $clone.find('input,textarea').val('');
                    $clone.find('.colorbox').remove();
                    if($flg == false){
                        $_self.sortable('enable');
                        $flg = true;
                    }
                    $clone.removeClass('close');
                    $(this).closest('.sortable-loop-item').after($clone);
                    return false;
                });
                $_self.find('.sortable-loop-header .dashicons-dismiss').click(function($e){
                    if($_self.find('.sortable-loop-item').length == 1){
                        var $item = $(this).closest('.sortable-loop-item');
                        $item.find('input,textarea').val('');
                        if($flg == true){
                            $_self.sortable('disable');
                            $flg = false;
                        }
                    }else{
                        $(this).closest('.sortable-loop-item').remove();
                    }
                    return false;
                });
                $_self.find('.sortable-loop-header .dashicons-arrow-up').click(function($e){
                    if($(this).closest('.sortable-loop-item').hasClass('close')){
                        $(this).closest('.sortable-loop-item').removeClass('close');
                    }else{
                        $(this).closest('.sortable-loop-item').addClass('close');
                    }
                });
                $_self.prev('.sortable-loop-list-action').find('.dashicons-list-view').click(function($e){
                    $_self.find('.sortable-loop-item').each(function(){
                        if(!$(this).hasClass('close')){
                            $(this).addClass('close');
                        }
                    })
                });
                $_self.prev('.sortable-loop-list-action').find('.dashicons-exerpt-view').click(function($e){
                    $_self.find('.sortable-loop-item').each(function(){
                        if($(this).hasClass('close')){
                            $(this).removeClass('close');
                        }
                    })
                });
            });
        }
    }(jQuery));
});