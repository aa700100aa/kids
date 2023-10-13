jQuery.event.add(window,'load',function(){
    (function($){
        //イメージアップロード
        $(document).on('click','.upload_image .media',function(e) {
            var custom_uploader;
            var $parent = $(this).closest(".upload_image");
            e.preventDefault();
            if (custom_uploader) {
                custom_uploader.open();
                return;
            }
            custom_uploader = wp.media({
                title: 'ZIPファイル選択',
                // 以下のコメントアウトを解除すると画像のみに限定される。
                library: {
                    type: 'application/zip'
                },
                button: {
                    text: 'ZIPファイル選択'
                },
                multiple: false // falseにすると画像を1つしか選択できなくなる
            });
            custom_uploader.on('select', function() {
                var images = custom_uploader.state().get('selection');
                images.each(function(file){
                    var $obj    = file.toJSON(), $id = $obj.id, $url = $obj.url, $alt = $obj.alt?$obj.alt:$obj.title, $thumbnail='';
                    $thumbnail = $url;
                    $parent.find('.colorbox').remove();
                    $parent.find('.dummy').prepend('<a href="'+$url+'" class="colorbox" target="_brank"><span class="dashicons dashicons-media-archive"><span>'+$alt+'</span></span></a>');
                    $parent.find('.img-id').val($id);
                    /* submenu_03 */
                    if($('.submenu_03').length){
                        var $for_id = '#' + $parent.closest('.table-cell').attr('data-for-id');
                        $($for_id+' .image').css('background-image','url('+$thumbnail+')').removeClass('none-image');
                    }
                    /* /submenu_03 */
                    $parent.find('.img-id').trigger("change");
                });
            });
            custom_uploader.open();
        });
        //画像削除
        $(document).on('click','.upload_image .del',function(e) {
            var $parent = $(this).closest('.upload_image');
            $parent.find('.img_box .dummy .colorbox').remove();
            $parent.find('.img-id').val('');
            /* submenu_03 */
            if($('.submenu_03').length){
                var $for_id = '#' + $parent.closest('.table-cell').attr('data-for-id');
                $($for_id+' .image').css('background-image','none').addClass('none-image');
            }
            /* /submenu_03 */
            $parent.find('.img-id').trigger("change");
            return false;
        });
        //isset関数
        function isset( data ){
            return ( typeof( data ) != 'undefined' );
        }
    }(jQuery));
});