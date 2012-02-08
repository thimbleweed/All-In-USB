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

// ############################################################################
// # Define some base variables
// ############################################################################

include "functions.php";
$Root = getRoot();

// ############################################################################
// # If Saving Build, then Write, .TWC file
// ############################################################################

if($_REQUEST["action"] == "save" && $_REQUEST["executable"])
	{
	$_REQUEST["manpage"] = base64_encode(stripslashes(trim($_REQUEST["manpage"])));

	$twc = "[capture]\n";
	foreach($Fields AS $Field => $Params)
		{
		if($Params["type"] == "sel-tab" && $_REQUEST[$Field] == "new-tab")
			{ $_REQUEST[$Field] = $_REQUEST[$Field."_other"]; unset($_REQUEST[$Field."_other"]); }

		$twc .= $Field .' = "'. $_REQUEST[$Field].'"'."\n";
		}

	$file = $Root."\\utilities\\".$_REQUEST["executable"].".twc";
	file_put_contents($file,$twc);
	unset($_REQUEST);
	}

// ############################################################################
// # Get File List
// ############################################################################

global $Files;
getFiles($Root."\\utilities");

// ############################################################################
// # Initial Parse of Capture Tools
// ############################################################################

foreach($Files AS $File)
	{
	if(substr($File,-4) != ".twc")
		{
		unset($Name);
		$tFile = str_replace($Root."\\utilities\\","",$File);
		if(in_array($File.".twc",$Files))
			{
			$tCap = parse_ini_file($File.".twc",true);
			$tCap = $tCap["capture"];
			$Missing = false;
			foreach($Fields AS $Field => $Params) { if($Params["required"] && !$tCap[$Field]) { $Missing = true; } }
			$Stat = $Missing ? 1 : 2;
			$Tabs[$tCap["tab"]] = 1;
			$Name = trim(trim(trim($tFile." - ".$tCap["name"]),"-"));
			}
		else
			{ $Stat = 0; }
		$Captures[$tFile]["stat"] =  $Stat;
		$Captures[$tFile]["name"] =  $Name ? $Name : $tFile;
		}
	}

?><html>
<head>
<title>Edit Configuration Files</title>
<link type="text/css" href="css/<?php echo $config["jQuery"]["ui"]; ?>/jquery-ui-<?php echo $config["jQuery"]["ui_ver"]; ?>.css" rel="stylesheet" />
<script type="text/javascript" src="js/jquery-<?php echo $config["jQuery"]["jq_ver"]; ?>.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-<?php echo $config["jQuery"]["ui_ver"]; ?>.min.js"></script>
<script>

