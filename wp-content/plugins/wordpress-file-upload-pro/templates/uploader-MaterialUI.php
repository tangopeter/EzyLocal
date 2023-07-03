<?php

/**
 * Defines a custom upload template
 * 
 * This is a very simple example of creation of a custom upload template by
 * extending the original template.
 * 
 * This custom template is a child of the original template class, so it is not
 * required to declare all functions of the template but only those that are
 * different.
 */
class WFU_UploaderTemplate_MaterialUI extends WFU_Original_Template {

private static $instance = null;

protected $materialUiInitialized = false;

protected function add_theme_props(&$data) {
	$data["theme"] = array(
		"primary_color" => $data["params"]["muiprimarycolor"],
		"primary_default" => ( $data["params"]["muiprimarycolor"] == WFU_VAR("WFU_MUIPRIMARYCOLOR") ),
		"text_color" => $data["params"]["muitextcolor"],
		"text_default" => ( $data["params"]["muitextcolor"] == WFU_VAR("WFU_MUITEXTCOLOR") ),
		"error_color" => $data["params"]["muierrorcolor"],
		"error_default" => ( $data["params"]["muierrorcolor"] == WFU_VAR("WFU_MUIERRORCOLOR") ),
		"dark_mode" => ( $data["params"]["muidarkmode"] == "true" )
	);
}

function wfu_base_template($data) {?>
<style>
<?php if ( $data["params"]["muioverridecssmethod"] == "layers" ): ?>
@layer normal-styles {
	div#wordpress_file_upload_block_$ID :not(path),
	div#wordpress_file_upload_block_$ID *:before,
	div#wordpress_file_upload_block_$ID *:after	{
		all: revert;
	}
	div#wordpress_file_upload_block_$ID *[hidden] {
		display: none;
	}
}
@layer react-reset-styles {
	.MuiPopper-root :not(path),
	.MuiPopper-root *:before,
	.MuiPopper-root *:after	{
		all: revert;
	}
	.MuiPopper-root *[hidden] {
		display: none;
	}
}
<?php endif ?>
</style>
<?php }

function wfu_contents_template($data) {?>
<?php
	extract($data);
	echo "<!--RAW-->";
	if ( $params["muioverridecssmethod"] != 'shadow-dom' ) {
		echo $contents;
		return;
	}
	// This part runs if Material UI Override CSS Mode is 'shadow-dom'.
	// In this case $contents need to be put inside a shadow DOM.
	// Before doing so, we need first to extract any code that is not intended
	// for the shadow DOM. Any code that needs to be put first after the shadow
	// DOM is enclosed in <shadowdom_pre> tags. Any code that needs to be put
	// after the shadow DOM is enclosed in <shadowdom_post> tags (shadow DOM is
	// always first).
	$pre_contents = "";
	$res = preg_match_all("/<shadowdom_pre>(.*?)<\/shadowdom_pre>/s", $contents, $pre);
	if ( $res > 0 ) {
		$pre_contents = implode("", $pre[1]);
		$contents = preg_replace("/<shadowdom_pre>(.*?)<\/shadowdom_pre>/s", "", $contents);
	}
	$post_contents = "";
	$res = preg_match_all("/<shadowdom_post>(.*?)<\/shadowdom_post>/s", $contents, $post);
	if ( $res > 0 ) {
		$post_contents = implode("", $post[1]);
		$contents = preg_replace("/<shadowdom_post>(.*?)<\/shadowdom_post>/s", "", $contents);
	}
?>
<template id="r_wfu_template_<?php echo $ID; ?>"><div id="wordpress_file_upload_block_<?php echo $ID; ?>" class="wfu_container r_wfu_container"><?php echo ($contents); ?></div></template>
<template id="r_wfu_template_<?php echo $ID; ?>_pre"><?php echo ($pre_contents); ?></template>
<template id="r_wfu_template_<?php echo $ID; ?>_post"><?php echo ($post_contents); ?></template>
<template id="r_wfu_styles_template_<?php echo $ID; ?>"><style>
	@import url('<?php echo WPFILEUPLOAD_DIR.'vendor/file-icons-js/src/style.css'; ?>');
	<?php if ( $params['css'] != '' ) echo str_replace(array( '%dq%', '%brl%', '%brr%', '%n%' ), array( '"', '[', ']', "\n" ), $params['css']); ?>
</style></template>
<script type="text/javascript">
	/**
	 * Put Upload Form HTML Inside Shadow DOM.
	 *
	 * This function runs when Material UI Override CSS Mode is 'shadow-dom' in
	 * order to render the upload form's HTML inside a shadow DOM. It first
	 * creates the shadow DOM and then puts the HTML inside using a template
	 * element.
	 *
	 * @since 4.21.0
	*/
	(function() {
		const sid = '<?php echo $ID; ?>';
		const template = document.getElementById('r_wfu_template_' + sid);
		const pre_template = document.getElementById('r_wfu_template_' + sid + '_pre');
		const post_template = document.getElementById('r_wfu_template_' + sid + '_post');
		const container = document.getElementById('wordpress_file_upload_block_' + sid);
		container.dom = container.attachShadow({ mode: "open" });
		container.dom.appendChild(template.content);
		// we put a style element as the first node of the shadow DOM in order
		// to load the file icons css
		const stylesTemplate = document.getElementById('r_wfu_styles_template_' + sid);
		if (stylesTemplate) container.dom.prepend(stylesTemplate.content);
		container.prepend(pre_template.content);
		container.appendChild(post_template.content);
	})();
</script>
<?php }

function wfu_row_container_template($data) {?>
<?php

	extract($data);

	$items_count = count($items);
	$item_props = array();
	for ( $i = 0; $i < $items_count; $i++ ) {
		$item_prop["title"] = $items[$i]["title"];
		$item_prop["is_first"] = ( $i == 0 );
		$item_prop["is_last"] = ( $i == $items_count - 1 );
		$style = "";
		if ( $items[$i]["width"] != "" ) $style .= 'width: '.$items[$i]["width"].'; ';
		if ( $items[$i]["hidden"] ) $style .= 'display: none; ';
		$item_prop["style"] = $style;
		$item_prop["lines"] = array();
		$k = 1;
		while ( isset($items[$i]["line".$k]) ) {
			if ( $items[$i]["line".$k] != "" )
				array_push($item_prop["lines"], $items[$i]["line".$k]);
			$k++;
		}
		if ( isset($items[$i]["object"]) ) $item_prop["object"] = $items[$i]["object"];
		array_push($item_props, $item_prop);
	}
?>
<?php if ( $responsive ): ?>
<?php foreach ( $item_props as $p ): ?>
	<div id="<?php echo $p["title"]; ?>" class="file_div_clean_responsive_r" style="<?php echo esc_html($p["style"]); ?>">
	<?php foreach ( $p["lines"] as $line ): ?>
		<?php echo $line; ?>
	<?php endforeach ?>
		<div class="file_space_clean_r"></div>
	<?php if ( isset($p["object"]) ): ?>
		<script type="text/javascript">wfu_run_js("<?php echo $p["object"]; ?>", "init");</script>
	<?php endif ?>
	</div>
<?php endforeach ?>
	<br />
<?php else: ?>
	<div class="file_div_clean_r">
		<table class="file_table_clean_r">
			<tbody>
				<tr>
				<?php foreach ( $item_props as $p ): ?>
					<td class="file_td_clean_r" style="<?php echo ( $p["is_last"] ? "" : "padding: 0 4px 0 0;" ); ?>">
						<div id="<?php echo $p["title"]; ?>" class="file_div_clean_r" style="<?php echo esc_html($p["style"]); ?>">
						<?php foreach ( $p["lines"] as $line ): ?>
							<?php echo $line; ?>
						<?php endforeach ?>
							<div class="file_space_clean_r"></div>
						<?php if ( isset($p["object"]) ): ?>
							<script type="text/javascript">wfu_run_js("<?php echo $p["object"]; ?>", "init");</script>
						<?php endif ?>
						</div>
					</td>
				<?php endforeach ?>
				</tr>
			</tbody>
		</table>
	</div>
<?php endif ?>
<?php }

function wfu_visualeditorbutton_template($data) {?>
<?php
	extract($data);
	$this->add_theme_props($data);
	$data["forceSpecificity"] = $data["params"]["muioverridecssmethod"];
	unset($data["params"]);
	$data["button_title"] = WFU_PAGE_PLUGINEDITOR_BUTTONTITLE;
	$data["editor_loading"] = WFU_PAGE_PLUGINEDITOR_LOADING;
	$dataEnc = wfu_encode_array_to_string($data);
?>
<style>
.wfu_container:not(:hover) #r_wfu_visualeditorbutton_$ID {
	display: none;
}
</style>
<script type="text/javascript">
GlobalData.WFU[$ID].visualeditorbutton.init = function() {

	let data = JSON.parse(wfu_plugin_decode_string('<?php echo $dataEnc; ?>'));
	data.slot = 'renderVisualEditorButton';
	wfu_create_react_dom('r_wfu_visualeditorbutton_$ID', data);
	wfu_render_react_component(data);
 
	this.attachInvokeHandler = function(invoke_function) { GlobalData.WFU[$ID].RR('visualeditorbutton')?.attachInvokeHandler(invoke_function); }
	this.update = function(status) { GlobalData.WFU[$ID].RR('visualeditorbutton')?.update(status); }
}
</script>
<div id="r_wfu_visualeditorbutton_$ID"></div>
<?php }

