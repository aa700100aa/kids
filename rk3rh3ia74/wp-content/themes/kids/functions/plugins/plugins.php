<?php
class myPluginDirSearch {
    private $dir = '';
    function __construct(){
        //独自関数
        require_once(get_template_directory().'/functions/plugins/functions.php');
        //現在のディレクトリ取得
        $this->dir = dirname(__FILE__);
        //ディレクトリー内にあるプラグインを実行
        $this->_ini();
    }
    function _ini(){
        require_once(ABSPATH.'wp-admin/includes/plugin.php');
        $plugin_files = array();
        $wp_plugins = array();
        if($handle01 = opendir($this->dir)){
            while(false !== ($dir = readdir($handle01))){
                if($dir != "." && $dir != ".." && is_dir("{$this->dir}/{$dir}")){
                    $handle02 = opendir("{$this->dir}/{$dir}");
                    while(false !== ($file = readdir($handle02))){
                        if($file == "." || $file == "..")continue;
                        if(is_dir("{$this->dir}/{$file}")){
                            $handle03 = opendir("{$this->dir}/{$dir}/{$file}");
                            while(false !== ($file2 = readdir($handle03))){
                                if($file2 == "." || $file2 == ".." || is_dir("{$this->dir}/{$dir}/{$file}/{$file2}"))continue;
                                if(is_file("{$this->dir}/{$dir}/{$file}/{$file2}") && pathinfo("{$this->dir}/{$dir}/{$file}/{$file2}", PATHINFO_EXTENSION) == 'php'){
                                    $plugin_files[$dir][] = "{$this->dir}/{$dir}/{$file}/{$file2}";
                                }
                            }
                            closedir($handle03);
                        }elseif(is_file("{$this->dir}/{$dir}/{$file}") && pathinfo("{$this->dir}/{$dir}/{$file}", PATHINFO_EXTENSION) == 'php'){
                            $plugin_files[$dir][] = "{$this->dir}/{$dir}/{$file}";
                        }
                    }
                    closedir($handle02);
                }
            }
            closedir($handle01);
            foreach ( $plugin_files as $plugin_name => $files ) {
                foreach($files as $plugin_file){
                    if(!is_readable($plugin_file))continue;
                    $plugin_data = $this->get_plugin_data($plugin_file, false, false);
                    if(empty($plugin_data['Name']))continue;
                    $wp_plugins[$plugin_file] = $plugin_data;
                }
            }
            foreach($wp_plugins as $plugin_file => $plugin_data){
                require_once($plugin_file);
            }
        }
    }
    function get_plugin_data( $plugin_file, $markup = true, $translate = true ) { 
        $default_headers = array( 
            'Name' => 'Plugin Name',  
            'PluginURI' => 'Plugin URI',  
            'Version' => 'Version',  
            'Description' => 'Description',  
            'Author' => 'Author',  
            'AuthorURI' => 'Author URI',  
            'TextDomain' => 'Text Domain',  
            'DomainPath' => 'Domain Path',  
            'Network' => 'Network',  
            // Site Wide Only is deprecated in favor of Network. 
            '_sitewide' => 'Site Wide Only',  
        ); 
        $plugin_data = get_file_data( $plugin_file, $default_headers, 'plugin' ); 
        // Site Wide Only is the old header for Network 
        if ( ! $plugin_data['Network'] && $plugin_data['_sitewide'] ){
            /** translators: 1: Site Wide Only: true, 2: Network: true */ 
            _deprecated_argument( __FUNCTION__, '3.0', sprintf( __( 'The %1$s plugin header is deprecated. Use %2$s instead.' ), '<code>Site Wide Only: true</code>', '<code>Network: true</code>' ) ); 
            $plugin_data['Network'] = $plugin_data['_sitewide']; 
        }
        $plugin_data['Network'] = ( 'true' == strtolower( $plugin_data['Network'] ) );
        unset( $plugin_data['_sitewide'] ); 
        if ( $markup || $translate ) {
            $plugin_data = _get_plugin_data_markup_translate( $plugin_file, $plugin_data, $markup, $translate ); 
        } else { 
            $plugin_data['Title'] = $plugin_data['Name']; 
            $plugin_data['AuthorName'] = $plugin_data['Author']; 
        } 
        return $plugin_data; 
    }
}
$myPluginDirSearch = new myPluginDirSearch;
