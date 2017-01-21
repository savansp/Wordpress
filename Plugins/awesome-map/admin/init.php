<?php

add_action('admin_menu', 'awsmap_create_menu');

function awsmap_create_menu() {

    //create new top-level menu 

    add_menu_page('Locations', 'Locations', 'administrator', __FILE__, 'awsmap_admin_home', 'dashicons-location');

    add_submenu_page(__FILE__, 'Awesome Map Apperiance', 'Apperiance', 'administrator', __FILE__ . '/admin_appearance.php', 'awsmap_admin_appearance');
    add_submenu_page(__FILE__, 'Awesome Map Settings', 'Settings', 'administrator', __FILE__ . '/admin_setting.php', 'awsmap_admin_setting');
}

require_once(CUSTOM_ABSPATH . 'admin/admin_home.php');
require_once(CUSTOM_ABSPATH . 'admin/admin_appearance.php');
require_once(CUSTOM_ABSPATH . 'admin/admin_setting.php');