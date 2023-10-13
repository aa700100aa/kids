<?php
/*
Plugin Name: 独自の一覧テーブルの例
Plugin URI: http://www.mattvanandel.com/
Description: 公式のWordPress APIを使用して独自の一覧テーブルを生成する方法を示す高度にドキュメント化されたプラグイン。
Version: 1.4.1
Author: Matt van Andel
Author URI: http://www.mattvanandel.com
License: GPL2
*/
 
// 訳注: GPLのライセンスの文言のため訳していません。
/*  Copyright 2015  Matthew Van Andel  (email : matt@mattvanandel.com)
 
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.
 
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
 
    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
 
 
 
/* == 注意 ===================================================================
 * このファイルを変更しないでください。代わりに全体のプラグインのコピーを作成し、
 * リネームしたもので作業してください。もしこのプラグインを直接編集したあとに
 * 更新がリリースされた場合、変更は失われます。
 * ========================================================================== */
 
 
 
/*************************** ベースクラスを読み込む *******************************
 *******************************************************************************
 * WP_List_Tableクラスはプラグインでは自動的に利用可能にならないため、
 * クラスがすでに利用可能かを確認してから必要に応じて読み込む必要があります。このチュートリアルでは、
 * WordPressの本体から直接WP_List_Tableクラスを使用します。
 *
 * 重要:
 * WP_List_Tableクラスは技術的には公式APIではなく(訳注:公開APIではなく)、
 * 遠い未来にいくつかの変更が行われる可能性があることに注意してください。
 * 変更があった場合は、参考のために最新の技術を用いてこのプラグインをすぐに更新します。
 *
 * 将来の互換性が心配な場合は、配布するプラグイン内にWP_List_Tableクラスのコピーを作成することができます。
 * (ちょうど下記に示すファイルパスのものです)
 * コピーを行う場合は、本体のクラスと名前が被るのを避けるためにクラス名の変更をすることを忘れないでください。
 *
 * このチュートリアルは近い将来にわたって最新に保つつもりなので、
 * WordPress本体から提供されるクラスで作業します。
 */
