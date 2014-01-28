<?php
/**
* Provides custom data fields.
*
* @package plugins
* @author Philip Ullrich
*/
$plugin_is_filter = 9|CLASS_PLUGIN;
$plugin_description = gettext("Provides advanced custom data functionality.");
$plugin_author = "Philip Ullrich";
$plugin_version = '0.3.5';

$option_interface = 'advanced_custom_data';

zp_register_filter('admin_utilities_buttons', 'advanced_custom_data::overviewbutton');
zp_register_filter('save_album_custom_data', 'custom_data_save_album');
zp_register_filter('edit_album_custom_data', 'custom_data_edit_album');
zp_register_filter('save_image_custom_data', 'custom_data_save_image');
zp_register_filter('edit_image_custom_data', 'custom_data_edit_image');
zp_register_filter('admin_head', 'custom_admin_head');


/**
* Returns a processed custom data item
* called when an image is saved on the backend
*
* @param string $discard always empty
* @param int $i prefix for the image being saved
* @return string
*/
function custom_data_save_image($discard, $i) {
	$result = $_POST[$i.'-custom_data'];
	if(is_array($result)) {
		foreach ($result as &$row) {
			$row = sanitize($row,1);
		}
	}
	return serialize($result);
}

/**
* Returns table row(s) for the edit of an image custom data field
*
* @param string $discard always empty
* @param int $currentimage prefix for the image being edited
* @param object $image the image object
* @return string
*/
function custom_data_edit_image($discard, $image, $currentimage) {
	$answer;
	$current = unserialize($image->getCustomData("all"));
	$acds = advanced_custom_data::getAllAcds("images");

	$checked = 'checked="checked"';
	
	
	$answer = "\n<tr id='acd_admin_table'>
		\n<td id='acd_admin_table_left' align='left' valign='top'>".gettext('Custom Settings:')."</td>
		\n<td id='acd_admin_table_right'>";
		
	foreach($acds as $id=>$row) {
		$title = trim(strtolower($row['acd_title']));
		$answer.= "<div class='acd_admin_table-field'>";
	
		$answer.= "\n<p class='acd_title'><strong>".$row['acd_title']."</strong></p>";
				
		switch($row['acd_type']) {
			
			case "checkbox" :
				$val = (is_array($current) && isset($current[$id])) ? $current[$id] : false;
				$answer .= "\n<input type='checkbox' name='".$currentimage."-custom_data[".$id."]'"
				.(!$val?:$checked)." value='1' />";
				break;
				
			case "input" :
				$val = (is_array($current) && isset($current[$id])) ? $current[$id] : false;
				$size = $row['acd_opt_input_length'] ? $row['acd_opt_input_length'] : 32;
				$answer.= "\n<input type='text' name='".$currentimage."-custom_data[".$id."]' size='".min($size,64)."' maxlength='".$size."' value='".$val."'/>";
				break;
				
			case "textarea" :
				$val = (is_array($current) && isset($current[$id])) ? $current[$id] : false;
				$rows = $row['acd_opt_textarea_rows'] ? $row['acd_opt_textarea_rows'] : 32;
				$cols = $row['acd_opt_textarea_cols'] ? $row['acd_opt_textarea_cols'] : 5;
				$answer.= "\n<textarea name='".$currentimage."-custom_data[".$id."]' cols='".$cols
				."' cols ='".$cols."'>".$val."</textarea>";
				break;
		}
		
		if($row['acd_desc']) { 
			$answer.= "\n<p class='acd_desc'>".$row['acd_desc']."</p>"; 
		}
		$answer.= "\n</div>";
	}
	$answer.= "\n</td>\n</tr>";
	return $answer;
}

/**
* Returns a processed album custom data item
* called when an album is saved on the backend
*
* @param string $discard always empty
* @param int $prefix the prefix for the album being saved
* @return string
*/
function custom_data_save_album($discard, $prefix) {
	$result = $_POST[$prefix.'x_album_custom_data'];
	if(is_array($result)) {
		foreach ($result as &$row) {
			$row=sanitize($row,1);
		}
	}
return serialize($result);
}


