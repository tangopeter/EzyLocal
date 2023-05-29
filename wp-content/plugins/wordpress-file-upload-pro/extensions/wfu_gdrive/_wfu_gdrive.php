<?php

require_once ABSWPFILEUPLOAD_DIR . WFU_AUTOLOADER_PHP50600;

function wfu_gdrive_authorize_app_start() {
	WFU_USVAR_store('wfu_GDrive_Client', null);

	$state = wfu_create_random_string(16);
	$GClient = wfu_gdrive_getGClient_basic($state);
	$authorizeUrl = $GClient->createAuthUrl();

	die("wfu_gdrive_authorize_app_start:success:".wfu_plugin_encode_string($authorizeUrl));
}

function wfu_gdrive_authorize_app_finish($state, $authCode) {
	wfu_tf_LOG("wfu_gdrive_authorize_app_finish_start:");
	$GClient = wfu_gdrive_getGClient_basic($state);
	wfu_gdrive_add_proxy_support($GClient);
	$authCode = trim($authCode);
	$accessToken = $GClient->fetchAccessTokenWithAuthCode($authCode);
	$refreshToken = $GClient->getRefreshToken();
	if ( $refreshToken == null ) {
		wfu_gdrive_revoke_authorization($accessToken);
		wfu_update_setting('gdrive_accesstoken', '');
		wfu_tf_LOG("wfu_gdrive_authorize_app_finish:refreshtoken_null");
		die("wfu_gdrive_authorize_app_finish:repeat:");
	}
	else {
		wfu_update_setting('gdrive_accesstoken', json_encode($accessToken));
		// reset any unread admin notifications related to GDrive activation
		wfu_reset_gdriveactivation_notification();
		wfu_tf_LOG("wfu_gdrive_authorize_app_finish:new_accesstoken_ok");
		die("wfu_gdrive_authorize_app_finish:success:");
	}
}

function wfu_gdrive_revoke_authorization($accessToken = null) {
	wfu_tf_LOG("gdrive_revoke_start:");
	$state = wfu_create_random_string(16);
	$GClient = wfu_gdrive_getGClient_basic($state);
	if ( $accessToken == null ) {
		$plugin_options = wfu_decode_plugin_options(get_option( "wordpress_file_upload_options" ));
		$accessToken = json_decode($plugin_options['gdrive_accesstoken'], true);
	}
	$GClient->revokeToken($accessToken);
	wfu_update_setting('gdrive_accesstoken', "");
	wfu_tf_LOG("gdrive_revoke_end:");
}

function wfu_gdrive_upload_file($filepath, $destination, $params) {
	$a = func_get_args(); $a = WFU_FUNCTION_HOOK(__FUNCTION__, $a, $out); if (isset($out['vars'])) foreach($out['vars'] as $p => $v) $$p = $v; switch($a) { case 'R': return $out['output']; break; case 'D': die($out['output']); }
	wfu_tf_LOG("gdrive_transfer_file_start:".$filepath);
	$fileid = $params["fileid"];
	$jobid = $params["jobid"];
	//get service
	try {
		$GClient = wfu_gdrive_getGClient();
		$GService = new Google_Service_Drive($GClient);
	}
	catch (Exception $ex) {
		wfu_tf_LOG("gdrive_transfer_file_end:service_fail");
		wfu_set_transfer_result($fileid, $jobid, "gdrive", false, $ex->getMessage(), "");
		return false;
	}
	wfu_tf_LOG("gdrive_transfer_file_gservice_ok");
	//locate destination folder id
	try {
		$folderid = wfu_locate_destination_id($destination, $GService);
	}
	catch (Exception $ex) {
		wfu_tf_LOG("gdrive_transfer_file_end:destination_fail");
		// check whether Google activation needs to be revoked because the
		// credentials are no longer valid
		wfu_revoke_if_auth_error($ex);
		wfu_set_transfer_result($fileid, $jobid, "gdrive", false, $ex->getMessage(), "");
		return false;
	}
	wfu_tf_LOG("gdrive_transfer_file_destination_ok:".$folderid);
	//trash duplicates if this option is enabled
	if ( isset($params["trash_duplicates"]) ) {
		try {
			wfu_gdrive_trash_duplicates(wfu_basename($filepath), $folderid, wfu_mime_content_type($filepath), $GService);
		}
		catch (Exception $ex) {
			wfu_tf_LOG("gdrive_transfer_file_end:duplicates_trashing_fail");
			wfu_set_transfer_result($fileid, $jobid, "gdrive", false, $ex->getMessage(), "");
			return false;
		}
		//remove trash duplicates flag from params
		unset($params["trash_duplicates"]);
	}
	//add leading and trailing slashes in destination if they do not exist
	if ( substr($destination, 0, 1) != '/' ) $destination = '/'.$destination;
	if ( substr($destination, -1) != '/' ) $destination .= '/';
	$destfile = $destination.wfu_basename($filepath);
	$params["destfile"] = $destfile;
	
	if ( wfu_filesize($filepath, "wfu_gdrive_upload_file") > WFU_VAR("WFU_GDRIVE_CHUNKED_UPLOAD_THRESHOLD") ) {

		$GClient->setDefer(true);		
		try {
			wfu_gdrive_chunked_upload_file($filepath, $folderid, $params, $GService);
		}
		catch (Exception $ex) {
			wfu_tf_LOG("gdrive_transfer_file_end:upload_fail");
			wfu_set_transfer_result($fileid, $jobid, "gdrive", false, $ex->getMessage(), "");
			return false;
		}
	}
	else {
		$metadata = null;
		try {
			$metadata = wfu_gdrive_simple_upload_file($filepath, $folderid, $params, $GService);
		}
		catch (Exception $ex) {
			wfu_tf_LOG("gdrive_transfer_file_end:upload_fail");
			wfu_set_transfer_result($fileid, $jobid, "gdrive", false, $ex->getMessage(), "");
			return false;
		}
		wfu_set_transfer_result($fileid, $jobid, "gdrive", true, "", $filepath, $metadata);
	}
	
	wfu_tf_LOG("gdrive_transfer_file_end");
	return false;
}