if(!class_exists('WP_List_Table')){
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
 
 
 
 
/************************** パッケージクラスの作成 *****************************
 *******************************************************************************
 * 本体のWP_List_Tableクラスを拡張して、新しい一覧テーブルパッケージを作成します。
 * WP_List_Tableはテーブルを生成するためのフレームワークの大部分が含まれていますが、
 * 独自データが正しく表示できるように、いくつかのメソッド定義およびオーバーライドが必要です。
 *
 * ページ上でこの例を表示する場合、はじめにこのクラスをインスタンス化する必要があります。
 * 次に $yourInstance->prepare_items() を呼び出してデータ操作を処理し、
 * 最後に $yourInstance->display() を呼び出してページにテーブルを描画します。
 *
 * この一覧テーブルでは映画データを扱います。
 */
class TT_Example_List_Table extends WP_List_Table {
 
    /** ************************************************************************
     * 一覧テーブルを使用する際は、通常はデータベースからデータを問い合わせて操作することになります。
     * この例では、話を単純にするために事前に生成済みの配列データを用意しました。
     * これは $wpdb->query() から返されたデータとして考えてください。
     *
     * 実際のシナリオでは、このクラスの prepare_items() メソッド内で
     * 独自の問い合わせを行うことになるでしょう。
     * @var array
     **************************************************************************/
    var $example_data = array(
            array(
                'ID'        => 1,
                'title'     => '300',
                'rating'    => 'R',
                'director'  => 'Zach Snyder'
            ),
            array(
                'ID'        => 2,
                'title'     => 'Eyes Wide Shut',
                'rating'    => 'R',
                'director'  => 'Stanley Kubrick'
            ),
            array(
                'ID'        => 3,
                'title'     => 'Moulin Rouge!',
                'rating'    => 'PG-13',
                'director'  => 'Baz Luhrman'
            ),
            array(
                'ID'        => 4,
                'title'     => 'Snow White',
                'rating'    => 'G',
                'director'  => 'Walt Disney'
            ),
            array(
                'ID'        => 5,
                'title'     => 'Super 8',
                'rating'    => 'PG-13',
                'director'  => 'JJ Abrams'
            ),
            array(
                'ID'        => 6,
                'title'     => 'The Fountain',
                'rating'    => 'PG-13',
                'director'  => 'Darren Aronofsky'
            ),
            array(
                'ID'        => 7,
                'title'     => 'Watchmen',
                'rating'    => 'R',
                'director'  => 'Zach Snyder'
            ),
            array(
                'ID'        => 8,
                'title'     => '2001',
                'rating'    => 'G',
                'director'  => 'Stanley Kubrick'
            ),
        );
 
 
    /** ************************************************************************
     * 必須。親のコンストラクタを参照して初期化を行います。
     * 親の参照を使用していくつかの設定のセットを行います。
     ***************************************************************************/
    function __construct(){
        global $status, $page;
 
        //　親のデフォルトをセット
        parent::__construct( array(
            'singular'  => 'movie',     // 一覧データの単数形の名前
            'plural'    => 'movies',    // 一覧データの複数形の名前
            'ajax'      => false        // このテーブルがAjaxをサポートしているか
        ) );
 
    }
 
    /** ************************************************************************
     * 推奨。このメソッドは、親のクラスが指定の列に対する特定の生成メソッドを
     * 見つけられないときに呼び出されます。一般的に、パッケージクラスが整理整頓された状態を
     * 保つためには、出力したい列を別々のメソッドに含めることを推奨します。
     * 例えば列名が 'title' のものの処理が必要な場合、最初に $this->column_title()
     * という名前のメソッドがあるかが最初に確認され、存在した場合はそのメソッドが使用されます。
     * 存在しない場合はこのメソッドが使用されます。
     * 可能な限りそれぞれ独自の列メソッドを使用してください。
     *
     * 今回の例ではこの後に column_title() メソッドを定義しているので、このメソッドは
     * 'title'という名前の列には関与しません。代わりに、その他すべての列を制御します。
     *
     * 列がどのように制御されるかの詳細を確認するには、WP_List_Table::single_row_columns()
     * を確認してください。
     *
     * @param array $item 単一項目 (データの1行分すべて)
     * @param array $column_name 処理対象の列の名前/スラッグ
     * @return string 列の<td>内に配置されるテキストまたはHTML
     **************************************************************************/
    function column_default($item, $column_name){
        switch($column_name){
            case 'rating':
            case 'director':
                return $item[$column_name];
            default:
                return print_r($item,true); // トラブルシューティングのために配列全体を表示
        }
    }
 
    /** ************************************************************************
    * 推奨。これは独自の列メソッドで、'title'という名前/スラッグの任意の列の出力を行う
    * 役割を担います。このクラスが列の出力を必要とするたびに column_{$column_title} という
    * メソッド名があるかが最初に確認され、存在する場合、そのメソッドが呼び出されます。
    * 存在しない場合は column_default() が代わりに呼び出されます。
    *
    * この例では、ロールオーバー操作の実装方法も併せて示します。操作は
    * 'スラッグ' => 'リンクHTML' という形式の連想配列であるべきです。
    * (このURLは自分で生成する必要があります)。
    *
    * @see WP_List_Table::::single_row_columns()
    * @param array $item 単一項目 (データの1行分すべて)
    * @return string 列の<td>内に配置されるテキストまたはHTML (映画タイトルのみ)
    **************************************************************************/
    function column_title($item){
 
        // 行の操作を生成
        $actions = array(
            'edit'      => sprintf('<a href="?page=%s&action=%s&movie=%s">編集</a>',$_REQUEST['page'],'edit',$item['ID']),
            'delete'    => sprintf('<a href="?page=%s&action=%s&movie=%s">削除</a>',$_REQUEST['page'],'delete',$item['ID']),
        );
 
        // タイトルの内容を返す
        return sprintf('%1$s <span style="color:silver">(id:%2$s)</span>%3$s',
            /*$1%s*/ $item['title'],
            /*$2%s*/ $item['ID'],
            /*$3%s*/ $this->row_actions($actions)
        );
    }
 
    /** ************************************************************************
     * チェックボックスまたは一括操作を表示する場合は必須です。
     * 「cb」列は列が処理される際に特別な取り扱いを受けます。
     * これは常に独自のメソッドを持つ必要があります。
     *
     * @see WP_List_Table::::single_row_columns()
     * @param array $item 単一項目 (データの1行分すべて)
     * @return string 列の<td>内に配置されるテキストまたはHTML
     **************************************************************************/
    function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            /*$1%s*/ $this->_args['singular'],  // テーブルの単数形ラベルを使用してみましょう ("movie")
            /*$2%s*/ $item['ID']                // チェックボックスの値はレコードのIDであるべきです
        );
    }
 
    /** ************************************************************************
     * 必須。このメソッドはテーブルの列とタイトルを指示します。このメソッドは
     * キーを列のスラッグ(class属性にもなる)、値を列のタイトルテキストとした配列を返すべきです。
     * 一括操作用のチェックボックスが必要な場合は、下記の $columns 配列を参照してください。
     *
     * この「cb」列は他とは異なる扱いを受けます。テーブルにチェックボックス列が含まれる
     * 場合は column_cb() メソッドを定義しなければなりません。
     * 一括操作またはチェックボックスが不要な場合は、単に「cb」項目を配列から取り除いてください。
     *
     * @see WP_List_Table::::single_row_columns()
     * @return array 列の情報を含む連想配列: 'スラッグ' => '表示タイトル'
     **************************************************************************/
    function get_columns(){
        $columns = array(
            'cb'        => '<input type="checkbox" />', // テキストの代わりにチェックボックスを出力
            'title'     => 'タイトル',
            'rating'    => 'レーティング',
            'director'  => 'ディレクター'
        );
        return $columns;
    }
 
    /** ************************************************************************
     * オプション。1つ以上の列を並び替え可能列(昇順・降順切替)にしたい場合、
     * ここに登録が必要になります。
     * これはキーが並び替え可能とする列、値がDB列と並び替え済かを示す配列を返すべきです。
     * キーと値は同じものになることがありますが、常に同じではありません。
     * (値はデータベースの列名で、一覧テーブルの列名ではありません)
     *
     * このメソッドでは単純に並び替え可能でクリック可能な列のみ定義すべきで、
     * 実際の並び替えは制御しません。
     * prepare_items() 内でのORDERBY、ORDERクエリ文字列変数の検出と、
     * それらに応じたデータの並び替えが必要です。(通常はSQLクエリを編集することによって)
     *
     * @return array 並び替え可能となるすべての列を含む連想配列: 'スラッグ'=>array('データ列',真偽値)
     **************************************************************************/
    function get_sortable_columns() {
        $sortable_columns = array(
            'title'     => array('title',false),     // true = すでに並び替え済み
            'rating'    => array('rating',false),
            'director'  => array('director',false)
        );
        return $sortable_columns;
    }
 
 
    /** ************************************************************************
     * オプション。一覧テーブルに一括操作を含める必要がある場合、それらを定義する場所です。
     * 一括操作は 'スラッグ'=>'表示タイトル' 形式の連想配列です。
     *
     * メソッドが空の値を返す場合、一括操作は出力されません。一括操作を指定した場合、一括操作は
     * display()でテーブルとともに自動出力されます。
     *
     * 一覧テーブルは<form>要素で自動的に囲まれることはないため、一括操作を機能させるためには
     * 手動で生成が必要なことにも注意してください。
     *
     * @return array すべての一括操作を含む連想配列: 'スラッグ'=>'表示タイトル'
     **************************************************************************/
    function get_bulk_actions() {
        $actions = array(
            'delete'    => '削除'
        );
        return $actions;
    }
 
 
    /** ************************************************************************
     * オプション. 一括操作はどこででも好きなように制御できます。
     * この例では、きれいに整理された状態を保つためにクラスで処理します。
     *
     * @see $this->prepare_items()
     **************************************************************************/
    function process_bulk_action() {
 
        // 一括操作が実行された時を検知…
        if( 'delete'===$this->current_action() ) {
            wp_die('項目が削除されました。(もし削除する項目があった場合)');
        }
 
    }
 
    /** ************************************************************************
     * 必須。これは表示のためにデータを準備する場所です。このメソッドは通常はデータベースへの
     * 照会、並び替えとフィルター、そして一般的な表示の準備に使用されます。
     * べきです。 最低限 $this->items と $this->set_pagination_args() を
     * 設定すべきですが、次のプロパティとメソッドも頻繁にこのメソッドとやり取りしています。
     *
     * @global WPDB $wpdb
     * @uses $this->_column_headers
     * @uses $this->items
     * @uses $this->get_columns()
     * @uses $this->get_sortable_columns()
     * @uses $this->get_pagenum()
     * @uses $this->set_pagination_args()
     **************************************************************************/
    function prepare_items() {
        global $wpdb; // データベースクエリを作成する場合にのみ使用されます
 
        /**
         * 最初に、1ページ内に表示するレコード数を決定します。
         */
        $per_page = 5;
 
 
        /**
         * 必須。列ヘッダーの定義が必要です。これは表示されるすべての列の
         * 配列(スラッグ & タイトル)、非表示にしておく列の一覧、並び替え可能な列の一覧が
         * 含まれます。
         * これらは (ここで行ったように) _column_headers プロパティの値の生成に使用される前に
         * それぞれ別のメソッドの中で定義できます。
         */
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
 
 
        /**
         * 必須。最後に、クラスによって使用される列ヘッダーのための配列を生成します。
         * $this->_column_headers プロパティは3つの別の配列を含む配列です。
         * 1つはすべての列、1つは非表示列、1つはソート可能列です。
         */
        $this->_column_headers = array($columns, $hidden, $sortable);
 
 
        /**
         * オプション。自由に一括操作を制御できます。
         * このケースでは、きれいに保つためパッケージ内で制御します。
         */
        $this->process_bulk_action();
 
 
        /**
         * データベースに問い合わせを行う代わりに、このプラグイン用に生成した
         * 例示データプロパティを取得します。この例示パッケージは実際に作成する
         * 可能性があるものとは多少異なります。
         * この例の中では、データの並び替えとページネーションは配列操作を使用します。
         * 実際の実装では、速く正確なクエリデータを使用するためには
         * おそらく並び替えとページネーションデータの生成に
         * 独自の問い合わせが代わりに必要です。
         */
        $data = $this->example_data;
 
 
        /**
         * これは入力を並び替えするかチェックし、配列に応じてデータの並び替えを行います。
         *
         * データベースが関係する実際のシチュエーションでは、おそらく並び替えは
         * 'orderby'と'order'の値を直接独自のクエリで処理するでしょう。
         * データは事前に並び替えられているので、この配列の並び替えは必要ありません。
         */
        function usort_reorder($a,$b){
            $orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'title'; // 並び替え指定がなければ、標準はタイトル
            $order = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'asc'; // 並び順指定がなければ、標準は昇順
            $result = strcmp($a[$orderby], $b[$orderby]); // 並び順の決定
            return ($order==='asc') ? $result : -$result; // 最後の並び替え方向をusortに送信
        }
        usort($data, 'usort_reorder');
 
 
        /***********************************************************************
         * ---------------------------------------------------------------------
         * vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv
         *
         * 実際のシチュエーションでは、ここで問い合わせを行います。
         *
         * WordPressでクエリを作成する方法については、このCodexの記事を見てください。
         * http://codex.wordpress.org/Class_Reference/wpdb
         *
         * ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
         * ---------------------------------------------------------------------
         **********************************************************************/
 
 
        /**
         * ページネーションのために必須。ユーザーが現在見ているのがどのページか把握しましょう。
         * この後必要になるので、実際のパッケージクラスには常に含める必要があります。
         */
        $current_page = $this->get_pagenum();
 
        /**
         * ページネーションのために必須。データの配列数を確認しましょう。
         * 実際に使用する際は、絞り込みをしていないデータベースの項目の総数になります。
         * この後必要になるので、実際のパッケージクラスには常に含める必要があります。
         */
        $total_items = count($data);
 
 
        /**
         * WP_List_Tableクラスはページネーションを制御しないので、現在のページのみに
         * 調整されたデータを確保することが必要です。今回は array_slice() を使います。
         */
        $data = array_slice($data,(($current_page-1)*$per_page),$per_page);
 
 
 
        /**
         * 必須。ここでitemsプロパティに並び替え済みのデータを追加すると、
         * クラスの他の部分で使うことができます。
         */
        $this->items = $data;
 
        /**
         * 必須。ページネーションのオプションと計算結果も登録します。
         */
        $this->set_pagination_args( array(
            'total_items' => $total_items,                  // 項目の総数を計算します
            'per_page'    => $per_page,                     // ページに表示する項目数を決定します
            'total_pages' => ceil($total_items/$per_page)   // ページの総数を計算します
        ) );
    }
 
 
}
 
 
 
 
 
