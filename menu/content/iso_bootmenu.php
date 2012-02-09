<?php

/* ********************************************************************\
|                                                                      |
|   Copyright (c) 2011 Thimbleweed Consulting. All Rights Reserved     |
|                                                                      |
|                  This file is part of All-In-USB.                    |
|                                                                      |
| All-In-USB is free software: you can redistribute it and/or modify   |
| it under the terms of the GNU General Public License as published    |
| by the Free Software Foundation, either version 3 of the License,    |
| or (at your option) any later version.                               |
|                                                                      |
| All-In-USB is distributed in the hope that it will be useful, but    |
| WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANT- |
| ABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General     |
| Public License for more details.                                     |
|                                                                      |
| You should have received a copy of the GNU General Public License    |
| along with All-In-USB. If not, see <http://www.gnu.org/licenses/>    |
|                                                                      |
\******************************************************************** */

include "functions.php";
$Root = getRoot();

$Colors = array	("black"	   	,"white"
				,"dark-gray"	,"light-gray"
				,"red"	        ,"light-red"
				,"brown"	    ,"yellow"
				,"green"	    ,"light-green"
				,"cyan"	        ,"light-cyan"
				,"magenta"	    ,"light-magenta"
				,"blue"	        ,"light-blue"
				);

global $Files;
getFiles($Root."\\isos");

// ############################################################################
// # Save Previous Run
// ############################################################################

if($_REQUEST["action"] == "save")
	{
	unset($_REQUEST["action"]);
	$saveImages = $_REQUEST["images"];
	$_REQUEST["images"] = base64_encode(stripslashes(trim(serialize($_REQUEST["images"]))));
	foreach($_REQUEST AS $Field => $Value) { $config["boot"][$Field] = $Value; }
	writeConfig("config.ini",$config);
	unset($_REQUEST);
	$config["boot"]["images"] = $saveImages;
	}

// ############################################################################
// # Initial Parse of Boot Items
// ############################################################################

$Available = array();
foreach(glob($Root."\\isos\\*.twc") AS $File)
	{
	$tCap = parse_ini_file($File,true);
	$tCap = $tCap["iso"];
	$Missing = false;
	foreach($isoFields AS $isoField => $Params) { if($Params["required"] && !$tCap[$isoField]) { $Missing = true; } }
	$Stat = $Missing ? 1 : 2;
	$Name = trim(trim(trim($tFile." - ".$tCap["name"]),"-"));

	$Available[basename($File)]["stat"] =  $Stat;
	$Available[basename($File)]["name"] =  $Name;
	$Available[basename($File)]["desc"] =  $tCap["desc"];
	}
ksort($Available);

?><html>
<head>
<title>Configure Boot Menu</title>
<link type="text/css" href="css/<?php echo $config["jQuery"]["ui"]; ?>/jquery-ui-<?php echo $config["jQuery"]["ui_ver"]; ?>.css" rel="stylesheet" />
<script type="text/javascript" src="js/jquery-<?php echo $config["jQuery"]["jq_ver"]; ?>.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-<?php echo $config["jQuery"]["ui_ver"]; ?>.min.js"></script>
<script>

$(function() {
	$("#tabs").tabs();
	$(".refresh").button( { icons: { primary: "ui-icon-refresh" } });
	$(".tool_add").button( { icons: { primary: "ui-icon-plus" } });
	$(".tool_cnf").button( { icons: { primary: "ui-icon-link" } });
	$(".tool_del").button( { icons: { primary: "ui-icon-close" } });
	$(".zebra tr:even").addClass("alt");
	});

</script>
<style type="text/css">

BODY { font-size: 70%; }
BUTTON { width: 250px; }
FORM { padding: 0px; margin: 0px; }