function wfu_gdrive_getGClient_basic($state) {
	$secret = wfu_get_gdrive_secret($state);
	if ( $secret === false ) return null;
	$config = json_decode($secret, true);

	$GClient = null;
	$GClient = new Google_Client();
	$GClient->setApplicationName('Google Drive API PHP Quickstart');
	$GClient->setScopes(Google_Service_Drive::DRIVE);
	$GClient->setAuthConfig($config);
	$GClient->setState($state);
	$plugin_options = wfu_decode_plugin_options(get_option( "wordpress_file_upload_options" ));
	$url = ( $plugin_options["altserver"] == "1" && trim(WFU_VAR("WFU_ALT_IPTANUS_SERVER")) != "" ? ( trim(WFU_VAR("WFU_ALT_GDRIVE_SERVER")) != "" ? trim(WFU_VAR("WFU_ALT_GDRIVE_SERVER")) : trim(WFU_VAR("WFU_ALT_IPTANUS_SERVER")).'/wp-admin/admin-ajax.php' ) : WFU_GDRIVE_SERVER_URL );
	$GClient->setRedirectUri($url.'?action=wfuca_google_oauth2callback');
	$GClient->setAccessType('offline');
	$GClient->setApprovalPrompt('force');
	
	return $GClient;
}

function wfu_gdrive_add_proxy_support(&$GClient) {
	//include proxy support
	$http_client = $GClient->getHttpClient();
	$http_client_config = $http_client->getConfig();
	if ( wfu_add_proxy_param($http_client_config) ) {
		$http_client = new GuzzleHttp\Client($http_client_config);
		$GClient->setHttpClient($http_client);
	}
}

function wfu_gdrive_getGClient() {
	wfu_tf_LOG("wfu_gdrive_getGClient_start:");
	$state = wfu_create_random_string(16);
	$GClient = wfu_gdrive_getGClient_basic($state);
	wfu_gdrive_add_proxy_support($GClient);
	$plugin_options = wfu_decode_plugin_options(get_option( "wordpress_file_upload_options" ));
	$accessToken = json_decode($plugin_options['gdrive_accesstoken'], true);
	$GClient->setAccessToken($accessToken);
	if ($GClient->isAccessTokenExpired()) {
		wfu_tf_LOG("wfu_gdrive_getGClient:refresh_token");
		$refreshTokenSaved = $GClient->getRefreshToken();
		if ( $refreshTokenSaved == null ) {
			wfu_gdrive_revoke_authorization();
			wfu_tf_LOG("wfu_gdrive_getGClient_end:null");
			return null;
		}
		$GClient->fetchAccessTokenWithRefreshToken($refreshTokenSaved);
		$accessTokenUpdated = $GClient->getAccessToken();
		$accessTokenUpdated['refresh_token'] = $refreshTokenSaved;
		$GClient->setAccessToken($accessTokenUpdated);
		wfu_update_setting('gdrive_accesstoken', json_encode($accessTokenUpdated));
	}
	
	wfu_tf_LOG("wfu_gdrive_getGClient_end:ok");
	return $GClient;
}