/** ************************    テストページの登録    *****************************
 *******************************************************************************
 * 今回は管理ページの定義が必要です。この例では、トップレベルのメニュー項目を
 * 管理メニューの下部に追加します。
 */
function tt_add_menu_items(){
    add_menu_page('一覧テーブルプラグインの例', '一覧テーブル例', 'activate_plugins', 'tt_list_test', 'tt_render_list_page');
} add_action('admin_menu', 'tt_add_menu_items');
 
 
 
 
/** *************************** テストページの出力 ********************************
 *******************************************************************************
 * この関数は管理ページと例の一覧テーブルを出力します。
 * コンストラクタから prepare_items() と display() を呼び出すこともできますが、
 * これらのステップの間にロジックを含める必要があることがしばしばあるので
 * ここでは明示的にこれらのメソッドを呼び出しています。
 * これは柔軟性を保った、WordPress本体でも使用されている一覧テーブルの作法です。
 */
function tt_render_list_page(){
 
    //パッケージクラスのインスタンスを生成...
    $testListTable = new TT_Example_List_Table();
    //データの取得、準備、並び替え、絞り込み...
    $testListTable->prepare_items();
 
    ?>
    <div class="wrap">
 
        <div id="icon-users" class="icon32"><br/></div>
        <h2>一覧テーブルのテスト</h2>
 
        <div style="background:#ECECEC;border:1px solid #CCC;padding:0 10px;margin-top:5px;border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;">
            <p>このページはプラグイン内で<tt><a href="http://codex.wordpress.org/Class_Reference/WP_List_Table" target="_blank" style="text-decoration:none;">WP_List_Table</a></tt>クラスを使用したデモです。</p>
            <p>プラグイン内で<tt><a href="http://codex.wordpress.org/Class_Reference/WP_List_Table" target="_blank" style="text-decoration:none;">WP_List_Table</a></tt>クラスを使用する詳細な説明は、
            <a href="<?php echo admin_url( 'plugin-editor.php?plugin='.plugin_basename(__FILE__) ); ?>" style="text-decoration:none;">プラグインエディタ</a>で表示するか、任意のPHPエディタでこのファイル <tt style="color:gray;"><?php echo __FILE__ ?></tt> を開きます。</p>
            <p>クラスのその他の詳細は<a href="http://codex.wordpress.org/Class_Reference/WP_List_Table" target="_blank" style="text-decoration:none;">WordPress Codex</a>で利用可能です。</p>
        </div>
 
        <!-- フォームは自動的には生成されません。一括操作のような機能を使用するにはテーブルを<form>で囲う必要があります。 -->
        <form id="movies-filter" method="get">
            <!-- プラグインのために、フォーム送信から現在のページに戻るための保証が必要です。 -->
            <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
            <!-- 完成した一覧テーブルを出力できます。 -->
            <?php $testListTable->display() ?>
        </form>
 
    </div>
    <?php
}