function wfu_dragdrop_template($data) {?>
<?php
	$this->add_theme_props($data);
	$data["forceSpecificity"] = $data["params"]["muioverridecssmethod"];
	unset($data["params"]);
	$data["drophere_message"] = WFU_DROP_HERE_MESSAGE;
	$dataEnc = wfu_encode_array_to_string($data);
?>
<style></style>
<script type="text/javascript">
GlobalData.WFU[$ID].dragdrop.init = function() {
	
	let data = JSON.parse(wfu_plugin_decode_string('<?php echo $dataEnc; ?>'));
	data.slot = 'renderDragdrop';
	wfu_create_react_dom('r_wfu_dragdrop_$ID', data);
	wfu_render_react_component(data);

	this.attachHandlers = function(handlers) { GlobalData.WFU[$ID].RR('dragdrop')?.attachHandlers(handlers); }
	this.disableDragDrop = function() { GlobalData.WFU[$ID].RR('dragdrop')?.disableDragDrop(); }
	this.reEnableDragDrop = function() { GlobalData.WFU[$ID].RR('dragdrop')?.reEnableDragDrop(); }
}
</script>
<div id="r_wfu_dragdrop_$ID"></div>
<?php }

function wfu_title_template($data) {?>
<?php
	$this->add_theme_props($data);
	$data["forceSpecificity"] = $data["params"]["muioverridecssmethod"];
	unset($data["params"]);
	$dataEnc = wfu_encode_array_to_string($data);
?>
<style></style>
<script type="text/javascript">
GlobalData.WFU[$ID].title.init = function() {

	let data = JSON.parse(wfu_plugin_decode_string('<?php echo $dataEnc; ?>'));
	data.slot = 'renderTitle';
	wfu_create_react_dom('r_wfu_title_$ID', data);
	wfu_render_react_component(data);

}
</script>
<div id="r_wfu_title_$ID"></div>
<?php }

function wfu_textbox_template($data) {?>
<?php
	$this->add_theme_props($data);
	$data["forceSpecificity"] = $data["params"]["muioverridecssmethod"];
	unset($data["params"]);
	$dataEnc = wfu_encode_array_to_string($data);
?>
<style></style>
<script type="text/javascript">
GlobalData.WFU[$ID].textbox.init = function() {

	let data = JSON.parse(wfu_plugin_decode_string('<?php echo $dataEnc; ?>'));
	data.slot = 'renderTextbox';
	wfu_create_react_dom('r_wfu_textbox_$ID', data);
	wfu_render_react_component(data);

	this.attachCancelHandler = function(cancel_function) { GlobalData.WFU[$ID].RR('textbox')?.attachActions({ cancelFunction: cancel_function }); }
	this.dettachCancelHandler = function() { GlobalData.WFU[$ID].RR('textbox')?.dettachCancelHandler(); }
	this.update = function(action, filenames) { GlobalData.WFU[$ID].RR('textbox')?.update(action, filenames); }

}
</script>
<div id="r_wfu_textbox_$ID"></div>
<?php }

function wfu_subfolders_template($data) {?>
<?php
	$this->add_theme_props($data);
	$data["forceSpecificity"] = $data["params"]["muioverridecssmethod"];
	unset($data["params"]);
	$data['nosubfolder_selected'] = WFU_ERROR_SUBFOLDER_NOTSELECTED;
	$dataEnc = wfu_encode_array_to_string($data);
?>
<style></style>
<script type="text/javascript">
GlobalData.WFU[$ID].subfolders.init = function() {

	let data = JSON.parse(wfu_plugin_decode_string('<?php echo $dataEnc; ?>'));
	data.slot = 'renderSubfolders';
	wfu_create_react_dom('r_wfu_subfolders_$ID', data);
	wfu_render_react_component(data);
	let slotProps = {
		ID: '$ID',
		slot: 'renderSubfolders.initHandler',
		handler: this.update_handler,
	};
	wfu_render_react_component(slotProps);

	this.check = function() { return GlobalData.WFU[$ID].RR('subfolders')?.check(); }
	this.index = function() { return GlobalData.WFU[$ID].RR('subfolders')?.index(); }
	this.reset = function() { GlobalData.WFU[$ID].RR('subfolders')?.reset(); }
	this.toggle = function(enabled) { GlobalData.WFU[$ID].RR('subfolders')?.toggle(enabled); }
}
</script>
<div id="r_wfu_subfolders_$ID"></div>
<?php }

function wfu_uploadform_template($data) {?>
<?php
	$this->add_theme_props($data);
	$data["forceSpecificity"] = $data["params"]["muioverridecssmethod"];
	unset($data["params"]);
	$data["testmode_message"] = WFU_NOTIFY_TESTMODE;
	$dataEnc = wfu_encode_array_to_string($data);
?>
<style></style>
<script type="text/javascript">
GlobalData.WFU[$ID].uploadform.init = function() {

	let data = JSON.parse(wfu_plugin_decode_string('<?php echo $dataEnc; ?>'));
	data.slot = 'renderUploadform';
	wfu_create_react_dom('r_wfu_uploadform_$ID', data);
	wfu_render_react_component(data);

	this.attachActions = function(clickaction, changeaction) {
		GlobalData.WFU[$ID].RR('uploadform')?.attachActions({ clickaction: clickaction, changeaction: changeaction });
	}
	this.getStoreddata = function(id) { return GlobalData.WFU[$ID].RR('uploadform')?.getStoreddata(id); }
	this.setStoreddata = function(id, value) { GlobalData.WFU[$ID].RR('uploadform')?.setStoreddata(id, value); }
	this.reset = function() { GlobalData.WFU[$ID].RR('uploadform')?.reset(); }
	this.resetDummy = function() { GlobalData.WFU[$ID].RR('uploadform')?.resetDummy(); }
	this.submit = function() { GlobalData.WFU[$ID].RR('uploadform')?.submit(); }
	this.lock = function() { GlobalData.WFU[$ID].RR('uploadform')?.lock(); }
	this.unlock = function() { GlobalData.WFU[$ID].RR('uploadform')?.unlock(); }
	this.changeFileName = function(new_filename) { GlobalData.WFU[$ID].RR('uploadform')?.changeFileName(new_filename); }
	this.files = function() { return GlobalData.WFU[$ID].RR('uploadform')?.files(); }

}
</script>
<div id="r_wfu_uploadform_$ID"></div>
<?php }

function wfu_submit_template($data) {?>
<?php
	$this->add_theme_props($data);
	$data["forceSpecificity"] = $data["params"]["muioverridecssmethod"];
	unset($data["params"]);
	$data["testmode_message"] = WFU_NOTIFY_TESTMODE;
	$dataEnc = wfu_encode_array_to_string($data);
?>
<style></style>
<script type="text/javascript">
GlobalData.WFU[$ID].submit.init = function() {

	let data = JSON.parse(wfu_plugin_decode_string('<?php echo $dataEnc; ?>'));
	data.slot = 'renderSubmit';
	wfu_create_react_dom('r_wfu_submit_$ID', data);
	wfu_render_react_component(data);

	this.attachClickAction = function(clickaction) { GlobalData.WFU[$ID].RR('submit')?.attachActions({ clickaction: clickaction }); }
	this.updateLabel = function(new_label) { GlobalData.WFU[$ID].RR('submit')?.updateLabel(new_label); }
	this.toggle = function(status) { GlobalData.WFU[$ID].RR('submit')?.toggle(status); }
}
</script>
<div id="r_wfu_submit_$ID"></div>
<?php }

function wfu_progressbar_template($data) {?>
<?php 
	$this->add_theme_props($data);
	$data["forceSpecificity"] = $data["params"]["muioverridecssmethod"];
	unset($data["params"]);
	$dataEnc = wfu_encode_array_to_string($data);
?><style></style>
<script type="text/javascript">
GlobalData.WFU[$ID].progressbar.init = function() {

	let data = JSON.parse(wfu_plugin_decode_string('<?php echo $dataEnc; ?>'));
	data.slot = 'renderProgressbar';
	wfu_create_react_dom('r_wfu_progressbar_$ID', data);
	wfu_render_react_component(data);

	this.show = function(mode) { GlobalData.WFU[$ID].RR('progressbar')?.show(mode); }
	this.hide = function() { GlobalData.WFU[$ID].RR('progressbar')?.hide(); }
	this.update = function(position) { GlobalData.WFU[$ID].RR('progressbar')?.update(position); }
	
	const dom = (data.forceSpecificity == 'shadow-dom' ? document.getElementById('wordpress_file_upload_block_$ID').dom : document);
	dom.getElementById('wordpress_file_upload_progressbar_$ID').style.display = "block";
}
</script>
<div id="r_wfu_progressbar_$ID"></div>
<?php }

function wfu_filelist_template($data) {?>
<?php
	$this->add_theme_props($data);
	$data["forceSpecificity"] = $data["params"]["muioverridecssmethod"];
	unset($data["params"]);
	$data["nofiles_selected_message"] = WFU_WARNING_NOFILES_SELECTED;
	$dataEnc = wfu_encode_array_to_string($data);
?><style></style>
<script type="text/javascript">
GlobalData.WFU[$ID].filelist.init = function() {
	
	let data = JSON.parse(wfu_plugin_decode_string('<?php echo $dataEnc; ?>'));
	data.slot = 'renderFilelist';
	wfu_create_react_dom('r_wfu_filelist_$ID', data);
	wfu_render_react_component(data);

	this.setVisibility = function(show) { GlobalData.WFU[$ID].RR('filelist')?.setVisibility(show); }
	this.visible = function() { return GlobalData.WFU[$ID].RR('filelist')?.visible();	}
	this.updateList = function(files, is_formupload) { GlobalData.WFU[$ID].RR('filelist')?.updateList(files, is_formupload, this._removeFile, this._removeAllFiles); }
	this.enableEditing = function(is_formupload) { GlobalData.WFU[$ID].RR('filelist')?.enableEditing(is_formupload); }
	this.disableEditing = function(is_formupload) { GlobalData.WFU[$ID].RR('filelist')?.disableEditing(is_formupload); }
	this.afterUploadStart = function(effect, is_formupload) { GlobalData.WFU[$ID].RR('filelist')?.afterUploadStart(effect, is_formupload); }
	this.attachCancelHandlers = function(cancel_function) { GlobalData.WFU[$ID].RR('filelist')?.attachCancelHandlers(cancel_function); }
	this.dettachCancelHandlers = function() { GlobalData.WFU[$ID].RR('filelist')?.dettachCancelHandlers(); }
	this.updateItemProgress = function(index, position) { GlobalData.WFU[$ID].RR('filelist')?.updateItemProgress(index, position); }
	this.updateTotalProgress = function(position) { GlobalData.WFU[$ID].RR('filelist')?.updateTotalProgress(position); }
	this.resetTotalProgress = function() { GlobalData.WFU[$ID].RR('filelist')?.resetTotalProgress(); }
	this.updateItemStatus = function(index, status) { GlobalData.WFU[$ID].RR('filelist')?.updateItemStatus(index, status); }
	this._removeFile = function(index) { wfu_filelist_removefile($ID, index); }
	this._removeAllFiles = function() { wfu_filelist_removeall($ID); }
}
</script>
<div id="r_wfu_filelist_$ID"></div>
<?php }

