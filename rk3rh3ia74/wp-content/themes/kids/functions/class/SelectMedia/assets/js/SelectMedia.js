jQuery.event.add(window,'load',function(){
    (function($){
        //イメージアップロード
        var $custom_uploader = [];
        $(document).on('click','.upload_image .media',function($e) {
            $e.preventDefault();
            var $parent = $(this).closest(".upload_image");
            if($parent.attr('id') == '' || $parent.attr('id') == undefined){
                var $uuid = getUniqueStr();
                $parent.attr('id',$uuid);
            }
            if($custom_uploader[$parent.attr('id')]) {
                $($custom_uploader[$parent.attr('id')].el).find('.attachment-filters').trigger('change');
                $custom_uploader[$parent.attr('id')].open();
                return;
            }
            $custom_uploader[$parent.attr('id')] = wp.media({
                title: 'メディア選択',
                // 以下のコメントアウトを解除すると画像のみに限定される。
                library: {
                    type: 'image,video'
                },
                button: {
                    text: 'メディア選択'
                },
                multiple: false // falseにすると画像を1つしか選択できなくなる
            });
            $custom_uploader[$parent.attr('id')].on('select', function() {
                var images = $custom_uploader[$parent.attr('id')].state().get('selection');
                images.each(function(file){
                    var $obj = file.toJSON(), $id = $obj.id, $url = $obj.url, $alt = $obj.alt?$obj.alt:$obj.title, $thumbnail='';
                    if($parent.find('.img-id').val($id) != $id){
                        lockScreen();
                        $.post(
                            $SelectMediaData.get_wp_mime_type_icon.endpoint, 
                            {
                                action: $SelectMediaData.get_wp_mime_type_icon.action,
                                attachment_id: $id,
                                secure: $SelectMediaData.get_wp_mime_type_icon.secure
                            },
                            function($thumbnail){
                                $parent.find('.colorbox').remove();
                                $parent.find('.dummy').prepend('<a class="colorbox" href="#" data-href="'+$url+'"><span><span class="image" style="background-image:url('+$thumbnail+');" /></span></a>');
                                $parent.find('.img-id').val($id);
                                $parent.find('.img-id').trigger("change");
                            }
                        );
                    }
                });
            });
            $($custom_uploader[$parent.attr('id')].el).find('.attachment-filters').trigger('change');
            $custom_uploader[$parent.attr('id')].open();
        });
        //画像削除
        $(document).on('click','.upload_image .del',function(e) {
            var $parent = $(this).closest('.upload_image');
            $parent.find('.img_box .dummy .colorbox').remove();
            $parent.find('.img-id').val('');
            $parent.find('.img-id').trigger("change");
            return false;
        });
        //画像
        $(document).on('click','.upload_image .colorbox',function(){
            var $src = $(this).attr("data-href");
            var $ext = $src.split('.');
            $ext = $ext[$ext.length-1].toLowerCase();
            if($ext == 'mp4' || $ext == 'mov'){
                var $parent = $('#SelectMedia-media-colorbox-content');
                //var $uuid   = 'SelectMedia-media-colorbox-content';//getUniqueStr();
                var $inline = $parent.find('video').attr('src',$src);
                var $video  = $('#videoTag')[0];
                var $wW     = window.innerWidth ? window.innerWidth: $(window).width();
                var $wH     = window.innerHeight ? (window.innerHeight - $('wpadminbar').height()): ($(window).height() - $('wpadminbar').height());
                $video.addEventListener('loadedmetadata',colorboxLoadedmetadata);
                function colorboxLoadedmetadata(){
                    var $cW = $wW * 0.7;
                    var $cH = $wH * 0.7;
                    if($video.videoWidth <= $cW && $video.videoHeight <= $cH){
                        $cW = $video.videoWidth;
                        $cH = $video.videoHeight;
                        //alert('1');
                    }else if($video.videoWidth <= $cW){
                        $cW = $video.videoWidth;
                        $cH = $cW * ($video.videoHeight / $video.videoWidth);
                        //alert('2');
                    }else if($video.videoHeight <= $cH){
                        $cH = $video.videoHeight;
                        $cW = $cH * ($video.videoWidth / $video.videoHeight);
                        //alert('3');
                    }else{
                        if($cW >= ($cH * ($video.videoWidth / $video.videoHeight))){
                            $cW = $cH * ($video.videoWidth / $video.videoHeight);
                        }else{
                            $cH = $cW * ($video.videoHeight / $video.videoWidth);
                        }
                        //alert('4');
                    }
                    $.colorbox({
                        innerHeight: $cH + 'px',
                        innerWidth: $cW + 'px',
                        inline:true,
                        href:'#videoInlineContent',
                        onOpen:function(){
                            $('#colorbox').css('margin-top',($('#wpadminbar').height() / 2) + 'px');
                        },
                        onCleanup:function(){
                            if(!$video.paused){
                                $video.pause();
                            }
                        },
                    });
                    $video.removeEventListener('loadedmetadata',colorboxLoadedmetadata);
                }
            }/*else if($ext == 'mp3'){
                var $parent = $(this).closest('.upload_image');
                var $uuid   = getUniqueStr();
                var $inline = $parent.find('.video.inline_content').attr('id',$uuid).find('video').attr('src',$src);
                var $v_uuid = getUniqueStr();
                var $video  = $parent.find('.video.inline_content video').attr('id',$v_uuid);
                var $w      = window.innerWidth ? window.innerWidth: $(window).width();
                var $h      = window.innerHeight ? window.innerHeight: $(window).height();
                $video.addEventListener('loadedmetadata', function() {
                    $.colorbox({
                        height: ($h * 0.8) + 'px',
                        width: ($h * 0.8) + 'px',
                        inline:true,
                        href:'#'+$uuid,
                        onCleanup:function(){
                            if(!$video.paused){
                                $video.pause();
                            }
                        },
                    });
                });
            }*/else{
                $.colorbox({maxHeight:"80%",maxWidth:"80%",href:$(this).attr("data-href"), rel:'colorbox'});
            }
            return false;
        });
    }(jQuery));
});

/* 関数群 */
function isset( data ){
    return ( typeof( data ) != 'undefined' );
}
function empty( data ){
    return ( typeof( data ) == 'undefined' || data == '' || data == '0' || data == 0);
}
function reset($target){
    $target.pause();
    $target.currentTime = 0;
}
function getUniqueStr(myStrong){
    var strong = 1000;
    if (myStrong) strong = myStrong;
    return new Date().getTime().toString(16)  + Math.floor(strong*Math.random()).toString(16);
}

var $lockId = getUniqueStr();
function lockScreen($id) {
    $id = !empty($id)?$id:$lockId;
    var $divTag = jQuery('<div />').attr("id",$id).addClass('lockScreen');
    $divTag.css("z-index", "99999")
        .css("position", "fixed")
        .css("top", "0px")
        .css("right", "0px")
        .css("bottom", "0px")
        .css("left", "0px")
        .css("background-color", "#000")
        .css("opacity", "0.6");
    jQuery('body').css("overflow", "hidden").append($divTag);
}
function unlockScreen($id) {
    $id = !empty($id)?$id:$lockId;
    jQuery("#" + $id).remove();
}
function loadingScreen($id) {
    $id = !empty($id)?$id:$lockId;
    if(jQuery("#" + $id).length == 0){
        lockScreen($id);
    }
    if(!jQuery("#" + $id).hasClass('loading')){
        jQuery("#" + $id).addClass('loading');
    }
}
function unloadingScreen($id) {
    $id = !empty($id)?$id:$lockId;
    unlockScreen($id);
    jQuery('body').css("overflow", "visible");
}
