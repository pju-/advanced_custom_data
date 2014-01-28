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
	
	if ($acd_type) {
		$msg = saveAcds();
	}
}
printAdminHeader('overview',gettext('Advanced Custom Data'));
?>
<script type="text/javascript">
	//<!-- <![CDATA[
	
	(function($) {
		function showoption(name,set,target) {
			if(set[name]) {
				set[name].appendTo(target).show();
				return true;
			} else {
				return false;
			}
		}
		
		$(document).ready(function() {
			var tsel = $("#acd_typesel"),
				table = $("#acd_admin_newfield-table"),
			 	options = $(".acd_options"),
				optarr = new Object;

			options.hide().each(function(i) {
				$this = $(this);
				var str = $this.attr('id');
				var spl = str.split("_");
				str = spl[spl.length-1];
				optarr[str] = $this;
				$this.remove();
			});

			tsel.change(function() {
				var sel = tsel.find("option:selected").attr("value");
				options.remove();
				showoption(sel,optarr,table);
			});
		});
	})(jQuery);
	
	// ]]> -->
</script>
</head>
<body>
<?php
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
	echo "\n<p class='messagebox fade-message'>Entry created.</p>";
}
?>

<form id="acd_field_update" action="?update&amp;tab=create_fields" method="post">
<?php XSRFToken('advanced_custom_data');
$acd = advanced_custom_data::getAllAcds();
 ?>
<h2>New Field:</h2>
	<table id="acd_admin_newfield-table">
	<tr>
		<td class="col1"><label for="acd_category">Category:</label></td>
		<td class="col2"><select name="acd_category" size="1">
			<option value="both" selected="selected">Both</option>
			<option value="images">Images</option>
			<option value="albums">Albums</option>
		</select></td>
	</tr>
	<tr>
		<td class="col1"><label for="acd_title">Title:</label></td>
		<td class="col2"><input name="acd_title" type="text" size="32" maxlength="32"></input></td>
		<td class="col3"><p>The name of the new custom data entry. Also used to fetch it in the template.</p></td>
	</tr>
	<tr>
		<td class="col1"><label for="acd_desc">Description:</label></td>
		<td class="col2"><input name="acd_desc" type="text" size="32" maxlength="128"></input></td>
		<td class="col3"><p>A description for the new custom data entry.</p></td>
	</tr>
	<tr>
		<td class="col1"><label for="acd_type">Type:</label></td>
		<td class="col2"><select name="acd_type" id="acd_typesel" size="1">
			<option value="checkbox">Checkbox</option>
			<option value="input">Text</option>
			<option value="textarea">Textarea</option>
		</select></td>
		<td class="col3"><p>The input type.</p></td>
	</tr>
	<tr id='acd_options_input' class='acd_options'>
		<td></td>
		<td>
			<table>
				<tr>
					<td class="col1"><label for="acd_opt_input_length">Length:</label></td>
					<td class="col2"><input name="acd_opt_input_length" type="text" size="3" maxlength="3" value="32"></input></td>
				</tr>
			</table>
		<td class="col3"><p></p></td>
	</tr>
	<tr id='acd_options_textarea' class='acd_options'>
		<td></td>
		<td>
			<table>
				<tr>
					<td class="col1"><label for="acd_opt_textarea_cols">Columns:<label></td>
					<td class="col2"><input name="acd_opt_textarea_cols" type="text" size="2" maxlength="2" value="32"></input></td>
				</tr>
				<tr>
					<td class="col1"><label for="acd_opt_textarea_rows">Rows:</label></td>
					<td class="col2"><input name="acd_opt_textarea_rows" type="text" size="2" maxlength="2" value="5"></input></td>
				</tr>
			</table>
		<td class="col3"><p></p></td>
	</tr>
	
	</table>

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