function wfu_userdata_template($data) {?>
<?php
	extract($data);
	$this->add_theme_props($data);
	$data["forceSpecificity"] = $params["muioverridecssmethod"];
	unset($data["params"]);
	$dataEnc = wfu_encode_array_to_string($data);
?><style></style>
<script type="text/javascript">
GlobalData.WFU[$ID].userdata.init = function() {

	let data = JSON.parse(wfu_plugin_decode_string('<?php echo $dataEnc; ?>'));
	data.slot = 'renderUserdata';
	wfu_render_react_component(data);

	this.initField = function(props) {
		let slotProps = {
			ID: '$ID',
			forceSpecificity: '<?php echo $params["muioverridecssmethod"]; ?>',
			slot: 'renderUserdata.initField',
			props: props
		};
		wfu_create_react_dom('r_wfu_userdata_$ID_' + props.key, slotProps);
		wfu_render_react_component(slotProps);
	}
	this.attachHandlers = function(props, handlerfunc) {
		let slotProps = {
			ID: '$ID',
			slot: 'renderUserdata.attachHandlers',
			handlerfunc: handlerfunc,
			props: props
		};
		wfu_render_react_component(slotProps);
	}
	this.getValue = function(props) { return GlobalData.WFU[$ID].RR('userdata')?.getValue(props); }
	this.setValue = function(props, value) { GlobalData.WFU[$ID].RR('userdata')?.setValue(props, value); }
	this.enable = function(props) { GlobalData.WFU[$ID].RR('userdata')?.toggle(props, true); }
	this.disable = function(props) { GlobalData.WFU[$ID].RR('userdata')?.toggle(props, false); }
	this.prompt = function(props, message) { GlobalData.WFU[$ID].RR('userdata')?.prompt(props, message); }
}
</script>
<?php foreach ( $props as $p ): ?>
<userdata_<?php echo $p["key"]; ?>_template>
<div id="userdata_$ID_<?php echo $p["key"]; ?>" class="file_userdata_container">
<div id="r_wfu_userdata_$ID_<?php echo $p["key"]; ?>"></div>
</div>
</userdata_<?php echo $p["key"]; ?>_template>
<?php endforeach ?>
<?php }

