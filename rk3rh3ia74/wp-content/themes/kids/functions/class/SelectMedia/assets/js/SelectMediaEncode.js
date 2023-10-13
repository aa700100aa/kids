jQuery.event.add(window,'load',function(){
    (function($){
        //動画からプレビュー生成
        $(document).on('change','.upload_image .img-id',function(){
            if($('#videoCanvas').length && $(this).val() != '' && !empty($(this).val())){
                var $parent   = $(this).closest(".upload_image");
                var $file_url = $parent.find('.dummy .colorbox').attr('data-href');
                var $post_id  = $(this).val();
                if(!empty($file_url)){
                    $.post(
                        $SelectMediaData.get_post_thumbnail_id.endpoint, 
                        {
                            action: $SelectMediaData.get_post_thumbnail_id.action,
                            post_id: $post_id,
                            secure: $SelectMediaData.get_post_thumbnail_id.secure
                        }, 
                        function($thumbnail_id){
                            if(empty($thumbnail_id)){
                                loadingScreen();
                                videoScreenshot($file_url,$post_id,unloadingScreen);
                            }else{
                                unloadingScreen();
                            }
                        }
                    );
                }else{
                    unloadingScreen();
                }
            }else{
                unloadingScreen();
            }
        });
    }(jQuery));
    //関数群
    function videoScreenshot($src,$post_id,$callback){
        videoThumnailCreate($src,$post_id,$callback);
    }
    function videoThumnailCreate($src,$post_id,$callback){
        var $fps          = 4;
        var $fps_time     = 1000 / $fps;
        var $videoEndTime = 5;
        var $imagesBox    = jQuery('#videoThumnail');
        var $inline       = jQuery('#videoTag').attr('src',$src);
        var $video        = jQuery('#videoTag')[0];
        var $canvas       = jQuery('#videoCanvas')[0];
        var $index        = 0;
        var $vW           = 0;
        var $vH           = 0;
        var $images       = [];
        var $setInterval  = '';
        var $encoder      = new GIFEncoder();
        var $currentTime  = 0;
        $video.addEventListener('loadedmetadata' ,videoThumnailCreateLoadedmetadata);
        $video.addEventListener('timeupdate'     ,videoThumnailCreateTimeupdate);
        $video.autoplay = false;
        $video.muted    = true;
        $video.loop     = false;
        $encoder.setRepeat(0);
        //$encoder.setDelay($fps_time * 2);
        $imagesBox.empty();
        function videoThumnailCreateLoadedmetadata(){
            //console.log("loadedmetadata event.");
            var $vR         = $video.videoHeight / $video.videoWidth;
            $vW             = 600;
            $vH             = 600 * $vR;
            $video.width    = $canvas.width  = $vW;
            $video.height   = $canvas.height = $vH;
            $encoder.setSize($vW,$vH);
            $video.removeEventListener('loadedmetadata',videoThumnailCreateLoadedmetadata);
            $video.play();
        }
        function videoThumnailCreateTimeupdate() {
            //console.log('timeupdate event.');
            $video.removeEventListener('timeupdate',videoThumnailCreateTimeupdate);
            //$encoder.start();
            if( window.FormData ){
                $setInterval = setInterval(videoThumnailCreateInterval,$fps_time);
            }
        }
        function videoThumnailCreateInterval(){
            ////console.log('setInterval event.');
            if($video.paused == true || $video.currentTime == 0 || parseFloat($video.currentTime) == $currentTime)return;
            if(Math.floor($video.currentTime) < $videoEndTime){
                var $img = new Image();
                $canvas.getContext('2d').drawImage($video,0,0,$vW,$vH);
                $img.src = $canvas.toDataURL("image/jpeg",0.4);
                //$img.src = $canvas.toDataURL("image/gif");
                //$encoder.addFrame($canvas.getContext('2d'));
                $imagesBox.append($img);
                //$images.push($canvas.toDataURL("image/jpeg",0.8));
                //console.log('サムネイル生成(' + ++$index + ') ' + $video.currentTime);
                ++$index;
                $currentTime = parseFloat($video.currentTime);
            }else{
                clearInterval($setInterval);
                //var $play_time = $video.currentTime;
                $video.pause();
                $video.currentTime = 0;

                if($imagesBox.find('img').length){
                    console.log('GIFEncoder エンコード開始');
                    var $setDelay = ($currentTime * 1000) / $index;
                    //console.log('currentTime:' + $currentTime);
                    //console.log('index:' + $index);
                    //console.log('setDelay:' + $setDelay);
                    $encoder.setDelay($setDelay);
                    $encoder.start();
                    $imagesBox.find('img').each(function($i,$e){
                        //var $img = new Image();
                        //$img.src = $e;
                        $canvas.getContext('2d').drawImage($e,0,0,$vW,$vH);
                        $encoder.addFrame($canvas.getContext('2d'));
                    });
                    $encoder.finish();
                    var $bin  = new Uint8Array($encoder.stream().bin);
                    var $blob = new Blob([$bin.buffer], {type: 'image/gif'});
                    //var $url  = URL.createObjectURL($blob);
                    //var $img        = new Image();
                    //var $binary_gif = $encoder.stream().getData();
                    //var $src        = 'data:image/gif;base64,'+encode64($binary_gif);

                    var $formData = new FormData();
                    $formData.append('action',  $SelectMediaData.videoThumnailUpload.action);
                    $formData.append('secure',  $SelectMediaData.videoThumnailUpload.secure);
                    $formData.append('post_id', $post_id);
                    $formData.append('image',   $blob ,"video-thumnail.gif");
                    //console.log($formData);
                    //$img.src        = $src;
                    $imagesBox.empty();
                    //$imagesBox.append($img);

                    jQuery.ajax({
                        url: $SelectMediaData.videoThumnailUpload.endpoint,
                        method: 'post',
                        //dataType: 'json',
                        data: $formData,
                        processData: false,
                        contentType: false,
                        cache: false,
                    }).done(function($res){
                        // 送信せいこう！
                        //console.log('SUCCESS',$res);
                    }).fail(function( jqXHR, textStatus, errorThrown ) {
                        // しっぱい！
                        //console.log('ERROR',jqXHR,textStatus,errorThrown);
                    });

                    console.log('GIFEncoder エンコード終了');
                }
                if(typeof $callback != "undefined" && typeof $callback == "function")$callback();
                //console.log('サムネイル生成完了('+$index+')');
            }
        }
    }
});
