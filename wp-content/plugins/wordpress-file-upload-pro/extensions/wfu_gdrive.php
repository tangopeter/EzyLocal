<?php
/*
Plugin: Wordpress File Upload
Slug: gdrive
Name: Google Drive
Description: Enable uploaded files to be stored on a Google Drive account.
Version: 1.0.0
Author: Nickolas Bossinas
Author URI: https://www.iptanus.com/nickolas

Google Drive (Wordpress File Upload Extension)
Copyright (C) 2010-2023 Nickolas Bossinas
Contact me at https://www.iptanus.com/contact

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.
*/


DEFINE("WFU_GDRIVE_DIR", WPFILEUPLOAD_DIR.'extensions/wfu_gdrive/');
DEFINE("WFU_GDRIVE_ABSDIR", ABSWPFILEUPLOAD_DIR.'extensions/wfu_gdrive/');

add_filter('_wfu_plugin_extensions', 'wfu_gdrive_plugin_extension', 10, 2);

function wfu_gdrive_get_info($headers = null) {
	if ( $headers === null ) $headers = array( 'code' => 'Slug', 'name' => 'Name', 'description' => 'Description' );
	return get_file_data( __FILE__, $headers );
}

function wfu_gdrive_plugin_extension($extensions, $headers) {
	array_push($extensions, wfu_gdrive_get_info($headers));
	return $extensions;
}

function wfu_check_load_gdrive() {
	global $WFU_PLUGIN_EXTENSIONS;
	if ( !isset($WFU_PLUGIN_EXTENSIONS) || !is_array($WFU_PLUGIN_EXTENSIONS) || !array_key_exists("gdrive", $WFU_PLUGIN_EXTENSIONS) || $WFU_PLUGIN_EXTENSIONS["gdrive"] !== "0" ) {
		include_once WFU_GDRIVE_ABSDIR.'wfu_gdrive_constants.php'; 
		include_once WFU_GDRIVE_ABSDIR.'wfu_gdrive_settings.php'; 
		include_once WFU_GDRIVE_ABSDIR.'wfu_gdrive_attributes.php'; 
		include_once WFU_GDRIVE_ABSDIR.'wfu_gdrive_admin_browser.php'; 
		include_once WFU_GDRIVE_ABSDIR.'wfu_gdrive_maintenance.php'; 
		include_once WFU_GDRIVE_ABSDIR.'wfu_gdrive_ajaxactions.php'; 
		include_once WFU_GDRIVE_ABSDIR.'wfu_gdrive_admin.php'; 
		include_once WFU_GDRIVE_ABSDIR.'wfu_gdrive_transfers.php'; 
		include_once WFU_GDRIVE_ABSDIR.'wfu_gdrive_functions.php';
	}
}

wfu_check_load_gdrive();