function wfu_get_gdrive_secret($state) {
	$a = func_get_args(); $a = WFU_FUNCTION_HOOK(__FUNCTION__, $a, $out); if (isset($out['vars'])) foreach($out['vars'] as $p => $v) $$p = $v; switch($a) { case 'R': return $out['output']; break; case 'D': die($out['output']); }
	$plugin_options = wfu_decode_plugin_options(get_option( "wordpress_file_upload_options" ));
	$postfields = array();
	$postfields['action'] = 'wfuca_get_gdrive_secret_new';
	$postfields['version_hash'] = WFU_VERSION_HASH;
	$postfields['state'] = $state;
	$postfields['redirect_url'] = wfu_ajaxurl()."?action=wfu_google_oauth2callback";
	$url = ( $plugin_options["altserver"] == "1" && trim(WFU_VAR("WFU_ALT_IPTANUS_SERVER")) != "" ? ( trim(WFU_VAR("WFU_ALT_GDRIVE_SERVER")) != "" ? trim(WFU_VAR("WFU_ALT_GDRIVE_SERVER")) : trim(WFU_VAR("WFU_ALT_IPTANUS_SERVER")).'/wp-admin/admin-ajax.php' ) : WFU_GDRIVE_SERVER_URL );
	$result = wfu_post_request($url, $postfields, false);
	$matches = array();
	if ( preg_match("/wfuca_gdrive_secret:(.*)$/", $result, $matches) != 1 ) return false;
	if ( !isset($matches[1]) || $matches[1] == "" ) return false;
	return wfu_plugin_decode_string($matches[1]);
}

function wfu_revoke_if_auth_error($error) {
	wfu_tf_LOG("reset_if_auth_error_start:");
	$code = $error->getCode();
	$err = $error->getMessage();
	if ( $code == 401 || ( $code == 400 && strpos($err, "invalid_grant") !== false ) ) {
		wfu_gdrive_revoke_authorization();
		// notify admin about the revocation
		wfu_add_gdriveactivation_notification();
		wfu_tf_LOG("reset_if_auth_error:activation_reset");
	}
	wfu_tf_LOG("reset_if_auth_error_end:");
}

function wfu_add_gdriveactivation_notification() {
	$a = func_get_args(); $a = WFU_FUNCTION_HOOK(__FUNCTION__, $a, $out); if (isset($out['vars'])) foreach($out['vars'] as $p => $v) $$p = $v; switch($a) { case 'R': return $out['output']; break; case 'D': die($out['output']); }
	wfu_tf_LOG("wfu_add_gdriveactivation_notification_start:");
	$action = array(
		'title' => 'Google Drive Activation',
		'link' => site_url().'/wp-admin/options-general.php?page=wordpress_file_upload&action=plugin_settings#wfu_gdrive_settings'
	);
	wfu_add_nr_admin_notification('Google Drive is disconnected from Wordpress File Upload plugin, however there are pending transfers to Google Drive. You need to activate it.', 'warning', 'google_activation', 'Google Drive requires activation.', $action);
	wfu_tf_LOG("wfu_add_gdriveactivation_notification_end:");
}

function wfu_reset_gdriveactivation_notification() {
	$a = func_get_args(); $a = WFU_FUNCTION_HOOK(__FUNCTION__, $a, $out); if (isset($out['vars'])) foreach($out['vars'] as $p => $v) $$p = $v; switch($a) { case 'R': return $out['output']; break; case 'D': die($out['output']); }
	wfu_tf_LOG("wfu_reset_gdriveactivation_notification_start:");
	$notfs = wfu_get_admin_notifications('unread', null, 'google_activation');
	$keys = array();
	foreach ( $notfs as $notf ) array_push($keys, $notf['id']);
	wfu_mark_notifications($keys, 'read');
	wfu_tf_LOG("wfu_reset_gdriveactivation_notification_end:");
}