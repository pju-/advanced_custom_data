<?php
/**
* Template for advanced custom data management
* @package plugins
*/
// force UTF-8 Ø
define('OFFSET_PATH', 3);
require_once("../../zp-core/admin-globals.php");
require_once('functions.php');
require_once(SERVERPATH.'/'.ZENFOLDER.'/template-functions.php');


admin_securityChecks($localrights, $return = currentRelativeURL());

$msg ="";

$zenphoto_tabs['overview']['subtabs'] = array(
	gettext('Create new ACD entry') => '../plugins/advanced_custom_data/acd_create.php?page=overview&amp;tab=create_fields',
	gettext('Manage exisiting ACD entries') => '../plugins/advanced_custom_data/acd_edit.php?page=overview&amp;tab=manage_fields');

if (isset($_GET['update'])) {
	XSRFdefender('advanced_custom_data');
	$acd_type = $_POST['acd_type'];
	$delete = array();
	for($i = 0; $i<$_POST['acd_fields_total']; $i++) {
		if($_POST['delete-'.$i]) {
			$delete[] = $_POST['id-'.$i];
		}
	}
	
	if(count($delete)) {
		$msg = saveAcds($delete);
	} else if ($acd_type) {
		$msg = saveAcds();
	}
}

printAdminHeader('overview',gettext('Advanced Custom Data'));
echo "\n</head>";
echo "\n<body>";

printLogoAndLinks();
echo "\n" . '<div id="main">';
printTabs();
echo "\n" . '<div id="content">';
?>
<?php printSubtabs(); ?>
<div class="tabbox">


<?php
zp_apply_filter('admin_note','acf', '');
$clear = gettext('Advanced custom data');
echo "\n<h2>".$clear."</h2>";
if($msg) {
	echo "\n<p class='errorbox fade-message'>".$msg."</p>";
} elseif ($msg === FALSE) {
	echo "\n<p class='messagebox fade-message'>Entries updated.</p>";
}
?>

<form id="acd_field_update" action="?update&amp;tab=manage_fields" method="post">
	<?php XSRFToken('advanced_custom_data'); ?>
<?php
$acd = advanced_custom_data::getAllAcds();
if($acd) : ?>
	<table id='acd_admin_list-table'>
	<tr>
	<th>Category</th>
	<th>Name</th>
	<th>Description</th>
	<th>Type</th>
	<th>Options</th>
	<th class="del">Delete</th>
	</tr>
	<?php
	$count = 0;
	$opt ="";
	foreach($acd as $id=>$row) :
		echo "\n<tr>";
		foreach($row as $key=>$value) {
			if(strpos($key, 'acd_opt') === false && $key != 'aux') {
				echo "\n<td class='value'>".$value."</td>";
			} elseif ($value) {
				$str = explode("_",$key);
				$opt.=$str[count($str)-1].": ".$value.", ";
			}
		} ?>
		<td class="value"><?php echo $opt; ?></td>
		<td class="del">
			<input type="hidden" name="id-<?php echo $count; ?>" value="<?php echo $id; ?>">
			<input type="checkbox" name="delete-<?php echo $count; ?>">
		</td>
		</tr>
	<?php 
	$opt = "";
	$count++;
	endforeach; ?>
	</table>
	<input type="hidden" name="acd_fields_total" value="<?php echo $count; ?>">
<?php else: ?>
	<p>No custom data fields found.</p>
<?php endif; ?>

	<p class="buttons">
		<a title="<?php echo gettext('Back to the overview'); ?>"href="<?php echo WEBPATH . '/' . ZENFOLDER . '/admin.php'; ?>"> <img src="<?php echo FULLWEBPATH . '/' . ZENFOLDER; ?>/images/cache.png" alt="" />
			<strong><?php echo gettext("Back"); ?> </strong>
		</a>
	</p>
	<p class="buttons">
		<button class="tooltip" type="submit" title="<?php echo "Submit changes" ?>" >
			<img src="<?php echo WEBPATH.'/'.ZENFOLDER; ?>/images/pass.png" alt="" />
			<?php echo gettext("Apply"); ?>
		</button>
	</p>
	<br clear="all">
</form>


<?php
echo "\n" . '</div>';
echo "\n" . '</div>';
echo "\n" . '</div>';

printAdminFooter();

echo "\n</body>";
echo "\n</head>";
?>
