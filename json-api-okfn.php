<?php
/*
Plugin Name: JSON API for OKFN
Plugin URI: http://github.com/okfn/wordpress-json-api-okfn
Description: Extends the JSON API plugin to provide a JSON API on OKFN user data from BuddyPress/WordPress. 
Version: 1.0
Author: Tom Rees
Author URI: http://zephod.com
License: MIT
*/

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
define ('JSON_API_OKFN_HOME', dirname(__FILE__));

if (!is_plugin_active('buddypress/bp-loader.php')) {
    add_action( 'admin_notices', 'draw_notice_buddypress');
    return;
}

if (!is_plugin_active('json-api/json-api.php')) {
    add_action( 'admin_notices', 'draw_notice_json_api');
    return;
}

add_filter('json_api_controllers', 'addJsonApiController');
add_filter('json_api_okfn_controller_path', 'setOkfnControllerPath');
/* load_plugin_textdomain('json-api-okfn', false, basename( dirname( __FILE__ ) ) . '/languages' );*/

function draw_notice_buddypress() {
	echo '<div id="message" class="error fade"><p style="line-height: 150%">';
        _e('<strong>JSON API for OKFN</strong></a> requires the BuddyPress plugin to be activated. Please <a href="http://buddypress.org">install / activate BuddyPress</a> first, or <a href="plugins.php">deactivate JSON API for OKFN</a>.', 'json-api-okfn');
	echo '</p></div>';
}

function draw_notice_json_api(){
    echo '<div id="message" class="error fade"><p style="line-height: 150%">';
    _e('<strong>JSON API for OKFN</strong></a> requires the JSON API plugin to be activated. Please <a href="http://wordpress.org/extend/plugins/json-api/installation/">install / activate the JSON API plugin</a> first, or <a href="plugins.php">deactivate JSON API for OKFN</a>.', 'json-api-okfn');
    echo '</p></div>';
}

function addJsonApiController($aControllers) {
  $aControllers[] = 'Okfn';
  return $aControllers;
}

function setOkfnControllerPath($sDefaultPath) {
    return dirname(__FILE__).'/controllers/okfn.php';
}
