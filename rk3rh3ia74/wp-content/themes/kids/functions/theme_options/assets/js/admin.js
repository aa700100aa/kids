jQuery.event.add(window,"load",function(){
    /* group_admin_menu */
    (function($){
        if($('#group_admin_menu').length){
            function tab(){
                var $figure_id = $('#group_admin_menu .tab input:checked').attr('data-figure-id');
                $('#group_admin_menu figure').css('display','none');
                $('#'+$figure_id).css('display','block');
                $('#tabvalue').val($figure_id);
            }
            tab();
            $('#group_admin_menu .tab input').change(function(){
                tab();
            });
        }
    }(jQuery));

    /* 追加フィールドタイプ */
    (function($){
        if($('.add_feild_type').length){
            $('.add_feild_type').change(function(){
                var $value = $(this).val();
                if($value == 'image'){
                    if(!$(this).closest('.info').next().hasClass('image'))$(this).closest('.info').next().addClass('image');
                }else{
                    if($(this).closest('.info').next().hasClass('image'))$(this).closest('.info').next().removeClass('image');
                }
            });
        }
    }(jQuery));
    /* sortable */
    (function($){
        var $flg = true;
        $('.sortable').sortable({
            cancel: '.cancel',
            handle: '.handle span',
            axis: 'y',
            cursor: 'ns-resize',
            items: 'li.sort',
        });
        //$('#sortable').disableSelection();
        if($('.sortable > li.sort').length < 2){
            $('.sortable').sortable('disable');
            $flg = false;
        }
        $('button.add').click(function($e){
            var $clone = $(this).closest('li').clone(true);
            $clone.find('input,textarea').val('');
            $clone.find('.colorbox').remove();
            if($flg == false){
                $('.sortable').sortable('enable');
                $flg = true;
            }
            $(this).closest('li.sort').after($clone);
            return false;
        });
        $('button.remove').click(function($e){
            if($('.sortable > li.sort').length == 1){
                var $item = $(this).closest('li');
                $item.find('input,textarea').val('');
                if($flg == true){
                    $('.sortable').sortable('disable');
                    $flg = false;
                }
            }else{
                $(this).closest('li').remove();
            }
            return false;
        });
    }(jQuery));
    /* custom-post-types */
    (function($){
        var $flg = true;
        $('.custom-post-types .dashicons-admin-collapse').click(function($e){
            var $closest = $(this).closest('.item');
            if($closest.hasClass('close')){
                $closest.removeClass('close');
            }else{
                $closest.addClass('close');
            }
        });
        $('.custom-post-types .dashicons-list-view').click(function($e){
            var $closest = $(this).closest('.custom-post-types');
            $closest.find('.item').addClass('close');
        });
        $('.custom-post-types .dashicons-exerpt-view').click(function($e){
            var $closest = $(this).closest('.custom-post-types');
            $closest.find('.item').removeClass('close');
        });
        $('.custom-post-types').sortable({
            //cancel: '.cancel',
            handle: '.col00.handle',
            cursor: 'ns-resize',
            items:  'div.item',
            axis:   'y',
        });
        //$('#sortable').disableSelection();
        if($('.custom-post-types .item').length < 2){
            $('.custom-post-types').sortable('disable');
            $flg = false;
        }
        $('.custom-post-types .add').click(function($e){
            var $closest    = $(this).closest('.custom-post-types');
            var $max_number = $closest.attr('data-max-number')?String($closest.attr('data-max-number')):0;
            if($max_number && $max_number <= $('.custom-post-types .item').length)return false;
            var $clone = $(this).closest('.item').clone(true);
            $clone.find('input,textarea').val('');
            $clone.find('.colorbox').remove();
            $clone.removeClass('close');
            if($flg == false){
                $closest.sortable('enable');
                $flg = true;
            }
            $(this).closest('.item').after($clone);
            return false;
        });
        $('.custom-post-types .remove').click(function($e){
            var $closest = $(this).closest('.custom-post-types');
            if($closest.find('.item').length == 1){
                var $item = $(this).closest('.item');
                $item.removeClass('close');
                $item.find('input,textarea').val('');
                if($flg == true){
                    $('.custom-post-types').sortable('disable');
                    $flg = false;
                }
            }else{
                $(this).closest('.item').remove();
                if($closest.find('.item').length == 1){
                    $closest.find('.item').removeClass('close');
                }
            }
            return false;
        });
    }(jQuery));
    (function($){
        //sortable
        if($('.imagebox.block.imagessortable').length){
            $('.imagebox.block.imagessortable').each(function(){
                var $outerWidth  = $(this).find('.images-item').outerWidth(true) / 10;
                var $outerHeight = $(this).find('.images-item').outerHeight(true) / 10;
                $(this).sortable({
                    //cancel: '.cancel',
                    handle: '.handle',
                    //axis: 'y',
                    cursor: 'move',
                    items: '.images-item',
                    placeholder: 'ui-state-highlight',
                    forcePlaceholderSize: true,
                    //grid: [$outerWidth,$outerHeight],
                    tolerance: "pointer",
                });
            });
            //入力領域追加
            $('.imagebox.block.imagessortable').on('click','.button-add',function(){
                var $ul    = $(this).closest('.imagebox.block.imagessortable');
                var $li    = $(this).closest('.images-item');
                var $clone = $li.clone(true);
                $clone.find('input,textarea').val('');
                $clone.find('input[checked]').removeAttr('checked');
                $clone.find('input[type="checkbox"]').prop('checked',false);
                $clone.find('.colorbox').remove();
                $li.after($clone);
            });
            //入力領域削除
            $('.imagebox.block.imagessortable').on('click','.button-remove',function(){
                var $ul    = $(this).closest('.imagebox.block.imagessortable');
                var $li    = $(this).closest('.images-item');
                if($ul.find('.images-item').length > 1){
                    $li.remove();
                }else{
                    $li.find('input,textarea').val('');
                    $li.find('input[checked]').removeAttr('checked');
                    $li.find('input[type="checkbox"]').prop('checked',false);
                    $li.find('.colorbox').remove();
                }
            });
        }
    }(jQuery));
});