function wfu_captcha_template($data) {?>
<?php /*************************************************************************
          the following lines contain initialization of PHP variables
*******************************************************************************/
/* do not change this line */extract($data);
/*
 *  The following variables are available for use:
 *  
 *  @var $ID int the upload ID
 *  @var $width string assigned width of captcha element
 *  @var $height string assigned height of captcha element
 *  @var $responsive bool true if responsive mode is enabled
 *  @var $testmode bool true if the plugin is in test mode
 *  @var $type string the captcha type, it can be "RecaptchaV1", "RecaptchaV2",
 *       "RecaptchaV1 (no account)" or "RecaptchaV2 (no account)"
 *  @var $V2unsupported bool true if the website does not support the new
 *       version 2 of Google Recaptcha due to old PHP version (lower than 5.3.0)
 *  @var $sitekey string the public (site) key of Google Recaptcha; applicable
 *       when captcha type is "RecaptchaV1" or "RecaptchaV2"
 *  @var $frameURL string the URL of the iframe element that will load captcha
 *       element when captcha type is "RecaptchaV1 (no account)" or
 *       "RecaptchaV2 (no account)"
 *  @var $index int the index of occurrence of the element inside the plugin,
 *       in case that it appears more than once
 *  @var $params array all plugin's attributes defined through the shortcode
 *  
 *  It is noted that $ID can also be used inside CSS, Javascript and HTML code.
 */
	$styles = "";
	if ( $width != "" ) $styles .= "width: $width; ";
	if ( $height != "" ) $styles .= "height: $height;";
	$user_is_admin = current_user_can( 'manage_options' );
	$error_text = "";
	if ( $type == "RecaptchaV1" )
		$error_text = ( $sitekey == '' ? ( $user_is_admin ? WFU_ERROR_CAPTCHA_NOSITEKEY_ADMIN : WFU_ERROR_CAPTCHA_NOSITEKEY ) : WFU_ERROR_CAPTCHA_UNKNOWNERROR );
	elseif ( $type == "RecaptchaV2" ) {
		$error_text = ( !$V2unsupported ? ( $sitekey == '' ? ( $user_is_admin ? WFU_ERROR_CAPTCHA_NOSITEKEY_ADMIN : WFU_ERROR_CAPTCHA_NOSITEKEY ) : WFU_ERROR_CAPTCHA_UNKNOWNERROR ) : WFU_ERROR_CAPTCHA_OLDPHP );
	}
/*******************************************************************************
              the following lines contain CSS styling rules
*********************************************************************/ ?><style>
span.file_captcha_prompt
{
	display: inline-block;
}

input[type="text"].file_captcha_input
{
	position: relative;	
	width: 150px; /*relax*/
	height: 25px; /*relax*/
	color: black; /*relax*/
	margin: 0px; /*relax*/
	padding: 0px; /*relax*/
	border: 1px solid; /*relax*/
	border-color: #BBBBBB; /*relax*/
	background-color: white; /*relax*/
	background-image: none; /*relax*/
}

input[type="text"].file_captcha_input:disabled
{
	position: relative;	
	width: 150px; /*relax*/
	height: 25px; /*relax*/
	color: silver; /*relax*/
	margin: 0px; /*relax*/
	padding: 0px; /*relax*/
	border: 1px solid; /*relax*/
	border-color: #BBBBBB; /*relax*/
	background-color: white; /*relax*/
	background-image: none; /*relax*/
}

input[type="text"].file_captcha_input_empty
{
	position: relative;	
	width: 150px; /*relax*/
	height: 25px; /*relax*/
	color: black; /*relax*/
	margin: 0px; /*relax*/
	padding: 0px; /*relax*/
	border: 1px solid; /*relax*/
	border-color: #BBBBBB; /*relax*/
	background-color: red;
	background-image: none; /*relax*/
}

input[type="text"].file_captcha_input_checking
{
	position: relative;	
	width: 150px; /*relax*/
	height: 25px; /*relax*/
	color: black; /*relax*/
	margin: 0px; /*relax*/
	padding: 0px; /*relax*/
	border: 1px solid; /*relax*/
	border-color: #BBBBBB; /*relax*/
	background-color: white; /*relax*/
	background-image: url("<?php echo WPFILEUPLOAD_DIR; ?>images/refresh_16.gif");
	background-repeat: no-repeat;
	background-position: right;
}

input[type="text"].file_captcha_input_checking:disabled
{
	position: relative;	
	width: 150px; /*relax*/
	height: 25px; /*relax*/
	color: silver; /*relax*/
	margin: 0px; /*relax*/
	padding: 0px; /*relax*/
	border: 1px solid; /*relax*/
	border-color: #BBBBBB; /*relax*/
	background-color: white; /*relax*/
	background-image: url("<?php echo WPFILEUPLOAD_DIR; ?>images/refresh_16.gif");
	background-repeat: no-repeat;
	background-position: right;
}

input[type="text"].file_captcha_input_Ok
{
	position: relative;	
	width: 150px; /*relax*/
	height: 25px; /*relax*/
	color: black; /*relax*/
	margin: 0px; /*relax*/
	padding: 0px; /*relax*/
	border: 1px solid; /*relax*/
	border-color: #BBBBBB; /*relax*/
	background-color: white; /*relax*/
	background-image: url("<?php echo WPFILEUPLOAD_DIR; ?>images/ok_16.png");
	background-repeat: no-repeat;
	background-position: right;
}

input[type="text"].file_captcha_input_Ok:disabled
{
	position: relative;	
	width: 150px; /*relax*/
	height: 25px; /*relax*/
	color: silver; /*relax*/
	margin: 0px; /*relax*/
	padding: 0px; /*relax*/
	border: 1px solid; /*relax*/
	border-color: #BBBBBB; /*relax*/
	background-color: white; /*relax*/
	background-image: url("<?php echo WPFILEUPLOAD_DIR; ?>images/ok_16.png");
	background-repeat: no-repeat;
	background-position: right;
}

div.file_captcha
{
	display: block;
	position: relative;	
	width: 318px;
	margin: 0px;
	padding: 0px;
	border-style: none;
	background: none;
	color: black;
}

div.file_captcha .recaptchatable {
	table-layout: auto;
}

div.file_captchav2
{
	display: block;
	position: relative;	
	width: 304px;
	margin: 0px;
	padding: 0px;
	border-style: none;
	background: none;
	color: black;
}

div.file_captcha_inner
{
	position: relative;
	min-height: 40px;
}

div.file_captcha_inner iframe
{
	width: 318px;
	height: 129px;
	margin: 0;
}

div.file_captcha_inner_v2
{
	position: relative;
	width: 304px;
	height: 78px;
	overflow: visible;
}

div.file_captcha_inner_v2_empty, div.file_captcha_inner_v2_locked
{
	overflow: hidden;
}

div.file_captcha_inner_v2 iframe
{
	position: absolute;
	width: 304px;
	height: 78px;
/*	max-width: 800px;
	max-height: 800px;*/
	overflow: visible;
	z-index: 1;
	margin: 0;
}

div.file_captcha_inner_v2_empty iframe, div.file_captcha_inner_v2_locked iframe
{
	height: 72px;
}

div.file_captcha_overlay_v1
{
	position: absolute;
	left: 0;
	top: 0;
	right: 0;
	bottom: 0;
	background: none;
	border: 4px solid red;
	z-index: 2;
	display: none;
}

div.file_captcha_inner_empty div.file_captcha_overlay_v1
{
	display: block;
}

div.file_captcha_inner_locked div.file_captcha_overlay_v1
{
	display: block;
	background-color: rgba(255,255,255,0.9);
	border: none;
}

label.file_captcha_overlay_v1_errors
{
	background-color: red;
	color: white;
	font-size: small;
	text-overflow: ellipsis;
	white-space: nowrap;
	overflow: hidden;
}

div.file_captcha_inner_empty label.file_captcha_overlay_v1_errors
{
	display: block;
}

div.file_captcha_inner_locked label.file_captcha_overlay_v1_errors
{
	display: none;
}

div.file_captcha_overlay_v2
{
	position: absolute;
	left: 0;
	top: 0;
	right: 0;
	bottom: 0;
	background: none;
	border: 4px solid red;
	z-index: 2;
	display: none;
}

div.file_captcha_inner_v2_empty div.file_captcha_overlay_v2
{
	display: block;
}

div.file_captcha_inner_v2_locked div.file_captcha_overlay_v2
{
	display: block;
	background-color: rgba(255,255,255,0.9);
	border: none;
}

label.file_captcha_overlay_v2_errors
{
	background-color: red;
	color: white;
	font-size: small;
	text-overflow: ellipsis;
	white-space: nowrap;
	overflow: hidden;
}

div.file_captcha_inner_v2_empty label.file_captcha_overlay_v2_errors
{
	display: block;
}

div.file_captcha_inner_v2_locked label.file_captcha_overlay_v2_errors
{
	display: none;
}

div.file_captcha_overlay_hidden
{
	position: absolute;
	display: none;
	width: 100%;
	height: 100%;
	top: 0;
	left: 0;
	background-color: rgba(255,255,255,0.9);
}

div.file_captcha_loading_outer, div.file_captcha_overlay_outer
{
	position: absolute;
	display: block;
	width: 100%;
	height: 100%;
	top: 0;
	left: 0;
	background-color: rgba(255,255,255,0.9);
}

div.file_captcha_loading_inner
{
	position: relative;
	height: 100%;
}

div.file_captcha_loading_outer img.file_captcha_loading_image
{
	position: absolute;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
	margin: auto;
}

div.file_captcha_overlay_outer img.file_captcha_loading_image
{
	position: absolute;
	display: none;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
	margin: auto;
}

img.file_captcha_inner+img.file_captcha_refresh:hover
{
	display: block;
	position: absolute;
	right: 0px;
	top: 0px;
	padding: 2px;
	border: 1px solid #aaa;
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
	-khtml-border-radius: 4px;
	border-radius: 4px;
	background-color: #ddd;
	width: 20px;
	height: 20px;
}

img.file_captcha_inner+img.file_captcha_refresh
{
	display: none;
	position: absolute;
	right: 0px;
	top: 0px;
	padding: 2px;
	border: 1px solid #aaa;
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
	-khtml-border-radius: 4px;
	border-radius: 4px;
	background-color: white;
	width: 20px;
	height: 20px;
}

img.file_captcha_inner:hover+img.file_captcha_refresh
{
	display: block;
	position: absolute;
	right: 0px;
	top: 0px;
	padding: 2px;
	border: 1px solid #aaa;
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
	-khtml-border-radius: 4px;
	border-radius: 4px;
	background-color: white;
	width: 20px;
	height: 20px;
}
</style><?php /*****************************************************************
               the following lines contain Javascript code 
*********************************************/ ?><script type="text/javascript">
/* do not change this line */GlobalData.WFU[$ID].captcha.init = function() {
/***
 *  The following captcha params have been defined and can be used in Javascript
 *  code:
 *
 *  @var type string the captcha type, it can be "RecaptchaV1", "RecaptchaV2",
 *       "RecaptchaV1 (no account)" or "RecaptchaV2 (no account)"
 *  
 *  The following captcha methods can be defined by the template, together
 *  with other initialization actions:
 *
 *  @method updateStatus updates the status of the captcha
 *  @method isLocked return lock status of captcha
 *  @method core returns the core captcha element that does all the job
 *  @method resize resizes the captcha
 */
/**
 *  updates the status of the captcha
 *  
 *  @param status string the status of the captcha
 *  @param param string optional additional parameter; if status is 'error' then
 *         param represents the error message
 *  
 *  @return void
 */
this.updateStatus = function(status, param) {
	var mode = this.type.substr(9, 2);
	var dom = (<?php echo ( $params["muioverridecssmethod"] == 'shadow-dom' ? 'true' : 'false' ); ?> ? document.getElementById('wordpress_file_upload_block_$ID').dom : document);
	var captcha_empty_overlay = dom.getElementById('captcha_$ID_empty_overlay');
	var captcha_error_label = dom.getElementById('captcha_$ID_overlay_error_label');
	if (mode == "V1") {
		if (status == "idle") {
			captcha_empty_overlay.parentNode.className = "file_captcha_inner";
		}
		else if (status == "empty") {
			captcha_error_label.innerHTML = GlobalData.consts.captchamessage_empty;
			captcha_error_label.title = GlobalData.consts.captchamessage_empty;
			captcha_empty_overlay.parentNode.className = "file_captcha_inner file_captcha_inner_empty";
		}
		else if (!(status != "error" || arguments.length <= 1)) {
			var param = arguments[1];
			captcha_error_label.innerHTML = param;
			captcha_error_label.title = param;
			captcha_empty_overlay.parentNode.className = "file_captcha_inner file_captcha_inner_empty";
		}
		else if (status == "checking") {
			captcha_empty_overlay.parentNode.className = "file_captcha_inner file_captcha_inner_locked";
		}
		else if (status == "locked") {
			captcha_empty_overlay.parentNode.className = "file_captcha_inner file_captcha_inner_locked";
		}
		else if (status == "unlocked") {
			captcha_empty_overlay.parentNode.className = "file_captcha_inner";
		}
		else if (status == "nomultiple") {
			dom.getElementById("captcha_$ID_inner").className = "file_captcha_inner file_captcha_inner_empty";
			captcha_empty_overlay.onclick = null;
			captcha_error_label.title = (GlobalData.WFU[$ID].is_admin ? GlobalData.consts.captcha_multiple_notallowed_admin : GlobalData.consts.captcha_multiple_notallowed );
			captcha_error_label.innerHTML = captcha_error_label.title;
		}
	}
	else if (mode == "V2") {
		if (status == "idle") {
			captcha_empty_overlay.parentNode.className = "file_captcha_inner_v2";
		}
		else if (status == "empty") {
			captcha_error_label.innerHTML = GlobalData.consts.captchamessage_empty;
			captcha_error_label.title = GlobalData.consts.captchamessage_empty;
			captcha_empty_overlay.parentNode.className = "file_captcha_inner_v2 file_captcha_inner_v2_empty";
		}
		else if (!(status != "error" || arguments.length <= 1)) {
			var param = arguments[1];
			captcha_error_label.innerHTML = param;
			captcha_error_label.title = param;
			captcha_empty_overlay.parentNode.className = "file_captcha_inner_v2 file_captcha_inner_v2_empty";
		}
		else if (status == "checking") {
			captcha_empty_overlay.parentNode.className = "file_captcha_inner_v2 file_captcha_inner_v2_locked";
		}
		else if (status == "locked") {
			captcha_empty_overlay.parentNode.className = "file_captcha_inner_v2 file_captcha_inner_v2_locked";
		}
		else if (status == "unlocked") {
			captcha_empty_overlay.parentNode.className = "file_captcha_inner_v2";
		}
	}
}

/**
 *  return lock status of captcha
 *  
 *  This function returns true if captcha is locked because an upload is on
 *  progress.
 *  
 *  @return bool true if captcha is locked
 */
this.isLocked = function() {
	var mode = this.type.substr(9, 2);
	var dom = (<?php echo ( $params["muioverridecssmethod"] == 'shadow-dom' ? 'true' : 'false' ); ?> ? document.getElementById('wordpress_file_upload_block_$ID').dom : document);
	var captcha_empty_overlay = dom.getElementById('captcha_$ID_empty_overlay');
	if (mode == "V1") { 
		return (captcha_empty_overlay.parentNode.className == "file_captcha_inner file_captcha_inner_locked")
	}
	else if (mode == "V2") {
		return (captcha_empty_overlay.parentNode.className == "file_captcha_inner_v2 file_captcha_inner_v2_locked") 
	}
	else return false;
}

/**
 *  returns the core captcha element that does all the job
 *  
 *  For normal Google Recaptcha V1 and V2 versions this is the div element that
 *  will be used as container for Google Recaptcha API to generate the captcha
 *  HTML code. For (no account) versions this is the iframe element, in which
 *  the captcha code is generated.
 *  When Material UI Override CSS Mode is 'shadow-dom', all captcha code will be
 *  inside a shadow DOM, except the div core captcha element for normal Google
 *  Recaptcha V1 and V2 versions. Google Recaptcha does not work inside a shadow
 *  DOM (it throws an uncaught DOMException: "Blocked a frame..."). So we put
 *  the div core inside a slot and a slot reference inside the shadow DOM. This
 *  way Google Recaptcha calls Google servers outside the shadow DOM, but it
 *  renders inside the shadow DOM.
 *  
 *  @return object the core captcha element
 */
this.core = function() {
	var noAccount = this.type.endsWith("(no account)");
	var dom = (<?php echo ( $params["muioverridecssmethod"] == 'shadow-dom' ? 'true' : 'false' ); ?> && noAccount ? document.getElementById('wordpress_file_upload_block_$ID').dom : document);
	if (this.type == "RecaptchaV1") return dom.getElementById("recaptcha_$ID_div");
	else if (this.type == "RecaptchaV1 (no account)") return dom.getElementById("captcha_$ID_frame");
	else if (this.type == "RecaptchaV2") return dom.getElementById("recaptcha_$ID_div");
	else if (this.type == "RecaptchaV2 (no account)") return dom.getElementById("captcha_$ID_frame");
	else return null;
}

/**
 *  resizes the captcha
 *  
 *  Sometimes based on user input the captcha may require resizing. For the
 *  moment this happens only for "RecaptchaV2 (no account)".
 *  
 *  @param width int the new width of the core captcha element
 *  @param height int the new height of the core captcha element
 *  
 *  @return void
 */
this.resize = function(width, height) {
	var dom = (<?php echo ( $params["muioverridecssmethod"] == 'shadow-dom' ? 'true' : 'false' ); ?> ? document.getElementById('wordpress_file_upload_block_$ID').dom : document);
	if (this.type == "RecaptchaV2 (no account)") {
		var frame = dom.getElementById('captcha_$ID_frame');
		var inner = dom.getElementById('captcha_$ID_inner');
//		frame.style.width = width + 'px';
		frame.style.height = (height + 10) + 'px';
//		inner.style.width = width + 'px';		
	}
}
/* do not change this line */}
</script><?php /****************************************************************
               the following lines contain the HTML output 
****************************************************************************/ ?>
<?php if ( $type == "RecaptchaV1" ): ?>
<div id="captcha_$ID" class="file_captcha" style="<?php echo esc_html($styles); ?>">
	<input id="captcha_$ID_mode" type="hidden" value="V1" />
	<div id="captcha_$ID_inner" class="file_captcha_inner<?php echo ( $sitekey == '' ? ' file_captcha_inner_empty' : '' ); ?>">
		<div id="captcha_$ID_empty_overlay" class="file_captcha_overlay_v1"<?php echo ( $sitekey == '' ? '' : ' onclick="if (this.parentNode.className == \'file_captcha_inner file_captcha_inner_empty\') wfu_set_captcha_state($ID, \'idle\', \'false\');"' ); ?>>
			<label id="captcha_$ID_overlay_error_label" class="file_captcha_overlay_v1_errors" title="<?php echo $error_text; ?>"><?php echo $error_text; ?></label>
		</div>
		<?php if ( $sitekey != "" ): ?>
		<div id="recaptcha_$ID_div"></div>
		<?php endif ?>
	</div>
</div>
<?php elseif ( $type == "RecaptchaV1 (no account)" ): ?>
<div id="captcha_$ID" class="file_captcha" style="<?php echo esc_html($styles); ?>">
	<input id="captcha_$ID_mode" type="hidden" value="V1 (no account)" />
	<div id="captcha_$ID_inner" class="file_captcha_inner">
		<div id="captcha_$ID_empty_overlay" class="file_captcha_overlay_v1" onclick="if (this.parentNode.className == 'file_captcha_inner file_captcha_inner_empty') wfu_set_captcha_state($ID, 'idle', 'false');">	
			<label id="captcha_$ID_overlay_error_label" class="file_captcha_overlay_v1_errors">Errors: <?php echo WFU_ERROR_CAPTCHA_UNKNOWNERROR; ?></label>
		</div>
		<iframe id="captcha_$ID_frame" src="<?php echo $frameURL; ?>" scrolling="no" width="318" height="129"></iframe>
	</div>
</div>
<?php elseif ( $type == "RecaptchaV2" ): ?>
<div id="captcha_$ID" class="file_captchav2" style="<?php echo esc_html($styles); ?>">
	<input id="captcha_$ID_mode" type="hidden" value="V2" />
	<div id="captcha_$ID_inner" class="file_captcha_inner_v2<?php echo ( $sitekey == '' ? ' file_captcha_inner_v2_empty' : '' ); ?>">
		<div id="captcha_$ID_empty_overlay" class="file_captcha_overlay_v2"<?php echo ( $sitekey == '' ? '' : ' onclick="if (this.parentNode.className == \'file_captcha_inner_v2 file_captcha_inner_v2_empty\') wfu_set_captcha_state($ID, \'idle\', \'false\');"' ); ?>>
			<label id="captcha_$ID_overlay_error_label" class="file_captcha_overlay_v2_errors" title="<?php echo $error_text; ?>"><?php echo $error_text; ?></label>
		</div>
		<?php if ( !$V2unsupported && $sitekey != "" ): ?>
		<?php if ( $params["muioverridecssmethod"] == 'shadow-dom' ): ?>
		<?php /* In case of shadow DOM we do not create the div core captcha
		element inside the shadow DOM, but we put it in a slot, so here we set
		the slot reference. */ ?>
		<slot name="recaptcha_$ID_slot"></slot>
		<?php /* This is the div core captcha element which we put inside a
		slot. This code is inside a <shadowdom_pre> tag, so the plugin will put
		it outside the shadow DOM, right after it. */ ?>
		<shadowdom_pre><div id="recaptcha_$ID_div" slot="recaptcha_$ID_slot"></div></shadowdom_pre>
		<?php else : ?>
		<div id="recaptcha_$ID_div"></div>
		<?php endif ?>
		<?php endif ?>
	</div>
</div>
<?php elseif ( $type == "RecaptchaV2 (no account)" ): ?>
<div id="captcha_$ID" class="file_captchav2" style="<?php echo esc_html($styles); ?>">
	<input id="captcha_$ID_mode" type="hidden" value="V2 (no account)" />
	<div id="captcha_$ID_inner" class="file_captcha_inner_v2">
		<div id="captcha_$ID_empty_overlay" class="file_captcha_overlay_v2" onclick="if (this.parentNode.className == 'file_captcha_inner_v2 file_captcha_inner_v2_empty') wfu_set_captcha_state($ID, 'idle', 'false');">
			<label id="captcha_$ID_overlay_error_label" class="file_captcha_overlay_v2_errors">Errors: <?php echo WFU_ERROR_CAPTCHA_UNKNOWNERROR; ?></label>
		</div>
		<iframe id="captcha_$ID_frame" src="<?php echo $frameURL; ?>" scrolling="no"></iframe>
	</div>
</div>
<?php endif ?>
<?php /*************************************************************************
                            end of HTML output 
*****************************************************************************/ }

