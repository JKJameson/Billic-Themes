<?php
class Themes {
	public $settings = array(
		'name' => 'Themes',
		'admin_menu_category' => 'Settings',
		'admin_menu_name' => 'Themes',
		'description' => 'Customize the appearance of Billic.',
		'admin_menu_icon' => '<i class="icon-magic-wand"></i>',
	);
	function checkPHP($code) {
		global $billic, $db;
		ob_start();
		$eval = @eval('?>' . $code);
		ob_get_clean();
		if ($eval === FALSE) {
			return false;
		} else {
			return true;
		}
	}
	function admin_area() {
		global $billic, $db;
		if (isset($_GET['Name'])) {
			if ($_GET['Name'] == 'Default') {
				$theme = $GLOBALS['billic_theme_default'];
			} else {
				$theme = $db->q('SELECT * FROM `themes` WHERE `name` = ?', urldecode($_GET['Name']));
				$theme = $theme[0];
				if (empty($theme)) {
					err('Theme does not exist');
				}
			}
			if (isset($_GET['AjaxPage'])) {
				$billic->disable_content();
				echo $theme[strtolower($_GET['AjaxPage']) ];
				exit;
			}
			if (isset($_GET['AjaxSave'])) {
				$billic->disable_content();
				$settingName = strtolower($_POST['settingName']);
				if (($settingName == 'header' || $settingName == 'footer' || $settingName == 'admin_header' || $settingName == 'admin_footer') && !$this->checkPHP($_POST['template_data'])) {
					echo 'A PHP error was detected inside the template.';
					exit;
				}
				if (($settingName == 'footer' || $settingName == 'admin_footer') && strpos($_POST['template_data'], '{poweredby}') === FALSE && $billic->allow_unbranding === false) {
					echo 'Your license does not have unbranding. Please put {poweredby} in the footer.';
					exit;
				}
				if (strpos($settingName, 'admin_') !== false && $_POST['themeCopy'] == 1) {
					$_POST['template_data'] = NULL;
				}
				// Important Security: Restrict the settingName to a pre-defined list of mysql columns
				if ($settingName == 'header' || $settingName == 'footer' || $settingName == 'css' || $settingName == 'admin_header' || $settingName == 'admin_footer' || $settingName == 'admin_css') {
					$db->q('UPDATE `themes` SET `' . $settingName . '` = ? WHERE `name` = ?', $_POST['template_data'], urldecode($_GET['Name']));
					echo 'OK';
				} else {
					echo 'Unknown template setting';
				}
				exit;
			}
			$billic->set_title('Admin/Theme ' . safe($theme['name']));
			echo '<h1>Theme ' . safe($theme['name']) . '</h1>';
			$current_module = 'Header';
			if (!empty($_POST['billic_ajax_module'])) {
				$current_module = $_POST['billic_ajax_module'];
			}
			$billic->show_errors();
			echo '<style>#dashboardLoader{left:50%;font-size:25px;margin:5em auto;width:1em;height:1em;border-radius:50%;text-indent:-9999em;-webkit-animation:load4 1.3s infinite linear;animation:load4 1.3s infinite linear;-webkit-transform:translateZ(0);-ms-transform:translateZ(0);transform:translateZ(0)}@-webkit-keyframes load4{0%,100%{box-shadow:0 -3em 0 .2em #074f99,2em -2em 0 0 #074f99,3em 0 0 -.5em #074f99,2em 2em 0 -.5em #074f99,0 3em 0 -.5em #074f99,-2em 2em 0 -.5em #074f99,-3em 0 0 -.5em #074f99,-2em -2em 0 0 #074f99}12.5%{box-shadow:0 -3em 0 0 #074f99,2em -2em 0 .2em #074f99,3em 0 0 0 #074f99,2em 2em 0 -.5em #074f99,0 3em 0 -.5em #074f99,-2em 2em 0 -.5em #074f99,-3em 0 0 -.5em #074f99,-2em -2em 0 -.5em #074f99}25%{box-shadow:0 -3em 0 -.5em #074f99,2em -2em 0 0 #074f99,3em 0 0 .2em #074f99,2em 2em 0 0 #074f99,0 3em 0 -.5em #074f99,-2em 2em 0 -.5em #074f99,-3em 0 0 -.5em #074f99,-2em -2em 0 -.5em #074f99}37.5%{box-shadow:0 -3em 0 -.5em #074f99,2em -2em 0 -.5em #074f99,3em 0 0 0 #074f99,2em 2em 0 .2em #074f99,0 3em 0 0 #074f99,-2em 2em 0 -.5em #074f99,-3em 0 0 -.5em #074f99,-2em -2em 0 -.5em #074f99}50%{box-shadow:0 -3em 0 -.5em #074f99,2em -2em 0 -.5em #074f99,3em 0 0 -.5em #074f99,2em 2em 0 0 #074f99,0 3em 0 .2em #074f99,-2em 2em 0 0 #074f99,-3em 0 0 -.5em #074f99,-2em -2em 0 -.5em #074f99}62.5%{box-shadow:0 -3em 0 -.5em #074f99,2em -2em 0 -.5em #074f99,3em 0 0 -.5em #074f99,2em 2em 0 -.5em #074f99,0 3em 0 0 #074f99,-2em 2em 0 .2em #074f99,-3em 0 0 0 #074f99,-2em -2em 0 -.5em #074f99}75%{box-shadow:0 -3em 0 -.5em #074f99,2em -2em 0 -.5em #074f99,3em 0 0 -.5em #074f99,2em 2em 0 -.5em #074f99,0 3em 0 -.5em #074f99,-2em 2em 0 0 #074f99,-3em 0 0 .2em #074f99,-2em -2em 0 0 #074f99}87.5%{box-shadow:0 -3em 0 0 #074f99,2em -2em 0 -.5em #074f99,3em 0 0 -.5em #074f99,2em 2em 0 -.5em #074f99,0 3em 0 -.5em #074f99,-2em 2em 0 0 #074f99,-3em 0 0 0 #074f99,-2em -2em 0 .2em #074f99}}@keyframes load4{0%,100%{box-shadow:0 -3em 0 .2em #074f99,2em -2em 0 0 #074f99,3em 0 0 -.5em #074f99,2em 2em 0 -.5em #074f99,0 3em 0 -.5em #074f99,-2em 2em 0 -.5em #074f99,-3em 0 0 -.5em #074f99,-2em -2em 0 0 #074f99}12.5%{box-shadow:0 -3em 0 0 #074f99,2em -2em 0 .2em #074f99,3em 0 0 0 #074f99,2em 2em 0 -.5em #074f99,0 3em 0 -.5em #074f99,-2em 2em 0 -.5em #074f99,-3em 0 0 -.5em #074f99,-2em -2em 0 -.5em #074f99}25%{box-shadow:0 -3em 0 -.5em #074f99,2em -2em 0 0 #074f99,3em 0 0 .2em #074f99,2em 2em 0 0 #074f99,0 3em 0 -.5em #074f99,-2em 2em 0 -.5em #074f99,-3em 0 0 -.5em #074f99,-2em -2em 0 -.5em #074f99}37.5%{box-shadow:0 -3em 0 -.5em #074f99,2em -2em 0 -.5em #074f99,3em 0 0 0 #074f99,2em 2em 0 .2em #074f99,0 3em 0 0 #074f99,-2em 2em 0 -.5em #074f99,-3em 0 0 -.5em #074f99,-2em -2em 0 -.5em #074f99}50%{box-shadow:0 -3em 0 -.5em #074f99,2em -2em 0 -.5em #074f99,3em 0 0 -.5em #074f99,2em 2em 0 0 #074f99,0 3em 0 .2em #074f99,-2em 2em 0 0 #074f99,-3em 0 0 -.5em #074f99,-2em -2em 0 -.5em #074f99}62.5%{box-shadow:0 -3em 0 -.5em #074f99,2em -2em 0 -.5em #074f99,3em 0 0 -.5em #074f99,2em 2em 0 -.5em #074f99,0 3em 0 0 #074f99,-2em 2em 0 .2em #074f99,-3em 0 0 0 #074f99,-2em -2em 0 -.5em #074f99}75%{box-shadow:0 -3em 0 -.5em #074f99,2em -2em 0 -.5em #074f99,3em 0 0 -.5em #074f99,2em 2em 0 -.5em #074f99,0 3em 0 -.5em #074f99,-2em 2em 0 0 #074f99,-3em 0 0 .2em #074f99,-2em -2em 0 0 #074f99}87.5%{box-shadow:0 -3em 0 0 #074f99,2em -2em 0 -.5em #074f99,3em 0 0 -.5em #074f99,2em 2em 0 -.5em #074f99,0 3em 0 -.5em #074f99,-2em 2em 0 0 #074f99,-3em 0 0 0 #074f99,-2em -2em 0 .2em #074f99}}</style><script>var themeChanged = false; var themeLoaded = false; function loadSettingsPage(page) { if ($("#saveThemeBtn").hasClass("btn-success") && !confirm("Changing page will discard changes!")) { return; } themeLoaded = false; $("#currentThemeSettingName").html(page.replace(\'_\', \' \'));  $("#themeEditContainer").hide(); $( "#loadingBox" ).html(\'<div id="dashboardLoader">Loading...</div>\'); $("#saveThemeBtn").removeClass( "btn-success" ).addClass( "btn-default disabled" ); $("#saveThemeBtn").html("No Changes"); $.get( "/Admin/Themes/Name/' . urlencode($theme['name']) . '/AjaxPage/"+encodeURIComponent(page)+"/", function( data ) { $( "#loadingBox" ).html(""); editor.getDoc().setValue(data); $("#themeEditContainer").show(); editor.refresh(); if (data=="") { $("#themeEditContainer").hide(); $("#themeCopyContainer").show(); $("#themeCopyCheck").prop(\'checked\', true); } else { if (page.substr(0, 6)=="Admin_") { $("#themeCopyContainer").show(); } else { $("#themeCopyContainer").hide(); } $("#themeCopyCheck").prop(\'checked\', false); } themeChanged = false; themeLoaded = true; }); } addLoadEvent(function() { loadSettingsPage(\'' . $current_module . '\'); });</script><div class="row">';
			if ($_GET['Name'] == 'Default') {
				echo '<button class="btn btn-danger disabled" style="position:fixed;right:100px;z-index: 5000;">Default theme can not be saved</button>';
			} else {
				echo '<button class="btn btn-default disabled" style="position:fixed;right:100px;z-index: 5000;" id="saveThemeBtn" onClick="saveTheme()"><i class=\"icon-save-disk\"></i> No Changes</button>';
			}
			echo '<ul class="nav nav-pills">';
			echo '<li' . ($current_module == 'Header' ? ' class="active"' : '') . '><a href="#" data-toggle="tab" onClick="loadSettingsPage(\'Header\')">Header</a></li>';
			echo '<li' . ($current_module == 'Footer' ? ' class="active"' : '') . '><a href="#" data-toggle="tab" onClick="loadSettingsPage(\'Footer\')">Footer</a></li>';
			echo '<li' . ($current_module == 'CSS' ? ' class="active"' : '') . '><a href="#" data-toggle="tab" onClick="loadSettingsPage(\'CSS\')">CSS</a></li>';
			echo '<li' . ($current_module == 'Admin_Header' ? ' class="active"' : '') . '><a href="#" data-toggle="tab" onClick="loadSettingsPage(\'Admin_Header\')">Admin Header</a></li>';
			echo '<li' . ($current_module == 'Admin_Footer' ? ' class="active"' : '') . '><a href="#" data-toggle="tab" onClick="loadSettingsPage(\'Admin_Footer\')">Admin Footer</a></li>';
			echo '<li' . ($current_module == 'Admin_CSS' ? ' class="active"' : '') . '><a href="#" data-toggle="tab" onClick="loadSettingsPage(\'Admin_CSS\')">Admin CSS</a></li>';
			echo '</ul>';
			echo '</div><div class="tab-content" style="-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;text-align: justify;text-justify: inter-word"><div class="tab-pane active" style="padding:10px"><div id="loadingBox" ></div><div id="themeCopyContainer" style="display:none"><input type="checkbox" id="themeCopyCheck" value="1" onClick="themeCopyCheck()"> Use the same template for <span id="currentThemeSettingName"></span> as your main website.</div><div id="themeEditContainer" style="display:none"><textarea id="themeEditBox" style="width: 100%; height:800px"></textarea></div></div></div>';
?>
<link rel="stylesheet" href="/Modules/Core/codemirror/codemirror.css">
<script src="/Modules/Core/codemirror/codemirror.js"></script>
<script src="/Modules/Core/codemirror/matchbrackets.js"></script>
<script src="/Modules/Core/codemirror/htmlmixed.js"></script>
<script src="/Modules/Core/codemirror/xml.js"></script>
<script src="/Modules/Core/codemirror/javascript.js"></script>
<script src="/Modules/Core/codemirror/css.js"></script>
<script src="/Modules/Core/codemirror/clike.js"></script>
<script src="/Modules/Core/codemirror/php.js"></script>
			
<script>
var editor = CodeMirror.fromTextArea(document.getElementById("themeEditBox"), {
	lineNumbers: true,
	lineWrapping: true,
	mode: "text/html",
	matchBrackets: true,
	indentUnit: 4,
	indentWithTabs: true
});
editor.on("change", function() {
	if (themeLoaded==true && themeChanged==false) {
		enableSaveBtn();
		themeChanged = true;
	}
});
	function themeCopyCheck() {
		if ($("#themeCopyCheck").is(':checked')) {
			if (confirm("Warning: This will delete the data in the textbox if you click save!")) {
				$("#themeEditContainer").hide();
				themeChanged = true;
				enableSaveBtn();
			}
		} else {
			$("#themeCopyCheck").prop('checked', false);	
			$("#themeEditContainer").show();	
		}
	}
	function enableSaveBtn() {
		$("#saveThemeBtn").removeClass( "btn-default disabled" ).addClass( "btn-success" );	
		$("#saveThemeBtn").html("<i class=\"icon-save-disk\"></i> Save Changes &raquo;");
	}
	function saveTheme() {
		var themeCopy = 0;
		if ($("#themeCopyCheck").is(':checked')) {
			themeCopy = 1;	
		}
		var settingName = $("#currentThemeSettingName").text();
		$.post( "/Admin/Themes/Name/<?php echo urlencode($theme['name']); ?>/AjaxSave/", { settingName: settingName.replace(' ', '_'), themeCopy: themeCopy, template_data: editor.getValue() })
			.done(function( data ) {
				if (data=='OK') {
					themeChanged = false;
					$("#saveThemeBtn").removeClass( "btn-success" ).addClass( "btn-default btn-disabled" );	
					$("#saveThemeBtn").html("<i class=\"icon-save-disk\"></i> Saved!");
				} else {
					alert("Error saving: "+data);
				}
			});
		}
</script>



<?php
			return;
		}
		if (isset($_GET['New'])) {
			$title = 'New Theme';
			$billic->set_title($title);
			echo '<h1>' . $title . '</h1>';
			$billic->module('FormBuilder');
			$form = array(
				'name' => array(
					'label' => 'Name',
					'type' => 'text',
					'required' => true,
					'default' => '',
				) ,
			);
			if (isset($_POST['Continue'])) {
				$billic->modules['FormBuilder']->check_everything(array(
					'form' => $form,
				));
				if (empty($billic->errors)) {
					$db->insert('themes', array(
						'name' => $_POST['name'],
						'header' => $GLOBALS['billic_theme_default']['header'],
						'footer' => $GLOBALS['billic_theme_default']['footer'],
						'css' => $GLOBALS['billic_theme_default']['css'],
					));
					$billic->redirect('/Admin/Themes/Name/' . urlencode($_POST['name']) . '/');
				}
			}
			$billic->show_errors();
			$billic->modules['FormBuilder']->output(array(
				'form' => $form,
				'button' => 'Continue',
			));
			return;
		}
		if (isset($_GET['SetActive'])) {
			$db->q('UPDATE `themes` SET `active` = ?', 0);
			$db->q('UPDATE `themes` SET `active` = ? WHERE `name` = ?', 1, urldecode($_GET['SetActive']));
			$billic->status = 'updated';
		}
		if (isset($_GET['Delete'])) {
			$db->q('DELETE FROM `themes` WHERE `name` = ? AND `active` = 0', urldecode($_GET['Delete']));
			$billic->status = 'deleted';
		}
		$billic->set_title('Admin/Themes');
		echo '<h1><i class="icon-magic-wand"></i> Themes</h1>';
		$billic->show_errors();
		echo '<a href="New/" class="btn btn-success">New Theme</a>';
		$themes = $db->q('SELECT * FROM `themes` ORDER BY `name` ASC');
		$default_active = $db->q('SELECT COUNT(*) FROM `themes` WHERE `active` = ?', 1);
		$default_active = $default_active[0]['COUNT(*)'];
		if ($default_active == 0) {
			$default_active = 1;
		} else {
			$default_active = 0;
		}
		$themes[] = array(
			'name' => 'Default',
			'active' => $default_active,
		);
		echo '<table class="table table-striped"><tr><th>Name</th><th>Active</th><th>Actions</th></tr>';
		if (empty($themes)) {
			echo '<tr><td colspan="20">No Themes matching filter.</td></tr>';
		}
		foreach ($themes as $theme) {
			echo '<tr><td><a href="/Admin/Themes/Name/' . urlencode($theme['name']) . '/">' . safe($theme['name']) . '</a></td><td>' . ($theme['active'] == 1 ? '<i class="icon-check-mark"></i>' : '') . '</td><td>';
			if ($theme['active'] == 0) {
				echo '<a href="/Admin/Themes/SetActive/' . urlencode($theme['name']) . '/" class="btn btn-info btn-xs" onClick="return confirm(\'Are you sure you want to set as active theme?\');">Set as Active theme</a>';
			}
			if ($theme['active'] == 0 && $theme['name'] != 'Default') {
				echo '&nbsp;<a href="/Admin/Themes/Delete/' . urlencode($theme['name']) . '/" title="Delete" onClick="return confirm(\'Are you sure you want to delete?\');" class="btn btn-danger btn-xs">Delete</a>';
			}
			echo '</td></tr>';
		}
		echo '</table>';
	}
}
