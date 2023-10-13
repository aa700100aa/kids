<?php
function theme_plugins_url( $path = '', $plugin = '' ) {
    $THEME_PLUGIN_DIR = dirname(__FILE__);
    $THEME_PLUGIN_URL = get_stylesheet_directory_uri().'/functions/plugins';
    $path = wp_normalize_path( $path );
    $plugin = wp_normalize_path( $plugin );
    $mu_plugin_dir = wp_normalize_path( $THEME_PLUGIN_DIR );
    if ( !empty($plugin) && 0 === strpos($plugin, $mu_plugin_dir) )
            $url = $THEME_PLUGIN_URL;
    else
            $url = $THEME_PLUGIN_URL;
    $url = set_url_scheme( $url );
    if ( !empty($plugin) && is_string($plugin) ) {
            $folder = dirname(theme_plugin_basename($plugin));
            if ( '.' != $folder )
                    $url .= '/' . ltrim($folder, '/');
    }
    if ( $path && is_string( $path ) )
            $url .= '/' . ltrim($path, '/');
    //return apply_filters( 'plugins_url', $url, $path, $plugin );
    return $url;
}
function theme_plugin_basename( $file ) {
    global $wp_plugin_paths;
    $THEME_PLUGIN_DIR = dirname(__FILE__);
    $THEME_PLUGIN_URL = get_stylesheet_directory_uri().'/functions/plugins';

    foreach ( $wp_plugin_paths as $dir => $realdir ) {
            if ( strpos( $file, $realdir ) === 0 ) {
                    $file = $dir . substr( $file, strlen( $realdir ) );
            }
    }

    $file = wp_normalize_path( $file );
    $plugin_dir = wp_normalize_path( $THEME_PLUGIN_DIR );
    $mu_plugin_dir = wp_normalize_path( $THEME_PLUGIN_DIR );

    $file = preg_replace('#^' . preg_quote($plugin_dir, '#') . '/|^' . preg_quote($mu_plugin_dir, '#') . '/#','',$file); // get relative path from plugins dir
    $file = trim($file, '/');
    return $file;
}