function wfu_webcam_template($data) {?>
<?php /*************************************************************************
          the following lines contain initialization of PHP variables
*******************************************************************************/
/* do not change this line */extract($data);
/*
 *  The following variables are available for use:
 *  
 *  @var $ID int the upload ID
 *  @var $width string assigned width of webcam element
 *  @var $height string assigned height of webcam element
 *  @var $responsive bool true if responsive mode is enabled
 *  @var $testmode bool true if the plugin is in test mode
 *  @var $index int the index of occurrence of the element inside the plugin,
 *       in case that it appears more than once
 *  @var $params array all plugin's attributes defined through the shortcode
 *  
 *  It is noted that $ID can also be used inside CSS, Javascript and HTML code.
 */
	$styles = "";
	if ( $width != "" ) $styles = "width: $width; ";
	if ( $height != "" ) $styles .= "height: $height; ";
/*******************************************************************************
              the following lines contain CSS styling rules
*********************************************************************/ ?><style>
div.wfu_file_webcam_inner {
	position: relative;
	background: none;
	border: none;
	padding: 0;
	margin: 0;
	width: 100%;
	height: 100%;
}

div.wfu_webcam_notsupported {
	border: 1px inset;
}

div.wfu_webcam_notsupported label.wfu_webcam_notsupported_label {
	display: inline !important;
	font-size: smaller;
	color: red;
}

div.wfu_file_webcam_off {
	width: 100%;
	height: 100%;
	margin: 0;
	padding: 0;
	border: 0;
	background-color: black;
}

div.wfu_file_webcam_off img {
	max-width: 100%;
	max-height: 100%;
	padding: 0;
	margin: 0;
}

div.wfu_file_webcam_off svg {
	position: absolute;
	top: 0;
	left: 0;
	fill: rgba(255, 255, 255, 0.5);
	width: 100%;
	height: 100%;
	padding: 0;
	margin: 0;
}

div.wfu_file_webcam_wrapper {
	display: flex;
	position: relative;
	align-items: center;
	justify-content: center;
	width: 100%;
	height: 100%;
	background: <?php echo $params["webcambg"] ?>;
}

div.wfu_file_webcam_image {
	display: flex;
	position: absolute;
	align-items: center;
	justify-content: center;
	width: 100%;
	height: 100%;
	top: 0;
	left: 0;
	background: <?php echo $params["webcambg"] ?>;
}

img.wfu_file_webcam_screenshot {
	max-width: 100%;
	max-height: 100%;
}

div.wfu_file_webcam_nav_container {
	position: relative;
	border: none;
	background: none;
	padding: 0;
	margin: 0;
}

div.wfu_file_webcam_nav {
	display: block;
	position: absolute;
	border: none;
	padding: 4px;
	margin: 0;
	left: 0;
	right: 0;
	height: 30px;
	bottom: 0;
	z-index: 1;
	overflow: hidden;
}

div.wfu_rec_ready {
	background-color: transparent;
}

div.wfu_recording {
	background-color: rgba(0, 0, 0, 0.8);
}

div.wfu_stream_ready {
	background-color: rgba(0, 0, 0, 0.8);
	display: none;
}

div.wfu_file_webcam_inner:hover div.wfu_stream_ready {
	display: block;
}

svg.wfu_file_webcam_btn {
	width: 22px;
	height: 22px;
}

svg.wfu_file_webcam_btn, svg.wfu_file_webcam_btn_disabled {
	float: left;
	height: 100%;
}

svg.wfu_file_webcam_btn:hover {
	border-radius: 4px;
	box-shadow: 0px 0px 4px #aaa;
}

svg.wfu_file_webcam_btn_onoff {
	fill: white;
	position: absolute;
	display: none;
	height: 22px;
	width: 22px;
	top: 2px;
	right: 2px;
	padding: 0 0 2px 3px;
	z-index: 1;
}

div.wfu_file_webcam_inner:hover svg.wfu_file_webcam_btn_onoff {
	display: block;
}

svg.wfu_file_webcam_btn_switchcam {
	fill: white;
	position: absolute;
	display: none;
	height: 22px;
	width: 22px;
	top: 2px;
	left: 2px;
	padding: 0;
	z-index: 1;
}

div.wfu_file_webcam_inner:hover svg.wfu_file_webcam_btn_switchcam {
	display: block;
}

svg.wfu_file_webcam_btn_video {
	fill: white;
	padding: 2px;
}

svg.wfu_file_webcam_btn_video_disabled {
	fill: rgba(255, 255, 255, 0.3);
	padding: 2px;
}

svg.wfu_file_webcam_btn_record {
	fill: red;
}

svg.wfu_recording {
	animation: blink-animation 1s steps(3, start) infinite;
	-webkit-animation: blink-animation 1s steps(3, start) infinite;
}

svg.wfu_recording:hover {
	border-radius: 0px;
	box-shadow: none;
}

@keyframes blink-animation {
	to { visibility: hidden; }
}

@-webkit-keyframes blink-animation {
	to { visibility: hidden; }
}

svg.wfu_file_webcam_btn_stop {
	fill: white;
}

svg.wfu_file_webcam_btn_play {
	fill: limegreen;
}

svg.wfu_file_webcam_btn_play_disabled {
	fill: rgba(255, 255, 255, 0.3);
}

svg.wfu_file_webcam_btn_pause {
	fill: white;
}

svg.wfu_file_webcam_btn_pause_disabled {
	fill: rgba(255, 255, 255, 0.3);
}

div.wfu_file_webcam_btn_pos {
	position: relative;
	float: left;
	background: none;
	border: none;
	margin: 0 8px 0 3px;
	padding: 0;
	width: calc(100% - 200px);
	max-width: 100px;
	height: 100%;
}

svg.wfu_file_webcam_btn_bar {
	position: absolute;
	height: 100%;
	top: 0;
	width: calc(100% + 5px);
	fill: white;
}

svg.wfu_file_webcam_btn_pointer {
	position: absolute;
	top: 4px;
	bottom: 4px;
	width: 5px;
	height: calc(100% - 8px);
	fill: white;
}

svg.wfu_file_webcam_btn_back {
	fill: white;
	padding: 0 2px;
}

svg.wfu_file_webcam_btn_fwd {
	fill: white;
	padding: 0 2px;
}

video.wfu_file_webcam_box {
	max-width: 100%;
	max-height: 100%;
	padding: 0;
	margin: 0;
}

div.wfu_file_webcam_btn_time {
	position: relative;
	float: right;
	background: none;
	border: none;
	margin: 0;
	padding: 0;
	height: 100%;
}

table.wfu_file_webcam_btn_time_tb {
	margin: 0;
	padding: 0;
	border: none;
	border-collapse: collapse;
	background: none;
	height: 100%;
}

tr.wfu_file_webcam_btn_time_tr {
	border: none;
	padding: 0;
	background: none;
}

td.wfu_file_webcam_btn_time_td {
	border: none;
	padding: 0;
	background: none;
	vertical-align: middle;
}

div.wfu_file_webcam_btn_time label {
	color: white;
	font-size: smaller;
	vertical-align: middle;
}

svg.wfu_file_webcam_btn_picture {
	fill: yellow;
	float: right;
	padding: 2px;
	height: calc(100% - 4px);
}
</style>
<script type="text/javascript">
GlobalData.WFU[$ID].webcam.init = function() {

this.initCallback = function() {
	var dom = (<?php echo ( $params["muioverridecssmethod"] == 'shadow-dom' ? 'true' : 'false' ); ?> ? document.getElementById('wordpress_file_upload_block_$ID').dom : document);
	var container = dom.getElementById("webcam_$ID_inner");
	var video = dom.getElementById("webcam_$ID_box");
	var imgdata = '<svg xmlns="http://www.w3.org/2000/svg" width="' + video.videoWidth + '" height="' + video.videoHeight + '"></svg>';
	var imgblob = new Blob([imgdata], {type: 'image/svg+xml;charset=utf-8'});
	var img = dom.getElementById("webcam_$ID_webcamoff_img");
	img.src = window.URL.createObjectURL(imgblob);
	img.style.width = container.clientWidth + "px";
	img.style.height = container.clientHeight + "px";
}

this.initButtons = function(mode) {
	var dom = (<?php echo ( $params["muioverridecssmethod"] == 'shadow-dom' ? 'true' : 'false' ); ?> ? document.getElementById('wordpress_file_upload_block_$ID').dom : document);
	if (dom.getElementById("webcam_$ID_btns_converted").value != "1") {
		wfu_webcam_init_svginjector();
		SVGInjector(dom.getElementById("webcam_$ID_btns"));
		dom.getElementById("webcam_$ID_btns_converted").value = "1";
		//fix for iOS devices to correctly load svgs
		setTimeout(()=> this.updateButtonStatus("redefine"), 1000);
	}
	if (mode == "capture video") this.updateButtonStatus("idle_only_video");
	else if (mode == "take photos") this.updateButtonStatus("idle_only_pictures");
	else if (mode == "both") this.updateButtonStatus("idle_video_and_pictures");
	else this.updateButtonStatus("idle_only_video");
}

this.updateStatus = function(status) {
	var dom = (<?php echo ( $params["muioverridecssmethod"] == 'shadow-dom' ? 'true' : 'false' ); ?> ? document.getElementById('wordpress_file_upload_block_$ID').dom : document);
	var container = dom.getElementById("webcam_$ID_inner");
	var wrapper = dom.getElementById("webcam_$ID_wrapper");
	var video = dom.getElementById("webcam_$ID_box");
	var webcamoff = dom.getElementById("webcam_$ID_webcamoff");
	if (status == "idle") {
		webcamoff.style.display = "none";
		wrapper.style.display = "flex";
		video.muted = true;
	}
	else if (status == "off") {
		var img = dom.getElementById("webcam_$ID_webcamoff_img");
		img.style.width = container.clientWidth + "px";
		img.style.height = container.clientHeight + "px";
		video.pause();
		video.src = "";
		video.ontimeupdate = null;
		video.onended = null;
		video.onloadeddata = null;
		video.onerror = null;
		video.load();
		this.updateButtonStatus("hidden");
		wrapper.style.display = "none";
		webcamoff.style.display = "block";
	}
	else if (status == "video_notsupported") {
		container.className = "wfu_file_webcam_inner wfu_webcam_notsupported";
	}
}

this.updateButtonStatus = function(status) {
	var dom = (<?php echo ( $params["muioverridecssmethod"] == 'shadow-dom' ? 'true' : 'false' ); ?> ? document.getElementById('wordpress_file_upload_block_$ID').dom : document);
	var switchcam = dom.getElementById("webcam_$ID_btn_switchcam");
	var onoff = dom.getElementById("webcam_$ID_btn_onoff");
	var nav = dom.getElementById("webcam_$ID_nav");
	var vid = dom.getElementById("webcam_$ID_btn_video");
	var rec = dom.getElementById("webcam_$ID_btn_record");
	var play = dom.getElementById("webcam_$ID_btn_play");
	var stop = dom.getElementById("webcam_$ID_btn_stop");
	var pause = dom.getElementById("webcam_$ID_btn_pause");
	var pos = dom.getElementById("webcam_$ID_btn_pos");
	var back = dom.getElementById("webcam_$ID_btn_back");
	var fwd = dom.getElementById("webcam_$ID_btn_fwd");
	var tim = dom.getElementById("webcam_$ID_btn_time");
	var pic = dom.getElementById("webcam_$ID_btn_picture");
	var image = dom.getElementById("webcam_$ID_image");
	var screenshot = dom.getElementById("webcam_$ID_screenshot");
	var bar = pos.querySelector(".wfu_file_webcam_btn_bar");
	var pointer = dom.getElementById("webcam_$ID_btn_pointer");
	var webcamOff = dom.getElementById("webcam_$ID_webcamoff");
	
	if (switchcam) switchcam.style.display = "block";
	onoff.style.display = "block";
	//buttons are hidden
	if (status == "hidden") {
		nav.style.display = "none";
		if (switchcam) switchcam.style.display = "none";
	}
	//video recording on progress
	else if (status == "recording") {
		nav.removeAttribute("style");
		nav.className = "wfu_file_webcam_nav wfu_recording";
		vid.style.display = "none";
		rec.style.display = "block";
		rec.style.visibility = "visible";
		rec.setAttribute("class", "wfu_file_webcam_btn wfu_file_webcam_btn_record wfu_recording");
		stop.style.display = "block";
		stop.style.visibility = "visible";
		play.style.display = "block";
		play.style.visibility = "hidden";
		pause.style.display = "block";
		pause.style.visibility = "hidden";
		pos.style.display = "block";
		pos.style.visibility = "hidden";
		back.style.display = "block";
		back.style.visibility = "hidden";
		fwd.style.display = "block";
		fwd.style.visibility = "hidden";
		tim.style.display = "block";
		tim.style.visibility = "visible";
		pic.style.display = "none";
		image.style.display = "none";
	}
	//video recording finished
	else if (status == "after_recording") {
		nav.removeAttribute("style");
		nav.className = "wfu_file_webcam_nav wfu_stream_ready";
		vid.style.display = "block";
		vid.setAttribute("class", "wfu_file_webcam_btn wfu_file_webcam_btn_video");
		rec.style.display = "none";
		stop.style.display = "block";
		stop.style.visibility = "hidden";
		play.style.display = "block";
		play.style.visibility = "hidden";
		pause.style.display = "block";
		pause.style.visibility = "hidden";
		pos.style.display = "block";
		pos.style.visibility = "hidden";
		back.style.display = "block";
		back.style.visibility = "hidden";
		fwd.style.display = "block";
		fwd.style.visibility = "hidden";
		tim.style.display = "block";
		tim.style.visibility = "hidden";
		pic.style.display = "none";
		image.style.display = "flex";
	}
	//video is available for playback
	else if (status == "ready_playback") {
		nav.removeAttribute("style");
		nav.className = "wfu_file_webcam_nav wfu_stream_ready";
		vid.style.display = "block";
		vid.setAttribute("class", "wfu_file_webcam_btn wfu_file_webcam_btn_video");
		rec.style.display = "none";
		stop.style.display = "block";
		stop.style.visibility = "hidden";
		play.style.display = "block";
		play.style.visibility = "visible";
		play.setAttribute("class", "wfu_file_webcam_btn wfu_file_webcam_btn_play");
		pause.style.display = "block";
		pause.style.visibility = "visible";
		pause.setAttribute("class", "wfu_file_webcam_btn_disabled wfu_file_webcam_btn_pause_disabled");
		pos.style.display = "block";
		pos.style.visibility = "visible";
		back.style.display = "block";
		back.style.visibility = "visible";
		fwd.style.display = "block";
		fwd.style.visibility = "visible";
		tim.style.display = "block";
		tim.style.visibility = "visible";
		pic.style.display = "none";
		image.style.display = "none";
	}
	//a screenshot has been captured
	else if (status == "after_screenshot") {
		nav.removeAttribute("style");
		nav.className = "wfu_file_webcam_nav wfu_stream_ready";
		vid.style.display = "block";
		vid.setAttribute("class", "wfu_file_webcam_btn wfu_file_webcam_btn_video");
		rec.style.display = "none";
		stop.style.display = "block";
		stop.style.visibility = "hidden";
		play.style.display = "block";
		play.style.visibility = "hidden";
		pause.style.display = "block";
		pause.style.visibility = "hidden";
		pos.style.display = "block";
		pos.style.visibility = "hidden";
		back.style.display = "block";
		back.style.visibility = "hidden";
		fwd.style.display = "block";
		fwd.style.visibility = "hidden";
		tim.style.display = "block";
		tim.style.visibility = "hidden";
		pic.style.display = "none";
		image.style.display = "flex";
	}
	//video playback on progress
	else if (status == "playing") {
		nav.removeAttribute("style");
		nav.className = "wfu_file_webcam_nav wfu_stream_ready";
		vid.style.display = "block";
		vid.setAttribute("class", "wfu_file_webcam_btn_disabled wfu_file_webcam_btn_video_disabled");
		rec.style.display = "none";
		stop.style.display = "block";
		stop.style.visibility = "hidden";
		play.style.display = "block";
		play.style.visibility = "visible";
		play.setAttribute("class", "wfu_file_webcam_btn_disabled wfu_file_webcam_btn_play_disabled");
		pause.style.display = "block";
		pause.style.visibility = "visible";
		pause.setAttribute("class", "wfu_file_webcam_btn wfu_file_webcam_btn_pause");
		pos.style.display = "block";
		pos.style.visibility = "visible";
		back.style.display = "block";
		back.style.visibility = "visible";
		fwd.style.display = "block";
		fwd.style.visibility = "visible";
		tim.style.display = "block";
		tim.style.visibility = "visible";
		pic.style.display = "none";
		image.style.display = "none";
	}
	//redefine innerHTML for svgs, this is a fix for iOS devices to correctly
	//load svgs
	else if (status == "redefine") {
		if (switchcam) switchcam.innerHTML = switchcam.innerHTML;
		if (onoff) onoff.innerHTML = onoff.innerHTML;
		if (vid) vid.innerHTML = vid.innerHTML;
		if (rec) rec.innerHTML = rec.innerHTML;
		if (play) play.innerHTML = play.innerHTML;
		if (stop) stop.innerHTML = stop.innerHTML;
		if (pause) pause.innerHTML = pause.innerHTML;
		if (back) back.innerHTML = back.innerHTML;
		if (fwd) fwd.innerHTML = fwd.innerHTML;
		if (pic) pic.innerHTML = pic.innerHTML;
		if (bar) bar.innerHTML = bar.innerHTML;
		if (pointer) pointer.innerHTML = pointer.innerHTML;
		if (webcamOff) webcamOff.innerHTML = webcamOff.innerHTML;
	}
	//idle status, waiting for video recording or screenshot capture
	else {
		nav.removeAttribute("style");
		nav.className = "wfu_file_webcam_nav wfu_rec_ready";
		vid.style.display = "none";
		rec.style.display = "none";
		stop.style.display = "none";
		play.style.display = "none";
		pause.style.display = "none";
		pos.style.display = "none";
		back.style.display = "none";
		fwd.style.display = "none";
		tim.style.display = "none";
		pic.style.display = "none";
		image.style.display = "none";
		if (status == "idle_only_video" || status == "idle_video_and_pictures") {
			rec.style.display = "block";
			rec.setAttribute("class", "wfu_file_webcam_btn wfu_file_webcam_btn_record");
		}
		if (status == "idle_only_pictures" || status == "idle_video_and_pictures") {
			pic.style.display = "block";
		}
	}
}

this.updateTimer = function(time) {
	var dom = (<?php echo ( $params["muioverridecssmethod"] == 'shadow-dom' ? 'true' : 'false' ); ?> ? document.getElementById('wordpress_file_upload_block_$ID').dom : document);
	if (!time) {
		var video = dom.getElementById("webcam_$ID_box");
		time = video.currentTime;
	}
	var hours = Math.floor(time / 3600);
	time -= hours * 3600;
	var minutes = Math.floor(time / 60);
	time -= minutes * 60;
	var secs = Math.floor(time);
	var msecs = (time - Math.floor(time)) * 1000;
	dom.getElementById("webcam_$ID_btn_time_label").innerHTML = (hours > 0 ? hours + ":" : "") + (minutes < 10 ? "0" : "") + minutes + ":" + (secs < 10 ? "0" : "") + secs;
}

this.updatePlayProgress = function(duration) {
	var dom = (<?php echo ( $params["muioverridecssmethod"] == 'shadow-dom' ? 'true' : 'false' ); ?> ? document.getElementById('wordpress_file_upload_block_$ID').dom : document);
	var video = dom.getElementById("webcam_$ID_box");
	var pointer = dom.getElementById("webcam_$ID_btn_pointer");
	duration = (isFinite(video.duration) ? video.duration : duration);
	var pos = Math.round(video.currentTime / duration * 100);
	pointer.style.left = pos + "%";
}

this.setVideoProperties = function(props) {
	var dom = (<?php echo ( $params["muioverridecssmethod"] == 'shadow-dom' ? 'true' : 'false' ); ?> ? document.getElementById('wordpress_file_upload_block_$ID').dom : document);
	var video = dom.getElementById("webcam_$ID_box");
	for (var prop in props) {
		if (props.hasOwnProperty(prop)) {
			if (prop == "srcObject") {
				try {
					video.srcObject = props["srcObject"];
				}
				catch (error) {
					//fallback to the src property if srcObject not supported
					video.srcObject = null;
					video.src = window.URL.createObjectURL(props["srcObject"]);
				}
			}
			else video[prop] = props[prop];
		}
	}
}

this.videoSize = function() {
	var dom = (<?php echo ( $params["muioverridecssmethod"] == 'shadow-dom' ? 'true' : 'false' ); ?> ? document.getElementById('wordpress_file_upload_block_$ID').dom : document);
	var video = dom.getElementById("webcam_$ID_box");
	return {width: video.videoWidth, height: video.videoHeight};
}

this.readyState = function() {
	var dom = (<?php echo ( $params["muioverridecssmethod"] == 'shadow-dom' ? 'true' : 'false' ); ?> ? document.getElementById('wordpress_file_upload_block_$ID').dom : document);
	var video = dom.getElementById("webcam_$ID_box");
	return video.readyState;
}

this.updateImage = function(file) {
	var dom = (<?php echo ( $params["muioverridecssmethod"] == 'shadow-dom' ? 'true' : 'false' ); ?> ? document.getElementById('wordpress_file_upload_block_$ID').dom : document);
	var screenshot = dom.getElementById("webcam_$ID_screenshot");
	var reader = new FileReader();
	var obj = this;
	reader.onload = function(e) {
		screenshot.src = e.target.result;
		obj.updateButtonStatus("after_screenshot");
	}
	reader.readAsDataURL(file);
}

this.screenshot = function(savefunc, image_type) {
	var dom = (<?php echo ( $params["muioverridecssmethod"] == 'shadow-dom' ? 'true' : 'false' ); ?> ? document.getElementById('wordpress_file_upload_block_$ID').dom : document);
	var video = dom.getElementById("webcam_$ID_box");
	var canvas = dom.getElementById("webcam_$ID_canvas");
	var screenshot = dom.getElementById("webcam_$ID_screenshot");
	canvas.width = video.videoWidth;
	canvas.height = video.videoHeight;
	var ctx = canvas.getContext('2d');
	ctx.drawImage(video, 0, 0, video.videoWidth, video.videoHeight);
	screenshot.src = canvas.toDataURL('image/webp');
	if (savefunc != null) {
		//the following commands will initialize toBlob function in case that it
		//does not exist; initialization will be executed only once
		if (!window["wfu_toBlob_function_initialized"]) {
			wfu_webcam_initialize_toBlob();
		}
		if (canvas.toBlob) {
			//convert the captured screenshot into an image file
			canvas.toBlob(
				function(blob) { savefunc(blob); },
				image_type
			);
		}
	}
}

this.play = function() {
	var dom = (<?php echo ( $params["muioverridecssmethod"] == 'shadow-dom' ? 'true' : 'false' ); ?> ? document.getElementById('wordpress_file_upload_block_$ID').dom : document);
	var video = dom.getElementById("webcam_$ID_box");
	video.muted = false;
	video.play();	
}

this.pause = function() {
	var dom = (<?php echo ( $params["muioverridecssmethod"] == 'shadow-dom' ? 'true' : 'false' ); ?> ? document.getElementById('wordpress_file_upload_block_$ID').dom : document);
	var video = dom.getElementById("webcam_$ID_box");
	video.pause();	
}

this.back = function() {
	var dom = (<?php echo ( $params["muioverridecssmethod"] == 'shadow-dom' ? 'true' : 'false' ); ?> ? document.getElementById('wordpress_file_upload_block_$ID').dom : document);
	var video = dom.getElementById("webcam_$ID_box");
	video.src = video.src;
	video.currentTime = 0;
}

this.fwd = function(duration) {
	var dom = (<?php echo ( $params["muioverridecssmethod"] == 'shadow-dom' ? 'true' : 'false' ); ?> ? document.getElementById('wordpress_file_upload_block_$ID').dom : document);
	var video = dom.getElementById("webcam_$ID_box");
	video.currentTime = (isFinite(video.duration) ? video.duration : duration * 2);
}

this.ended = function() {
	var dom = (<?php echo ( $params["muioverridecssmethod"] == 'shadow-dom' ? 'true' : 'false' ); ?> ? document.getElementById('wordpress_file_upload_block_$ID').dom : document);
	var video = dom.getElementById("webcam_$ID_box");
	video.src = video.src;
}
/* do not change this line */}
</script><?php /****************************************************************
               the following lines contain the HTML output 
****************************************************************************/ ?>
<div id="webcam_$ID" class="wfu_file_webcam" style="<?php echo esc_html($styles); ?>">
	<div id="webcam_$ID_inner" class="wfu_file_webcam_inner">
		<label id="webcam_$ID_notsupported" class="wfu_webcam_notsupported_label" style="display:none;"><?php echo WFU_ERROR_WEBCAM_NOTSUPPORTED; ?></label>
		<img id="webcam_$ID_btns" src="<?php echo WFU_IMAGE_MEDIA_BUTTONS; ?>" style="display:none;" />
<?php if ( $params["webcamswitch"] == "true" ): ?>
		<svg viewBox="0 0 8 8" id="webcam_$ID_btn_switchcam" class="wfu_file_webcam_btn wfu_file_webcam_btn_switchcam" onclick="wfu_webcam_switch<?php echo ( WFU_VAR("WFU_WEBCAMSWITCHMODE") == "side" ? "" : "_devices" ) ?>($ID);" style="display:none;"><use xlink:href="#loop-circular"></use><rect width="8" height="8" fill="transparent"><title><?php echo WFU_WEBCAM_SWITCHCAM_BTN; ?></title></rect></svg>
<?php endif ?>
		<svg viewBox="0 0 8 8" id="webcam_$ID_btn_onoff" class="wfu_file_webcam_btn wfu_file_webcam_btn_onoff" onclick="wfu_webcam_onoff($ID);" style="display:none;"><use xlink:href="#power-standby"></use><rect width="8" height="8" fill="transparent"><title><?php echo WFU_WEBCAM_TURNONOFF_BTN; ?></title></rect></svg>
		<div id="webcam_$ID_wrapper" class="wfu_file_webcam_wrapper">
			<div id="webcam_$ID_image" class="wfu_file_webcam_image" style="display:none;">
				<img id="webcam_$ID_screenshot" class="wfu_file_webcam_screenshot" onerror="wfu_webcam_screenshot_error($ID);" />
				<canvas id="webcam_$ID_canvas" style="display:none;"></canvas>
			</div>
			<video playsinline autoplay="true" id="webcam_$ID_box" class="wfu_file_webcam_box"><?php echo WFU_ERROR_WEBCAM_NOTSUPPORTED; ?></video>
		</div>
		<div class="wfu_file_webcam_nav_container">
			<div id="webcam_$ID_nav" class="wfu_file_webcam_nav wfu_rec_ready" style="display:none;">
				<input id="webcam_$ID_btns_converted" type="hidden" value="" />
				<svg viewBox="0 0 8 8" id="webcam_$ID_btn_video" class="wfu_file_webcam_btn wfu_file_webcam_btn_video" onclick="wfu_webcam_golive($ID);"><use xlink:href="#video"></use><rect width="8" height="8" fill="transparent"><title><?php echo WFU_WEBCAM_GOLIVE_BTN; ?></title></rect></svg>
				<svg viewBox="0 0 8 8" id="webcam_$ID_btn_record" class="wfu_file_webcam_btn wfu_file_webcam_btn_record" onclick="wfu_webcam_start_rec($ID);"><use xlink:href="#media-record"></use><rect width="8" height="8" fill="transparent"><title><?php echo WFU_WEBCAM_RECVIDEO_BTN; ?></title></rect></svg>
				<svg viewBox="0 0 8 8" id="webcam_$ID_btn_stop" class="wfu_file_webcam_btn wfu_file_webcam_btn_stop" onclick="wfu_webcam_stop_rec($ID);"><use xlink:href="#media-stop"></use><rect width="8" height="8" fill="transparent"><title><?php echo WFU_WEBCAM_STOPREC_BTN; ?></title></rect></svg>
				<svg viewBox="0 0 8 8" id="webcam_$ID_btn_play" class="wfu_file_webcam_btn wfu_file_webcam_btn_play" onclick="wfu_webcam_play($ID);"><use xlink:href="#media-play"></use><rect width="8" height="8" fill="transparent"><title><?php echo WFU_WEBCAM_PLAY_BTN; ?></title></rect></svg>
				<svg viewBox="0 0 8 8" id="webcam_$ID_btn_pause" class="wfu_file_webcam_btn wfu_file_webcam_btn_pause" onclick="wfu_webcam_pause($ID);"><use xlink:href="#media-pause"></use><rect width="8" height="8" fill="transparent"><title><?php echo WFU_WEBCAM_PAUSE_BTN; ?></title></rect></svg>
				<div id="webcam_$ID_btn_pos" class="wfu_file_webcam_btn_pos">
					<svg viewBox="0 0 8 8" class="wfu_file_webcam_btn_bar" preserveAspectRatio="none"><use xlink:href="#minus"></use></svg>
					<svg viewBox="1 1 6 6" id="webcam_$ID_btn_pointer" class="wfu_file_webcam_btn_pointer" preserveAspectRatio="none"><use xlink:href="#media-stop" transform="rotate(0)"></use></svg>
				</div>
				<svg viewBox="0 0 8 8" id="webcam_$ID_btn_back" class="wfu_file_webcam_btn wfu_file_webcam_btn_back" onclick="wfu_webcam_back($ID);"><use xlink:href="#media-skip-backward"></use><rect width="8" height="8" fill="transparent"><title><?php echo WFU_WEBCAM_GOBACK_BTN; ?></title></rect></svg>
				<svg viewBox="0 0 8 8" id="webcam_$ID_btn_fwd" class="wfu_file_webcam_btn wfu_file_webcam_btn_fwd" onclick="wfu_webcam_fwd($ID);"><use xlink:href="#media-skip-forward"></use><rect width="8" height="8" fill="transparent"><title><?php echo WFU_WEBCAM_GOFWD_BTN; ?></title></rect></svg>
				<svg viewBox="0 0 8 8" id="webcam_$ID_btn_picture" class="wfu_file_webcam_btn wfu_file_webcam_btn_picture" onclick="wfu_webcam_take_picture($ID);"><use xlink:href="#aperture"></use><rect width="8" height="8" fill="transparent"><title><?php echo WFU_WEBCAM_TAKEPIC_BTN; ?></title></rect></svg>
				<div id="webcam_$ID_btn_time" class="wfu_file_webcam_btn_time">
					<table class="wfu_file_webcam_btn_time_tb">
						<tbody>
							<tr class="wfu_file_webcam_btn_time_tr">
								<td class="wfu_file_webcam_btn_time_td">
									<label id="webcam_$ID_btn_time_label">00:00</label>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div id="webcam_$ID_webcamoff" class="wfu_file_webcam_off" style="display:none;">
			<svg viewBox="-2 -2 12 12"><use xlink:href="#video"></use></svg>
			<img id="webcam_$ID_webcamoff_img" src="" />
		</div>
	</div>
</div>
<?php }

