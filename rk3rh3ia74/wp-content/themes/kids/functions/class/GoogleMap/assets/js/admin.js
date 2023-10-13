jQuery(document).ready(function($){
    /* Google Maps API */
    (function($){
        var $API_Key = 'AIzaSyDxscgNaCIvekdKBGT_7VzfiUHc06WqP0g';
        if($GoogleMapsMVC_data.id){
            var $id = $GoogleMapsMVC_data.id;
            $.getScript("//maps.googleapis.com/maps/api/js?key="+$API_Key, function() {
                console.log('GoogleMapsAPI loaded');
                var $geocoder = new google.maps.Geocoder();
                var $mapLat   = $('#'+$id+'-map-lat').val();
                var $mapLng   = $('#'+$id+'-map-lng').val();
                var $iconLat  = $('#'+$id+'-icon-lat').val();
                var $iconLng  = $('#'+$id+'-icon-lng').val();
                var $icon     = $('#'+$id+'-icon').val();
                var $zoom     = parseInt($('#'+$id+'-zoom').val());
                var $info     = $('#'+$id+'-info').val();
                var $style    = $('#'+$id+'-style').val();
                var $address  = $('#'+$id+'-address-element').val();
                //GoogleMap生成
                var $map = new google.maps.Map(document.getElementById($id),{
                    zoom: $zoom,
                    center: new google.maps.LatLng($mapLat,$mapLng),
                    //disableDoubleClickZoom: false,
                    scrollwheel: false,
                    mapTypeControl: false,
                    //zoomControl: false,
                    streetViewControl: false,
                });
                google.maps.event.addListener($map, "dragend", function() {
                    var $latlng = $map.getCenter();
                    $('#'+$id+'-map-lat').val($latlng.lat());
                    $('#'+$id+'-map-lng').val($latlng.lng());
                });

                //ズーム
                google.maps.event.addListener($map,'zoom_changed',function(){
                    var $zoom = $map.getZoom();
                    $('#'+$id+'-zoom').val($zoom);
                });
                $('#'+$id+'-zoom').change(function(){
                    $map.setZoom(parseInt($(this).val()));
                });

                //マーカー生成
                var $marker = new google.maps.Marker({
                    position: new google.maps.LatLng($iconLat,$iconLng),
                    map: $map,
                    draggable:true,
                });
                if($icon != ''){
                    markerIconChange($icon);
                }
                google.maps.event.addListener($marker, "dragend", function() {
                    var $position = $marker.getPosition();
                    $('#'+$id+'-map-lat').val($position.lat);
                    $('#'+$id+'-map-lng').val($position.lng);
                    $('#'+$id+'-icon-lat').val($position.lat);
                    $('#'+$id+'-icon-lng').val($position.lng);
                    $map.setCenter($marker.getPosition());
                });
                $('#'+$id+'-map-lat , #'+$id+'-map-lng').bind("keyup change",function(){
                    var $lat = $('#'+$id+'-map-lat').val();
                    var $lng = $('#'+$id+'-map-lng').val();
                    $map.setCenter(new google.maps.LatLng($lat,$lng));
                });
                $('#'+$id+'-icon-lat , #'+$id+'-icon-lng').bind("keyup change",function(){
                    var $lat = $('#'+$id+'-icon-lat').val();
                    var $lng = $('#'+$id+'-icon-lng').val();
                    $marker.setPosition( new google.maps.LatLng($lat,$lng));
                    $map.setCenter($marker.getPosition());
                });
                $('#'+$id+'-icon').change(function(){
                    var $attachment_id = $(this).val();
                    markerIconChange($attachment_id);
                });
                function markerIconChange($attachment_id){
                    if($attachment_id != ''){
                        $.ajax({
                            type: 'POST',
                            url: $GoogleMapsMVC_ajaxurl,
                            data: {
                                'action' : 'wp_get_attachment_image_url',
                                'attachment_id' : $attachment_id,
                            },
                            success: function($response){
                                $marker.setIcon($response);
                            }
                        });
                    }else{
                        $marker.setIcon('');
                    }
                }

                //吹き出し生成
                var $infowindow       = undefined;
                var $infowindowHandle = undefined;
                if($info != ''){
                    $infowindow = new google.maps.InfoWindow({
                        content: $info
                    });
                    $infowindowHandle = google.maps.event.addListener($marker,'click',function() {
                        $infowindow.open($map,$marker);
                    });
                }
                var keyDownCode = 0;
                $('#'+$id+'-info').keydown(function(e) {
                    keyDownCode = e.which;  // 押下されたキーのコードをとっておく(日本語変換確定の場合keyupと異なるコード)
                });
                $('#'+$id+'-info').keyup(function(e) {
                    if ( 13 == e.which && e.which == keyDownCode ) {
                        // 日本語入力確定済みかつinputにフォーカスのある状態でエンターキー押下したときの処理
                        //alert('test');
                        //return false;
                    }
                    $('#'+$id+'-info').change();  // 1文字ごとの変化を監視処理したい場合、changeイベントを発生させる
                });
                $('#'+$id+'-info').change(function() {
                    if('' === $(this).val()) {
                        // inputテキストが空欄になった
                        if(typeof $infowindow !== 'undefined'){
                            $infowindow.close($map,$marker);
                            $infowindow = undefined;
                            google.maps.event.removeListener($infowindowHandle);
                        }
                    } else if('' !== $(this).val()) {
                        // inputテキストが空欄ではない(変換途上の文字列含む)
                        if(typeof $infowindow === 'undefined'){
                            $infowindow = new google.maps.InfoWindow({
                                content: ''
                            });
                            $infowindowHandle = google.maps.event.addListener($marker,'click',function() {
                                $infowindow.open($map,$marker);
                            });
                            $infowindow.open($map,$marker);
                        }
                        var $content = $(this).val().replace( /\r?\n/g, '<br />' );
                        $infowindow.setContent($content);
                    }
                });

                //スタイル
                var $styledMapType = undefined;
                if($style == ''){
                    $styledMapType =  new google.maps.StyledMapType([]);
                    $map.mapTypes.set('styled_map',$styledMapType);
                    $map.setMapTypeId('styled_map');
                }else{
                    $.ajax({
                        url: $style,
                        success: function($response){
                            $styledMapType =  new google.maps.StyledMapType($response);
                            $map.mapTypes.set('styled_map',$styledMapType);
                            $map.setMapTypeId('styled_map');
                        }
                    });
                }
                $('#'+$id+'-style').change(function(){
                    if($(this).val() == ''){
                        $styledMapType =  new google.maps.StyledMapType([]);
                        $map.mapTypes.set('styled_map',$styledMapType);
                        $map.setMapTypeId('styled_map');
                    }else{
                        $.ajax({
                            url: $(this).val(),
                            success: function($response){
                                $styledMapType =  new google.maps.StyledMapType($response);
                                $map.mapTypes.set('styled_map',$styledMapType);
                                $map.setMapTypeId('styled_map');
                            }
                        });
                    }
                });

                //住所読み込み
                $('#'+$id+'-address-load-button').click(function(){
                    if($('#'+$id+'-address-element').length){
                        var $flg = true;
                        var $add = '';
                        if(!$.isArray($address)){
                            $address = $address.split(',');
                        }
                        for(var $i in $address) {
                            if($($address[$i]).val() == ''){
                                $flg = false;
                                return false;
                            }
                            $add += $($address[$i]).val();
                        }
                        if($flg){
                            $geocoder.geocode({'address':$add},function($results,$status){
                                // ジオコーディングが成功した場合
                                if ($status == google.maps.GeocoderStatus.OK) {
                                    // google.maps.Map()コンストラクタに定義されているsetCenter()メソッドで
                                    // 変換した緯度・経度情報を地図の中心に表示
                                    $map.setCenter($results[0].geometry.location);
                                    // 地図上に目印となるマーカーを設定います。
                                    // google.maps.Marker()コンストラクタにマーカーを設置するMapオブジェクトと
                                    // 変換した緯度・経度情報を渡してインスタンスを生成
                                    // →マーカー詳細
                                    var $latlng = $map.getCenter();
                                    $('#'+$id+'-map-lat').val($latlng.lat());
                                    $('#'+$id+'-map-lng').val($latlng.lng());
                                    $marker.setPosition( new google.maps.LatLng($latlng.lat(),$latlng.lng()));
                                // ジオコーディングが成功しなかった場合
                                } else {
                                    console.log('Geocode was not successful for the following reason: ' + $status);
                                }

                            });
                        }
                    }else{
                        
                    }
                });
            });
        }
    }(jQuery));
});
