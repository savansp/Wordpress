<?php
/**
 * Plugin Name: Awsome Map
 * Description: Google map with Awsome Feature.user friendly customizable and beautiful map generator.
 * Version: 1.1.1
 * Author: Celestial Developers
 */

register_activation_hook( __FILE__, 'awsome_map_activate' );
function awsome_map_activate() {

    register_setting('awsmap-settings-group', '_awsmap_locations');

    register_setting('awsmap-settings-group', '_awsmap_hue');
    register_setting('awsmap-settings-group', '_awsmap_grayscale');
    register_setting('awsmap-settings-group', '_awsmap_light');
    register_setting('awsmap-settings-group', '_awsmap_zoom');
    register_setting('awsmap-settings-group', '_awsmap_marker');
    register_setting('awsmap-settings-group', '_awsmap_max_height');
    register_setting('awsmap-settings-group', '_awsmap_max_width');
    register_setting('awsmap-settings-group', '_awsmap_scroll');
    register_setting('awsmap-settings-group', '_awsmap_overlay');
    register_setting('awsmap-settings-group', '_awsmap_street');
    register_setting('awsmap-settings-group', '_awsmap_dreggable');
    register_setting('awsmap-settings-group', '_awsmap_infowindow');
    
    $marker_default= plugin_dir_url(__FILE__) . 'dist/img/marker-default.jpg';
    
    update_option('_awsmap_marker', $marker_default);
    
    update_option('_awsmap_scroll', 'true');
    update_option('_awsmap_dreggable', 'true');
    update_option('_awsmap_max_width', '1600px');
    update_option('_awsmap_max_height', '400px');
    update_option('_awsmap_infowindow', 'true');
    update_option('_awsmap_street', 'false');
 
 
}
register_uninstall_hook( __FILE__, 'awsome_map_uninstall' );
function awsome_map_uninstall() {
    
  delete_option( '_awsmap_locations' );
  delete_option( '_awsmap_hue' );
  delete_option( '_awsmap_grayscale' );
  delete_option( '_awsmap_light' );
  delete_option( '_awsmap_zoom' );
  delete_option( '_awsmap_marker' );
  delete_option( '_awsmap_max_height' );
  delete_option( '_awsmap_max_width' );
  delete_option( '_awsmap_scroll' );
  delete_option( '_awsmap_overlay' );
  delete_option( '_awsmap_street' );
  delete_option( '_awsmap_dreggable' );
  delete_option( '_awsmap_infowindow' );
  
}
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'add_action_links' );

function add_action_links ( $links ) {
 $mylinks = array(
 '<a href="' . admin_url( 'admin.php?page=awesome-map/admin/init.php/admin_setting.php' ) . '">Settings</a>',
 );
return array_merge( $links, $mylinks );
}

defined( 'CUSTOM_ABSPATH' ) || define( 'CUSTOM_ABSPATH', plugin_dir_path( __FILE__ ) );


require_once(CUSTOM_ABSPATH . 'lib/function.php');
require_once(CUSTOM_ABSPATH . 'admin/init.php');