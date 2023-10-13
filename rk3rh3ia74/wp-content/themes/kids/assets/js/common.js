/* jQuery */
var $ = jQuery.noConflict();

/* アクセス直後 */
/*$(document).ready(function() {
    (function($){
    })(jQuery);
});*/

//読み込み完了後
$(window).load(function() {
    /* スライドアクション */
    (function($){
        if($('[data-tergtid]').length){
            $('[data-tergtid]').click(function(){
                var $_self = $(this);
                if($($_self.attr('data-tergtid')).length){
                    $($_self.attr('data-tergtid')).slideDown("slow",function(){
                        $_self.remove();
                    }).addClass('open');
                }
                return false;
            });
        }
    })(jQuery);

    /* SVG生成 */
    (function($){
        if($('.svgCreate').length){
            var userAgent = window.navigator.userAgent.toLowerCase();
            if(userAgent.indexOf('msie') != -1 || userAgent.indexOf('trident') != -1){
                $('.svgCreate').each(function(){
                    var $_pw   = $(this).width(),
                        $_ph   = $(this).height(),
                        $_draw = SVG($(this).attr('id')),
                        $_text = $(this).text(),
                        $_svg  = $_draw.text($_text).font({
                            family           : $(this).css('font-family'),
                            size             : parseInt($(this).css('font-size'), 10),
                            'font-weight'    : $(this).css('font-weight'),
                            leading          : 1,
                            'letter-spacing' : $(this).css('letter-spacing'),
                        }).attr('letter-spacing',$(this).css('letter-spacing')).attr('font-weight',$(this).css('font-weight')),
                        $_txdata    = $_svg.bbox(),
                        $_tw        = $_txdata.width,
                        $_th        = $_txdata.height,
                        $_group     = $_draw.group(),
                        $_grouprect = $_group.rect(0,0,$_pw,parseInt($(this).css('font-size'),10)),
                        $_Color     = $(this).attr('data-Color'),
                        $_morph     = $(this).attr('data-morph'),
                        $_gradient  = $_group.gradient('linear',function(stop) {
                            stop.at(0,$_Color);
                            stop.at(1,$_morph);
                        });
                    $_gradient.from(0,0).to(1,0);
                    //色/move配置
                    if(!$(this).hasClass('left')){
                        $_svg.attr({fill:$_gradient}).move(($_pw-$_tw)/2);
                    }else{
                        $_svg.attr({fill:$_gradient});
                    }
                });
            }
        }
    })(jQuery);

    /* スクロールイベント */
    $(window).scroll(function() {
        if($(window).scrollTop() && !$('body').hasClass('header-change')){
            $('body').addClass('header-change');
        }else if(!$(window).scrollTop() && $('body').hasClass('header-change')){
            $('body').removeClass('header-change');
        }
        //.fadein.scrollin
        if($('.fadein').length){
            $('.fadein').each(function(){
                var $height = window.innerHeight ? window.innerHeight: $(window).height();
                if(($(window).scrollTop() + $height) >= $(this).offset().top && !$(this).hasClass('scrollin')){
                    $(this).addClass('scrollin');
                }else if(($(window).scrollTop() + $height) < $(this).offset().top && $(this).hasClass('scrollin')){
                    $(this).removeClass('scrollin');
                }
            });
        }
    });
    $(window).trigger('scroll');

    /* タブ */
    (function($){
        if($('.tabChangeBox .tab a').length){
            $('.tabChangeBox .tab a').click(function(){
                var $_tabChangeBox  = $(this).closest('.tabChangeBox'),
                    $_tab           = $(this).closest('.tab'),
                    $_target_tab_li = $(this).closest('li'),
                    $_tabContents   = $_tabChangeBox.find('.tabContents'),
                    $_href          = $(this).attr("href"),
                    $_target        = '#' + $_href.split('#')[1];
                if($_target_tab_li.hasClass('show')){
                    return false;
                }
                if($_target && $($_target).length){
                    $_tab.find('.show').removeClass('show');
                    $_target_tab_li.addClass('show');
                    $_tabContents.find('.show').removeClass('show');
                    $($_target).addClass('show');
                    return false;
                }
            });
        }
    })(jQuery);
    

    /* アンカーリンク */
    (function($){
        var $speed = 400;
        $(document).on('click','a',function($e){
            if($(this).parent().hasClass('drawer-toggle') || $(this).hasClass('noscroll')){
                return;
            }
            // アンカーの値取得width
            var $windowWidth  = window.innerWidth ? window.innerWidth: $(window).width(),
                $windowHeight = window.innerHeight ? window.innerHeight: $(window).height(),
                $href         = $(this).attr("href"),
                $hash         = ($href?'#' + $href.split('#')[1]:''),
                $_timer;
            if($href == '#'){
                // スムーススクロール
                $('html, body').stop().animate({ 
                    scrollTop: 0
                },$speed);
                return false;
            }else if($hash && $($hash).length){
                // 移動先を取得
                var $target = $($hash);
                // 移動先を数値で取得
                var $position = $target.offset().top - ($('#wpadminbar').length?$('#wpadminbar').height():0) + 1;
                var $width    = window.innerWidth ? window.innerWidth: $(window).width();
                if($width <= 750){
                    $position -= $('#mainHeader .showBox').height();
                }else{
                    $position -= $('#mainHeader .showBox').height();
                }
                // スムーススクロール
                $('html, body').stop().animate({ 
                    scrollTop: $position
                },$speed);
                /*clearTimeout($_timer);
                $_timer = setTimeout(function(){
                    $(window).trigger('scroll');
                },$speed * 2);*/
                return false;
            }
        });
    })(jQuery);

    /* ドロワー */
    (function($){
        if($('#menu-gnav').length){
            $('#menu-gnav').removeClass('display-none');
            $(".drawer").drawer();
            var _ua = (function(u){
                return {
                    Tablet:(u.indexOf("windows") != -1 && u.indexOf("touch") != -1 && u.indexOf("tablet pc") == -1)
                        || u.indexOf("ipad") != -1
                        || (u.indexOf("android") != -1 && u.indexOf("mobile") == -1)
                        || (u.indexOf("firefox") != -1 && u.indexOf("tablet") != -1)
                        || u.indexOf("kindle") != -1
                        || u.indexOf("silk") != -1
                        || u.indexOf("playbook") != -1,
                    Mobile:(u.indexOf("windows") != -1 && u.indexOf("phone") != -1)
                        || u.indexOf("iphone") != -1
                        || u.indexOf("ipod") != -1
                        || (u.indexOf("android") != -1 && u.indexOf("mobile") != -1)
                        || (u.indexOf("firefox") != -1 && u.indexOf("mobile") != -1)
                        || u.indexOf("blackberry") != -1
                }
            })(window.navigator.userAgent.toLowerCase());
            if (_ua.Mobile) {
                $(".drawer-menu-item").on('click', function() {
                    $('.drawer').drawer('close');
                });
            }
            $('.drawer').on('drawer.opened', function(){
                if($('body').hasClass('size-sp') && !$('.drawer-nav').hasClass('_drawer-open')){
                    $('.drawer-nav').addClass('_drawer-open').removeClass('_drawer-close');
                }
            });
            $('.drawer').on('drawer.closed', function(){
                if($('body').hasClass('size-sp') && $('.drawer-nav').hasClass('_drawer-open')){
                    $('.drawer-nav').removeClass('_drawer-open').addClass('_drawer-close');
                }
            });
            /*$('.drawer').on('drawer.opened', function(){
                var $offset    = $('#mainInnerWrap').offset();
                var $scrolltop = $(window).scrollTop();
                $('#mainWrap').height($('#mainInnerWrap').height());
                $('#mainInnerWrap').addClass('fixed').css('top',(($scrolltop - 46) * -1) + 'px');
            });
            $('.drawer').on('drawer.closed', function(){
                $('#mainWrap').css('height','auto');
                $('#mainInnerWrap').css('top','auto').removeClass('fixed');
            });*/
            function windowSize(){
                var $w = window.innerWidth ? window.innerWidth: $(window).width();
                if($w <= 768){
                    if(!$('body').hasClass('size-sp')){
                        $('body').addClass('size-sp');
                    }
                    if($('.drawer-nav').hasClass('_drawer-open')){
                        $('.drawer-nav').removeClass('_drawer-open');
                    }
                    if($('.drawer-nav').hasClass('_drawer-close')){
                        $('.drawer-nav').removeClass('_drawer-close');
                    }
                    if($('#sidebar .myWidget.news').length){
                        $('#menu-gnav').append($('<li class="add_item" />').append($('#sidebar .myWidget.news').addClass('drawer-menu')));
                    }
                    if($('#sidebar .myWidget.COLUMN').length){
                        $('#menu-gnav').append($('<li class="add_item" />').append($('#sidebar .myWidget.COLUMN').addClass('drawer-menu')));
                    }
                    if($('#mainHeader .table-cell.col03 .menu-hnav-right-container').length){
                        $('#menu-gnav').append($('<li class="add_item" />').append($('#mainHeader .table-cell.col03 .menu-hnav-right-container').addClass('drawer-menu')));
                    }
                }else{
                    if($('body').hasClass('size-sp')){
                        $('body').removeClass('size-sp');
                    }
                    if($('#menu-gnav .myWidget.news').length){
                        $('#sidebar aside').append($('#menu-gnav .myWidget.news').removeClass('drawer-menu'));
                    }
                    if($('#menu-gnav .myWidget.COLUMN').length){
                        $('#sidebar aside').append($('#menu-gnav .myWidget.COLUMN').removeClass('drawer-menu'));
                    }
                    if($('#menu-gnav .menu-hnav-right-container').length){
                        $('#mainHeader .table-cell.col03').append($('#menu-gnav .menu-hnav-right-container').removeClass('drawer-menu'));
                    }
                    if($('#menu-gnav .add_item').length){
                        $('#menu-gnav .add_item').remove();
                    }
                }
            }
            windowSize();
            $(window).on("resize", function(){
                windowSize();
            });
        }
    })(jQuery);

    /* key-visual */
    (function($){
        if($('#keyvisual .carousel-cell').length > 1){
            var $key_visual = $('#keyvisual .main-carousel').flickity({
                // options
                cellAlign: 'center',
                contain: true,
                wrapAround: true,
                autoPlay: 5000,
                prevNextButtons: false,
                groupCells: false,
                pageDots: false,
                fade: true,
            });
        }
    }(jQuery));

    /* postsBox */
    (function($){
        if($('.postsBox article').length > 3){
            var $postsBox = $('.postsBox .shortcode.posts');
            $postsBox.on('ready.flickity',function(){
                var $height = 0;
                $(this).find('article').each(function(){
                    if($height < $(this).height())$height = $(this).height();
                });
                $(this).find('article').each(function(){
                    $(this).height($height);
                });
            });
            $postsBox.flickity({
                // options
                cellAlign: 'center',
                contain: true,
                wrapAround: true,
                autoPlay: 5000,
                prevNextButtons: true,
                groupCells: false,
                pageDots: false,
            });
        }
    }(jQuery));

    /* 別ページからのアンカーリンク */
    (function($){
        var $urlHash = location.hash;
        if($urlHash){
            if($('a[href="'+ location.href+'"]').length){
                $('a[href="'+location.href+'"]').eq(0).trigger('click');
            }
        }
    })(jQuery);
});
