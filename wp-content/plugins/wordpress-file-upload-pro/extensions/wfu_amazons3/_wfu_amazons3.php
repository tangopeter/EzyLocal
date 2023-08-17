<?php

require_once WFU_AMAZONS3_ABSDIR . 'vendor/autoload.php';
use Aws\Iam\IamClient; 
use Aws\S3\S3MultiRegionClient;
use Aws\S3\MultipartUploader;
use Aws\Exception\AwsException;
use Aws\Exception\MultipartUploadException;
use Aws\Command;

function wfu_amazons3_authorize_app_finish($publickey, $privatekey) {
	$a = func_get_args(); $a = WFU_FUNCTION_HOOK(__FUNCTION__, $a, $out); if (isset($out['vars'])) foreach($out['vars'] as $p => $v) $$p = $v; switch($a) { case 'R': return $out['output']; break; case 'D': die($out['output']); }
	$args = array(
		'version'     => WFU_VAR("WFU_AMAZONS3_VERSION"),
		'region'      => WFU_VAR("WFU_AMAZONS3_REGION"),
		'credentials' => array(
			'key'    => $publickey,
			'secret' => $privatekey,
		),
		'use_aws_shared_config_files' => false
	);
	$args = apply_filters("_wfu_amazons3_iamclient_args", $args);
	$client = new IamClient($args);
	wfu_update_setting('amazons3_publickey', "");
	wfu_update_setting('amazons3_privatekey', "");
	$result = "Credentials are invalid!";
	try {
		$result = $client->listAccessKeys();
		if ( isset($result["AccessKeyMetadata"]) ) {
			foreach ( $result["AccessKeyMetadata"] as $item ) {
				if ( $item["AccessKeyId"] == $publickey ) {
					wfu_update_setting('amazons3_publickey', $publickey);
					wfu_update_setting('amazons3_privatekey', $privatekey);
					$result = "success";
					wfu_reset_amazons3activation_notification();
					break;
				}
			}
		}
	}
	catch (Exception $ex) {}
	return $result;
}

function wfu_amazons3_upload_file($filepath, $bucket, $destination, $params) {
	$a = func_get_args(); $a = WFU_FUNCTION_HOOK(__FUNCTION__, $a, $out); if (isset($out['vars'])) foreach($out['vars'] as $p => $v) $$p = $v; switch($a) { case 'R': return $out['output']; break; case 'D': die($out['output']); }
	wfu_tf_LOG("amazons3_transfer_file_start:".$filepath);
	$fileid = $params["fileid"];
	$jobid = $params["jobid"];
	//get client
	$s3Client = wfu_amazons3_get_S3Client();
	if ( $s3Client == null ) {
		wfu_tf_LOG("amazons3_transfer_file_end:service_fail");
		wfu_set_transfer_result($fileid, $jobid, "amazons3", false, 'service_fail', '');
		return false;		
	}
	//add trailing slashes in destination if they do not exist, remove any
	//trailing slashes
	if ( substr($destination, 0, 1) == '/' ) $destination = substr($destination, 1);
	if ( substr($destination, -1) != '/' ) $destination .= '/';
	$destfile = $destination.wfu_basename($filepath);
	//$params["destfile"] = $destfile;
	
	if ( wfu_filesize($filepath, "wfu_amazons3_upload_file") > WFU_VAR("WFU_AMAZONS3_MULTIPART_UPLOAD_THRESHOLD") ) {
		try {
			wfu_amazons3_multipart_upload_file($filepath, $bucket, $destfile, $params, $s3Client);
		}
		catch (Exception $ex) {
			wfu_tf_LOG("amazons3_transfer_file_end:upload_fail");
			return false;
		}
	}
	else {
		$metadata = null;
		try {
			$metadata = wfu_amazons3_simple_upload_file($filepath, $bucket, $destfile, $params, $s3Client);
		}
		catch (Exception $ex) {
			wfu_tf_LOG("amazons3_transfer_file_end:upload_fail");
			wfu_set_transfer_result($fileid, $jobid, "amazons3", false, $ex->getMessage(), "");
			return false;
		}
		wfu_set_transfer_result($fileid, $jobid, "amazons3", true, "", $filepath, $metadata);
	}
	
	wfu_tf_LOG("amazons3_transfer_file_end");
	return false;
}

function wfu_amazons3_get_S3Client() {
	$plugin_options = wfu_decode_plugin_options(get_option( "wordpress_file_upload_options" ));
	$publickey = $plugin_options['amazons3_publickey'];
	$privatekey = $plugin_options['amazons3_privatekey'];
	if ( $publickey == "" ) return null;
	$args = array(
		'version'     => WFU_VAR("WFU_AMAZONS3_VERSION"),
		'region'      => WFU_VAR("WFU_AMAZONS3_REGION"),
		'credentials' => array(
			'key'    => $publickey,
			'secret' => $privatekey,
		),
		'use_aws_shared_config_files' => false
	);
	$s3Client = null;
	$args = apply_filters("_wfu_amazons3_s3client_args", $args);
	try {
		$s3Client = new S3MultiRegionClient($args);
	}
	catch (Exception $ex) {
		wfu_tf_LOG("amazons3_get_S3Client_error: ".$ex->getMessage());
	}
	return $s3Client;
}

function wfu_add_amazons3activation_notification() {
	$a = func_get_args(); $a = WFU_FUNCTION_HOOK(__FUNCTION__, $a, $out); if (isset($out['vars'])) foreach($out['vars'] as $p => $v) $$p = $v; switch($a) { case 'R': return $out['output']; break; case 'D': die($out['output']); }
	wfu_tf_LOG("wfu_add_amazons3activation_notification_start:");
	$action = array(
		'title' => 'Amazon S3 Activation',
		'link' => site_url().'/wp-admin/options-general.php?page=wordpress_file_upload&action=plugin_settings#wfu_amazons3_settings'
	);
	wfu_add_nr_admin_notification('Amazon S3 is disconnected from Wordpress File Upload plugin, however there are pending transfers to Amazon S3. You need to activate it.', 'warning', 'amazons3_activation', 'Amazon S3 requires activation.', $action);
	wfu_tf_LOG("wfu_add_amazons3activation_notification_end:");
}

function wfu_reset_amazons3activation_notification() {
	$a = func_get_args(); $a = WFU_FUNCTION_HOOK(__FUNCTION__, $a, $out); if (isset($out['vars'])) foreach($out['vars'] as $p => $v) $$p = $v; switch($a) { case 'R': return $out['output']; break; case 'D': die($out['output']); }
	wfu_tf_LOG("wfu_reset_amazons3activation_notification_start:");
	$notfs = wfu_get_admin_notifications('unread', null, 'amazons3_activation');
	$keys = array();
	foreach ( $notfs as $notf ) array_push($keys, $notf['id']);
	wfu_mark_notifications($keys, 'read');
	wfu_tf_LOG("wfu_reset_amazons3activation_notification_end:");
}