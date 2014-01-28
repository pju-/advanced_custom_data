<?php 
/**
* Template for advanced custom data management
* @package plugins
*/
// force UTF-8 Ø
define('OFFSET_PATH', 3);
require_once("../../zp-core/admin-globals.php");
require_once(SERVERPATH.'/'.ZENFOLDER.'/template-functions.php');


admin_securityChecks($localrights, $return = currentRelativeURL());

function loadAlbum($album) {
	global $_zp_current_album, $_zp_current_image, $_zp_gallery, $custom, $enabled;
	$subalbums = $album->getAlbums();
	$started = false;
	$tcount = $count = 0;
	foreach ($subalbums as $folder) {
		$subalbum = new Album($_zp_gallery, $folder);
		if (!$subalbum->isDynamic()) {
			$tcount = $tcount + loadAlbum($subalbum);
		}
	}
	return $count + $tcount;
}

function saveAcds($del = FALSE) {
	$acdimage = array();
	foreach ($_POST as $key=>$value) {
		$acdimage[$key] = sanitize(trim($value));
	}

	if (count($acdimage)>1 && $acdimage['acd_title']) {
		$acdimage['acd_title'] = preg_replace("/[\s\"\']+/","-",$acdimage['acd_title']);
		$sql = 'INSERT INTO '.prefix('plugin_storage').' (`type`, `aux`,`data`) VALUES ("advanced_custom_data",'.db_quote($acdimage['acd_title']).','.db_quote(serialize($acdimage)).')';
		query($sql);
		$msg = FALSE;
	} elseif (!$acdimage['acd_title']) {
		$msg = "No title set. You need to set a title in order to create a new entry.";
	} else {
		$msg = "Error, coud not create new entry.";
	}
	
	if($del) {
		foreach($del as $deli) {
			$sql = 'DELETE FROM'.prefix('plugin_storage').'WHERE `type`="advanced_custom_data" AND `id`='.$deli;
			query($sql);
		}
		$msg = FALSE;
	}
	
	return $msg;
}
?>
