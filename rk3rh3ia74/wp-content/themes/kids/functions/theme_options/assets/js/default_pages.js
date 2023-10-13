jQuery.event.add(window,"load",function(){
    /* タブ */
    (function($){
        if($('.main-carousel').length){
            /* ブラウザバック */
            window.onpopstate = function(evt) {
                location.reload();
            };
            /* タブ操作 */
            var $initialIndex = parseInt($('.main-carousel').attr('data-initialIndex'));
            var $carousel = $('.main-carousel').flickity({
                "contain":true,
                "freeScroll":true,
                "pageDots":false,
                "prevNextButtons": false,
                "initialIndex":$initialIndex,
                "cellSelector":".item"
            });
            if(!$('.main-carousel .item').eq($initialIndex).hasClass('is-checked')){
                $('.main-carousel .item').eq($initialIndex).addClass('is-checked');
                history.replaceState('','',$('.main-carousel .item').eq($initialIndex).find('a').attr('data-href'));
                $('#form01').attr('action',$('.main-carousel .item').eq($initialIndex).find('a').attr('data-href'));
            }
            defaultOptionsDisplay()
            $('.main-carousel a').click(function(){
                if(!$(this).parent('.is-checked').length){
                    var $index = $('.main-carousel a').index(this);
                    $('.main-carousel .is-checked').removeClass('is-checked');
                    $carousel.flickity('select',$index);
                    $('.main-carousel .item').eq($index).addClass('is-checked');
                    history.pushState('','',$(this).attr('data-href'));
                    $('#form01').attr('action',$(this).attr('data-href'));
                    defaultOptionsDisplay();
                }
                return false;
            });
            $('.flickity-prev-next-button.next').click(function(){
                var $index = $('.main-carousel .item').index($('.main-carousel .is-checked'));
                if(($index + 2) <= $('.main-carousel .item').length){
                    $('.main-carousel .is-checked').removeClass('is-checked');
                    $carousel.flickity('select',$index + 1);
                    $('.main-carousel .is-selected').addClass('is-checked');
                    history.pushState('','',$('.main-carousel .is-selected a').attr('data-href'));
                    $('#form01').attr('action',$('.main-carousel .is-selected a').attr('data-href'));
                    defaultOptionsDisplay();
                }
                return false;
            });
            $('.flickity-prev-next-button.previous').click(function(){
                var $index = $('.main-carousel .item').index($('.main-carousel .is-checked'));
                if(($index - 1) >= 0){
                    $('.main-carousel .is-checked').removeClass('is-checked');
                    $carousel.flickity('select',$index - 1);
                    $('.main-carousel .is-selected').addClass('is-checked');
                    history.pushState('','',$('.main-carousel .is-selected a').attr('data-href'));
                    $('#form01').attr('action',$('.main-carousel .is-selected a').attr('data-href'));
                    defaultOptionsDisplay();
                }
                return false;
            });
            /* 関数群 */
            function defaultOptionsDisplay(){
                $('.default_options').not('.display-none').addClass('display-none');
                $('.default_options.slug-' + $('.main-carousel .item.is-checked').attr('data-slug')).removeClass('display-none');
                $carousel.flickity('resize');
            }
        }
    }(jQuery));
    /* sortable */
    (function($){
        $('.sortable').sortable({
            //cancel: '.cancel',
            handle: '.handle span',
            axis: 'y',
            cursor: 'ns-resize',
            items: 'li.sort',
        });
        $('.sortable').each(function(){
            if($(this).find('li.sort').length < 2){
                $(this).sortable('disable').addClass('disable');
            }
        })
        $('button.add').click(function($e){
            var $clone = $(this).closest('li').clone(true);
            $clone.find('input,textarea').val('');
            $clone.find('.colorbox').remove();
            if($(this).closest('.sortable').hasClass('disable')){
                $(this).closest('.sortable').sortable('enable').removeClass('disable');
            }
            $(this).closest('li.sort').after($clone);
            return false;
        });
        $('button.remove').click(function($e){
            if($(this).closest('.sortable').find('li.sort').length == 1){
                var $item = $(this).closest('li');
                $item.find('input,textarea').val('');
                $item.find('.colorbox').remove();
                if(!$(this).closest('.sortable').hasClass('disable')){
                    $(this).closest('.sortable').sortable('disable').addClass('disable');
                }
            }else{
                $(this).closest('li').remove();
            }
            return false;
        });
    }(jQuery));
});