/**
* Returns table row(s) for the edit of an album custom data field
*
* @param string $discard always empty
* @param int $prefix prefix of the album being edited
* @param object $album the album object
* @return string
*/
function custom_data_edit_album($discard, $album, $prefix) {
	$answer;
	$acds = advanced_custom_data::getAllAcds("albums");
	$current = unserialize($album->getCustomData("all"));	
	$checked = 'checked="checked"';
	
	
	$answer = "\n<tr id='acd_admin_table'>
		\n<td id='acd_admin_table_left' align='left' valign='top'>".gettext('Custom Settings:')."</td>
		\n<td id='acd_admin_table_right'>";
		
	foreach($acds as $id=>$row) {
		$title = trim(strtolower($row['acd_title']));
		$answer.= "<div class='acd_admin_table-field'>";
		
		$answer.= "\n<p class='acd_title'><strong>".$row['acd_title']."</strong></p>";

		switch($row['acd_type']) {
			
			case "checkbox" :
				$val = (is_array($current) && isset($current[$id])) ? $current[$id] : false;
				$answer .= "\n<input type='checkbox' name='".$prefix."x_album_custom_data[".$id."]'"
				.(!$val?:$checked)." value='1' />";
				break;
				
			case "input" :
				$val = (is_array($current) && isset($current[$id])) ? $current[$id] : false;
				$size = $row['acd_opt_input_length'] ? $row['acd_opt_input_length'] : 32;
				$answer.= "\n<input type='text' name='".$prefix."x_album_custom_data[".$id."]' size='".min($size,64)."' maxlength='".$size."' value='".$val."'/>";
				break;
				
			case "textarea" :
				$val = (is_array($current) && isset($current[$id])) ? $current[$id] : false;
				$rows = $row['acd_opt_textarea_rows'] ? $row['acd_opt_textarea_rows'] : 32;
				$cols = $row['acd_opt_textarea_cols'] ? $row['acd_opt_textarea_cols'] : 5;
				$answer.= "\n<textarea name='".$prefix."x_album_custom_data[".$id."]' cols='".$cols
				."' cols ='".$cols."'>".$val."</textarea>";
				break;
		}
		
		if($row['acd_desc']) { 
			$answer.= "\n<p class='acd_desc'>".$row['acd_desc']."</p>"; 
		}
		$answer.= "\n</div>";
	}
	$answer.= "\n</td>\n</tr>";
	return $answer;
}

function custom_admin_head($discard) {
	echo '<link rel="stylesheet" href="'.WEBPATH.'/plugins/advanced_custom_data/acd_admin.css" type="text/css" />';
}

function getAcd($type, $field = FALSE) {

	$allAcds = advanced_custom_data::getAllAcds();

	if($type == "album") {
		global $_zp_current_album;
		$curAcd = unserialize($_zp_current_album->getCustomData("all"));
	} elseif ($type == "image") {
		global $_zp_current_image;
		$curAcd = unserialize($_zp_current_image->getCustomData("all"));
	} else return false;
	
	if (!is_array($curAcd)) return NULL;
	
	$answer = array();

	foreach($curAcd as $id=>$value) {
		if(isset($allAcds[$id])) {
			$answer[$allAcds[$id]['aux']] = $value;
		}
	}
	
	if ($field && isset($answer[$field])) {
		return $answer[$field];
	} else if ($field) {
		return NULL;
	}
	return $answer;
}


// OPTION INTERFACE CLASS
class advanced_custom_data {
	var $ratingstate;
	/**
	 * class instantiation function
	 *
	 */
	function advanced_custom_data() {
		setOptionDefault('show_button', 1);
	}


	/**
	 * Reports the supported options
	 *
	 * @return array
	 */
	function getOptionsSupported() {
		return array(gettext('new_field') => array('key' => 'show_button', 'type' => OPTION_TYPE_CHECKBOX_ARRAY,
								'checkboxes' => array(gettext("Show Button") => "1"),
								'order' =>1,
								'desc' => gettext('Whether to show the button')),
					);
	}

	function handleOption($option, $currentValue) {
	}

	static function overviewbutton($buttons) {

		$buttons[] = array(
							'category'=>gettext('Advanced Custom Data'),
							'enable'=>'true',
							'button_text'=>gettext('Create ACD entries'),
							'formname'=>'advanced_custom_data_button',
							'action'=>WEBPATH.'/plugins/advanced_custom_data/acd_create.php?page=overview&tab=create_fields',
							'icon'=>'images/arrow_up.png',
							'alt'=>'',
							'hidden'=>'<input type="hidden" name="tab" value="create_fields" />',
							'rights'=>ADMIN_RIGHTS,
							'title'=>'Create new custom data fields'
							);
		$buttons[] = array(
							'category'=>gettext('Advanced Custom Data'),
							'enable'=>'true',
							'button_text'=>gettext('Edit exisiting ACD entries'),
							'formname'=>'advanced_custom_data_button',
							'action'=>WEBPATH.'/plugins/advanced_custom_data/acd_edit.php?page=overview&tab=manage_fields',
							'icon'=>'images/arrow_up.png',
							'alt'=>'',
							'hidden'=>'<input type="hidden" name="tab" value="manage_fields" />',
							'rights'=>ADMIN_RIGHTS,
							'title'=>'Manage your custom data fields'
							);
		return $buttons;
	}
	
	static function getAllAcds($category = FALSE) {
		$acd = array();
		$count = 0;
		$result = query('SELECT * FROM'.prefix('plugin_storage').' WHERE `type`="advanced_custom_data" ');
		while ($row = db_fetch_assoc($result)) {
			$id = $row['id'];
			$data = unserialize($row['data']);
			if (!$category || ($data['acd_category'] == $category) || ($data['acd_category'] == 'both')) {
				$acd[$id]['aux'] = $row['aux'];
				foreach($data as $key=>$value) {
					$acd[$id][$key] = $value;
					$acd[$id][$key] = $value;
				}
			}
		}
		return ($result) ? $acd : false;
	}

}
?>
