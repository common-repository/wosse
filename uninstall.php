<?php
/*
* Eklenti Kaldırma PRogramı
*/

if(!defined('WP_UNINSTALL_PLUGIN')){
	die;
	exit;
	
}

global $wpdb;

$wpdb->query("DELETE FROM `wp_options` WHERE `option_name` LIKE 'HMYWSK_%'");