function wfu_message_template($data) {?>
<?php
	$this->add_theme_props($data);
	$data["forceSpecificity"] = $data["params"]["muioverridecssmethod"];
	unset($data["params"]);
	$dataEnc = wfu_encode_array_to_string($data);
?><style></style>
<script type="text/javascript">
GlobalData.WFU[$ID].message.init = function() {
	
	let data = JSON.parse(wfu_plugin_decode_string('<?php echo $dataEnc; ?>'));
	data.slot = 'renderMessage';
	wfu_create_react_dom('r_wfu_message_$ID', data);
	wfu_render_react_component(data);

	this.update = function(data) { GlobalData.WFU[$ID].RR('message')?.update(data, this._getFiles); }
	this.reset = function() { GlobalData.WFU[$ID].RR('message')?.reset(); }
	this._getFiles = () => ( wfu_get_filelist($ID) );
}
</script>
<div id="r_wfu_message_$ID"></div>
<?php }

function wfu_consent_template($data) {?>
<?php
	$this->add_theme_props($data);
	$data["consentquestion"] = $data["params"]["consentquestion"];
	$data["consentdisclaimer"] = $data["params"]["consentdisclaimer"];
	$data["forceSpecificity"] = $data["params"]["muioverridecssmethod"];
	unset($data["params"]);
	$data["consentyes_label"] = WFU_CONSENTYES;
	$data["consentno_label"] = WFU_CONSENTNO;
	$dataEnc = wfu_encode_array_to_string($data);
?><style></style>
<script type="text/javascript">
GlobalData.WFU[$ID].consent.init = function() {

	let data = JSON.parse(wfu_plugin_decode_string('<?php echo $dataEnc; ?>'));
	data.slot = 'renderConsent';
	wfu_create_react_dom('r_wfu_consent_$ID', data);
	wfu_render_react_component(data);

	this.attachActions = function(completeaction) { GlobalData.WFU[$ID].RR('consent')?.attachActions(completeaction); }
	this.consentCompleted = function() { return GlobalData.WFU[$ID].RR('consent')?.consentCompleted(); }
	this.update = function(action) { GlobalData.WFU[$ID].RR('consent')?.update(action); }
}
</script>
<div id="r_wfu_consent_$ID"></div>
<?php }

}