.zebra { margin-bottom: 10px; }
tr.alt td { background-color: <?php echo $config["colors"]["zebra"]; ?>; }
tr.activeRow tr td { background-color: #FF0000; }

</style>
</head>
<body>

<div id="tabs" style="width: 800px; padding: 10px; text-align: center; margin-left: auto; margin-right: auto; margin-bottom: 10px; ">
	<ul>
		<li><a href="#isos">Image Selection</a></li>
		<li><a href="#look">Appearance</a></li>
	</ul>
	<form method="post" action="<?php echo basename(__FILE__); ?>">
		<input type="hidden" name="action" value="save">
		<div id="isos">
			<table border='0' cellspacing="0" cellpadding="5" width="100%" class="zebra">
				<?php foreach($Available AS $Image => $Params) { ?>
					<tr valign="top">
						<td><input type="checkbox" name="images[]" value="<?php echo $Image; ?>" <?php echo in_array($Image,$config["boot"]["images"]) ? "checked='checked'" : ""; ?> /></td>
						<td><?php echo $Params["name"]; ?></td>
						<td><?php echo $Params["desc"]; ?></td>
					</tr>
				<?php } ?>
			</table>
		</div>
		<div id="look">
			<table border='0' cellspacing="0" cellpadding="5" width="100%" class="zebra">
				<tr>
					<td>Timeout:</td>
					<td colspan="2"><input type="text" name="timeout" value="<?php echo $config["boot"]["timeout"]; ?>" size="5" maxlength="2" /></td>
				</tr>
				<tr>
					<td>Normal:</td>
					<td>
						Text:
						<select name="normal_text">
							<?php foreach($Colors AS $Color) { ?>
								<option value="<?php echo $Color; ?>" <?php echo ($Color==$config["boot"]["normal_text"] ? "selected='selected'" : "") ?>><?php echo $Color; ?></option>
							<?php } ?>
						</select>
					</td>
					<td>
						Background:
						<select name="normal_bg">
							<?php foreach($Colors AS $Color) { ?>
								<option value="<?php echo $Color; ?>" <?php echo ($Color==$config["boot"]["normal_bg"] ? "selected='selected'" : "") ?>><?php echo $Color; ?></option>
							<?php } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td>Highlight:</td>
					<td>
						Text:
						<select name="highlight_text">
							<?php foreach($Colors AS $Color) { ?>
								<option value="<?php echo $Color; ?>" <?php echo ($Color==$config["boot"]["highlight_text"] ? "selected='selected'" : "") ?>><?php echo $Color; ?></option>
							<?php } ?>
						</select>
					</td>
					<td>
						Background:
						<select name="highlight_bg">
							<?php foreach($Colors AS $Color) { ?>
								<option value="<?php echo $Color; ?>" <?php echo ($Color==$config["boot"]["highlight_bg"] ? "selected='selected'" : "") ?>><?php echo $Color; ?></option>
							<?php } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td>Help:</td>
					<td>
						Text:
						<select name="help_text">
							<?php foreach($Colors AS $Color) { ?>
								<option value="<?php echo $Color; ?>" <?php echo ($Color==$config["boot"]["help_text"] ? "selected='selected'" : "") ?>><?php echo $Color; ?></option>
							<?php } ?>
						</select>
					</td>
					<td>
						Background:
						<select name="help_bg">
							<?php foreach($Colors AS $Color) { ?>
								<option value="<?php echo $Color; ?>" <?php echo ($Color==$config["boot"]["help_bg"] ? "selected='selected'" : "") ?>><?php echo $Color; ?></option>
							<?php } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td>Heading:</td>
					<td>
						Text:
						<select name="heading_text">
							<?php foreach($Colors AS $Color) { ?>
								<option value="<?php echo $Color; ?>" <?php echo ($Color==$config["boot"]["heading_text"] ? "selected='selected'" : "") ?>><?php echo $Color; ?></option>
							<?php } ?>
						</select>
					</td>
					<td>
						Background:
						<select name="heading_bg">
							<?php foreach($Colors AS $Color) { ?>
								<option value="<?php echo $Color; ?>" <?php echo ($Color==$config["boot"]["heading_bg"] ? "selected='selected'" : "") ?>><?php echo $Color; ?></option>
							<?php } ?>
						</select>
					</td>
				</tr>
			</table>
		</div>
		<button type="submit" class="tool_cnf">Save Configuration</button>
	</form>
</div>

</body>
</html>