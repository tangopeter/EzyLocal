<?php

/**
 * Plugin Name: Eazy Prints Plugin
 * Description: This plugin contains Eazy Prints custom code
 * Author: Peter Williamson
 * Version: 0.2
 **/
?>
<?php
wp_enqueue_script('jquery');

include_once 'ezyUpdateDB.php';
include_once 'orderFunctions.php';
include_once 'ezyDrawTables.php';
include_once 'ezyPluginFunctions.php';
include_once 'ezyDBfunctions.php';
include_once 'ajax.php';
include_once 'completeOrder.php';

// Custom script with no dependencies, enqueued in the header 
// https://developer.wordpress.org/reference/functions/plugin_dir_url/
function my_scripts()
{
  wp_register_script('start', plugin_dir_url(__FILE__) . '/js/js.js', array('jquery'), null, true);
  wp_enqueue_script('start');
  wp_register_script('acf', plugin_dir_url(__FILE__) . '/assets/build/js/acf-input.js', array('acf-input'));
  wp_enqueue_script('acf');
}
add_action('wp_enqueue_scripts', 'my_scripts');


function my_scripts2()
{

  wp_register_script('testy-js', plugin_dir_url(__FILE__) . 'js/testy.js', array('jquery'), null, true);
  wp_enqueue_script('testy-js');
  wp_register_script('ezyFunctions-js', plugin_dir_url(__FILE__) . 'js/ezyFunctions.js', array('jquery'), null, true);
  wp_enqueue_script('ezyFunctions-js');
}
add_action('wp_enqueue_scripts', 'my_scripts2');



add_filter("script_loader_tag", "add_module_to_my_script", 10, 3);
function add_module_to_my_script($tag, $handle, $src)
{
  if ("start" === $handle) {
    $tag = '<script type="module" src="' . esc_url($src) . '"></script>';
  }
  return $tag;
}

function our_tutorial()
{
  if (isset($_REQUEST)) {
    $testing = $_REQUEST['php_test'];

    echo 'This is our JS Variable :' . $testing;

    // global $wpdb;
    // $wpdb->insert(
    //   $wpdb->prefix . 'lms_enroll',
    //   [
    //     'ID' => $testing
    //   ]
    // );
  }
  die();
}
add_action('wp_ajax_php_tutorial', 'our_tutorial');



/* **************************************************************/

if (!function_exists('wfu_before_frontpage_scripts_handler')) {
  /** Function syntax
   * The function takes one parameter, $changable_data.
   * - $changable_data is an array that can be modified by the filter and
   * contains the following items:
   * > correct_JQueryUI_incompatibility: if this item is set to "true",
   * then adjustments will be performed in the plugin so that it does not
   * cause incompatibilities with JQuery UI css.
   * > correct_NextGenGallery_incompatibility: if this item is set to
   * "true", then adjustments will be performed in the plugin so that it
   * does not cause incompatibilities with NextGen Gallery plugin.
   * > exclude_timepicker: if this item is set to "true", then timepicker
   * element's css and js code will not be loaded
   * If $changable_data contains the key 'return'value', then no plugin
   * scripts and styles will be loaded.
   * The function must return the final $changable_data. */
  function wfu_before_frontpage_scripts_handler($changable_data)
  {
    return $changable_data;
  }
  add_filter('wfu_before_frontpage_scripts', 'wfu_before_frontpage_scripts_handler', 10, 1);
}
/* **************************************************************/

/*
This filter runs before the upload starts, in order to perform any preliminary
custom server actions and allow the upload to start or reject it.
*/
if (!function_exists('wfu_before_upload_handler')) {
  /** Function syntax
   * The function takes two parameters, $changable_data and $additional_data.
   * - $changable_data is an array that can be modified by the filter and
   * contains the items:
   * > error_message: initially it is set to an empty value, if the handler
   * sets a non-empty value then upload will be cancelled showing this
   * error message
   * > js_script: javascript code to be executed on the client's browser
   * right after the filter
   * - $additional_data is an array with additional data to be used by the
   * filter (but cannot be modified) as follows:
   * > sid: this is the id of the plugin, as set using uploadid attribute;
   * it can be used to apply this filter only to a specific instance of
   * the plugin (if it is used in more than one pages or posts)
   * > unique_id: this id is unique for each individual upload attempt
   * and can be used to identify each separate upload
   * > files: holds an array with data about the files that have been
   * selected for upload; every item of the array is another array
   * with the following items:
   * >> filename: the filename of the file
   * >> filesize: the size of the file
   * The function must return the final $changable_data. */
  function wfu_before_upload_handler($changable_data, $additional_data)
  {
    $changable_data["js_script"] = '
    // console.log("wfu_before_upload_handler");
    // Add code here...
    ';
    return $changable_data;
  }
  add_filter('wfu_before_upload', 'wfu_before_upload_handler', 10, 2);
}

