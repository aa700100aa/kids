/**
 * editor.js
 * Version    : 1.2.0
 * Author     : inc2734
 * Created    : September 30, 2014
 * Modified   : January 27, 2017
 * License    : GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */
jQuery( function( $ ) {

	var table_class = 'tr';

	/**
	 * 初期化
	 * click_count はロードボタンを押すごとに加算。
	 * 検索ボックスが変更されるたびに 0 にリセットすること。
	 */
	$( '.smart-cf-meta-box .load-relation-post-types' ).closest( table_class )
		.data( 'click_count', 0 )
		.data( 'search_timer', null )
		.data( 'recent_search_query', '' );

	/**
	 * 検索ボタン
	 */
	$( document ).on( 'keyup', '.smart-cf-meta-box .search-input-post-types', function() {
		var parent = $( this ).closest( table_class );
		var search_timer = parent.data( 'search_timer' );
		clearTimeout( search_timer );

		parent.data( 'click_count', 0 );
		parent.find( '.smart-cf-relation-children-select ul li' ).remove();

		var search_query = $( this ).val();
		parent.data( 'recent_search_query', search_query );
		parent.data( 'search_timer', setTimeout( function() {
			get_posts( { s: search_query }, parent );
		}, 2000 ) );
	} );

	/**
	 * 読み込みボタン
	 */
	$( document ).on( 'click', '.smart-cf-meta-box .load-relation-post-types', function() {
		var parent = $( this ).closest( table_class );
		var click_count = parent.data( 'click_count' );
		click_count ++;
		parent.data( 'click_count', click_count );
		var search_query = parent.data( 'recent_search_query' );
		if ( search_query ) {
			get_posts( { s: search_query }, parent );
		} else {
			get_posts( {}, parent );
		}
	} );

	/**
	 * クエリ
	 */
	function get_posts( args, table ) {
		var click_count   = table.data( 'click_count' );
		var post_types    = table.find( '.smart-cf-relation-left' ).data( 'post-types' );
		var btn_load      = table.find( '.load-relation-post-types' );
		var btn_load_text = btn_load.text();
		btn_load.text( 'Now loading...' );
//alert(post_types);
		args = $.extend( args, {
			action     : smart_cf_relation_post_types.action,
			nonce      : smart_cf_relation_post_types.nonce,
			click_count: click_count,
			post_types : post_types
		} );
		$.post(
			smart_cf_relation_post_types.endpoint,
			args,
			function( response ) {
				btn_load.addClass( 'hide' );
                var $makers = post_types.split(',');
				$( response ).each( function( i, e ) {
                    if($makers.length > 1){
                        table.find( '.smart-cf-relation-children-select ul' ).append(
                            $( '<li />' )
                                .attr( 'data-id', this.ID )
                                .text( this.post_title + ' ( ' + this.post_type_name + ' )' )
                        );
                    }else{
                        table.find( '.smart-cf-relation-children-select ul' ).append(
                            $( '<li />' )
                                .attr( 'data-id', this.ID )
                                .text( this.post_title )
                        );
                    }
				} );

				btn_load.text( btn_load_text );
				btn_load.removeClass( 'hide' );
			}
		);
		return false;
	}
} );