$(function() {
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
<script type="text/javascript">

function toggleOther(Fld,FldOth)
	{
	tFld = document.getElementById(Fld);
	tFldOth = document.getElementById(FldOth);

	tVal = tFld.options[tFld.selectedIndex].value;
	if(tVal == "new-tab")
		{
		tFldOth.disabled = false;
		tFldOth.style.backgroundColor = "#ffffff";
		}
	else
		{
		tFldOth.disabled = true;
		tFldOth.style.backgroundColor = "#c0c0c0";
		}
	}

function validateEdit(F)
	{
	<?php

		foreach($Fields AS $Field => $Params)
			{
			echo "\n";
			if($Params["required"])
				{
				echo "tFld = F.".$Field.";\n";
				switch($Params["type"])
					{
					case "text":
					case "area-man":
						echo "if(tFld.value.length < 1) { alert('Missing ".$Params["label"]."'); return false; }\n";
						break;

					case "sel-tab":
						echo "if(tFld.selectedIndex < 1) { alert('Missing ".$Params["label"]."'); return false; }\n";
						echo "tFldOth = F.".$Field."_other;\n";
						echo "if((tFld.options[tFld.selectedIndex].value == 'new-tab') && tFldOth.value.length < 1) { alert('Missing Other Value for ".$Params["label"]."'); return false; }\n";
						break;
					}
				}
			}

	?>
	return true;
	}


</script>
</head>
<body>

<div class="ui-tabs ui-widget ui-widget-content ui-corner-all" style="width: 510px; padding: 10px; text-align: center; margin-left: auto; margin-right: auto; margin-bottom: 10px; ">
	<form method="post" action="<?php echo basename(__FILE__); ?>">
		<input type="hidden" name="action" value="load">
		<select name="executable">
			<option value=""></option>
			<?php foreach($Captures AS $Capture => $Params) { ?>
				<option value="<?php echo $Capture; ?>" <?php echo $_REQUEST["executable"] == $Capture ? 'selected="selected"' : ''; ?>>
					<?php
					echo $Params["name"];
					switch($Params["stat"])
						{
						case "2":						 		break;
						case "1":	echo " (Partial Conf)";		break;
						default:	echo " (Not Conf)";			break;
						}
					?></option>
			<?php } ?>
		</select>
		<button type="submit" class="tool_cnf" style="width: 75px">Load</button>
	</form>
</div>

<?php if($_REQUEST["action"] == "load" && $_REQUEST["executable"]) { ?>
	<div class="ui-tabs ui-widget ui-widget-content ui-corner-all" style="width: 800px; padding: 10px; text-align: center; margin-left: auto; margin-right: auto; margin-bottom: 10px; ">
		<form method="post" action="<?php echo basename(__FILE__); ?>" onsubmit="return validateEdit(this);">
		<input type="hidden" name="action" value="save">
		<table border='0' cellspacing="0" cellpadding="5" width="800" class="zebra">
			<tr valign="top">
				<td>Executable: </td>
				<td>
					<?php echo $_REQUEST["executable"]; ?>
					<input type="hidden" name="executable" value="<?php echo $_REQUEST['executable']; ?>" />
					<?php
						$tTWC = $Root."\\utilities\\".$_REQUEST["executable"].".twc";
						if(is_file($tTWC))	{ $tCap = parse_ini_file($tTWC,true); $tCap = $tCap["capture"]; }
						else				{ $tCap = array(); }
					?>
				</td>
			</tr>
			<?php foreach($Fields AS $Field => $Params) { ?>
				<tr valign="top">
					<td><?php echo $Params["label"]; ?>: </td>
					<td>
						<?php
							switch($Params["type"])
								{
								case "text":
									echo '<input type="text" name="'.$Field.'" id="'.$Field.'" value="'.$tCap[$Field].'" size="'.$Params["width"].'" />'."\n";
									break;

								case "sel-tab":
									echo '<select name="'.$Field.'" id="'.$Field.'" onchange=\'toggleOther("'.$Field.'","'.$Field.'_other");\'>'."\n";
									echo '	<option value=""></option>'."\n";
									foreach($Tabs AS $Tab => $Discard)
										{
										echo '	<option value="'.$Tab.'"';
										echo $Tab == $tCap["tab"] ? 'selected="selected"' : '';
										echo '>'.$Tab.'</option>'."\n";
										}
									echo '	<option value="new-tab">New Tab</option>'."\n";
									echo '</select>'."\n";
									echo '<input type="text" disabled="disabled" value="" name="'.$Field.'_other" id="'.$Field.'_other" style="background-color: #c0c0c0;" size="20" />';
									break;

								case "area-man":
									echo '<textarea name="'.$Field.'" id="'.$Field.'" rows="'.$Params['height'].'" cols="'.$Params['width'].'" wrap="virtual">';
									if($tCap["manpage"]) { echo base64_decode($tCap["manpage"]); }
									echo '</textarea>'."\n";
									echo "<br />Attempt Auto-populate &raquo;\n";
									echo "<input type='button' value='--help' style='font-size: small;' onclick='window.open(\"capture_maintain_gethelp.php?type=unix&fld=".$Field."&exe=".$_REQUEST["executable"]."\")' />\n";
									echo "<input type='button' value='/?' style='font-size: small;' onclick='window.open(\"capture_maintain_gethelp.php?type=win&fld=".$Field."&exe=".$_REQUEST["executable"]."\")' />\n";
									echo "<small>(Pop-Up Window may have text. Please ignore.)</small>";
									break;

								default:
									pecho($Params);
									break;

								}
						?>
					</td>
				</tr>
			<?php } ?>
			</table>
			<button type="submit" class="tool_cnf">Save Configuration</button>
		</form>
	</div>
<?php } ?>

</body>
</html>