/* **************************************************************/

/*
This filter runs before the uploaded file is sent to the server and before the
plugin executes file validity checks (filename, extension, size etc.). It can
be used to perform custom file checks and reject the file if checks fail, or
customize the upload file path (or filename) taking into account data from
user data fields.
*/
if (!function_exists('wfu_before_file_check_handler')) {
  /** Function syntax
   * The function takes two parameters, $changable_data and $additional_data.
   * - $changable_data is an array that can be modified by the filter and
   * contains the items:
   * > file_path: the full path of the uploaded file
   * > user_data: an array of user data values, if userdata are activated
   * > error_message: initially it is set to an empty value, if the handler
   * sets a non-empty value then upload of the file will be cancelled
   * showing this error message
   * > admin_message: initially it is set to an empty value, if the handler
   * sets a non-empty value then this value will be shown to
   * administrators if adminmessages attribute has been activated,
   * provided that error_message is also set. You can use it to display
   * more information about the error, visible only to admins.
   * - $additional_data is an array with additional data to be used by the
   * filter (but cannot be modified) as follows:
   * > shortcode_id: this is the id of the plugin, as set using uploadid
   * attribute; it can be used to apply this filter only to a specific
   * instance of the plugin (if it is used in more than one pages or
   * posts)
   * > file_unique_id: this id is unique for each individual file upload
   * and can be used to identify each separate upload
   * > file_size: the size of the uploaded file
   * > user_id: the id of the user that submitted the file for upload
   * > page_id: the id of the page from where the upload was performed
   * (because there may be upload plugins in more than one page)
   * The function must return the final $changable_data. */
  function wfu_before_file_check_handler($changable_data, $additional_data)
  {
    $user = get_current_user_id();

    $orderNumber = get_option('ORDER_NUMBER');

    // $changable_data['user_data'][1]['value'] = $orderNumber;

    // $this_file_path = wfu_basedir($changable_data['file_path']);
    $date = $changable_data['user_data'][1]['value'];
    $price = $changable_data['user_data'][5]['value'];
    $total_price = $changable_data['user_data'][6]['value'];

    $size = $changable_data['user_data'][7]['value'];
    $finish = $changable_data['user_data'][8]['value'];
    $quantity = $changable_data['user_data'][2]['value'];
    $reSize = $changable_data['user_data'][4]['value'];

    $filename = wfu_basename($changable_data['file_path']);
    $filepath = wfu_basedir($changable_data['file_path']);

    $fileext = wfu_fileext($changable_data['file_path'], true);

    $changable_data['file_path'] =
      $filepath . '/' .
      $orderNumber . '/' .
      $size . '_' .
      $quantity . '_' .
      $finish . '_' .
      $reSize . '/' .
      $filename;

    $orderArray = [];
    $orderArray[] = $orderNumber;
    $orderArray[] = $user;
    $orderArray[] = $filename;
    $orderArray[] = $filepath;
    $orderArray[] = $quantity;
    $orderArray[] = $size;
    $orderArray[] = $finish;
    $orderArray[] = $reSize;
    $orderArray[] = $price;
    $orderArray[] = $total_price;
    $orderArray[] = date('Y-m-d H:i:s');

    addNewRowToUploads($orderArray);


    return $changable_data;
  }
  add_filter('wfu_before_file_check', 'wfu_before_file_check_handler', 10, 2);
}
/* **************************************************************/

// Folder:
// /web/www.ezyprints.co.nz/orders/1505/10x12_1_Lustre_no-resize_NB
// Files:
// /volume1/web/www.ezyprints.co.nz/orders/1505/10x12_1_Lustre_no-resize_NB/Emma Rouse multisheet 187.jpg
// /volume1/web/www.ezyprints.co.nz/orders/1505/10x12_1_Lustre_no-resize_NB/Emma Rouse multisheet 190.jpg
