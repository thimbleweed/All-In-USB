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
			if(count($tCap))
				{
				$tCap = $tCap["capture"];
				$Missing = false;
				foreach($Fields AS $Field => $Params) { if($Params["required"] && !$tCap[$Field]) { $Missing = true; } }
				if(!$Missing)
					{
					$tCap["manpage"] = base64_decode($tCap["manpage"]);
					$Captures[$tCap["tab"]][$tFile] = $tCap;
					}
				}
			}
		}
	}

// ############################################################################
// # Sort Tool List
// ############################################################################

function capSort($a, $b)
	{
	if ($a["name"] == $b["name"]) { return 0; }
	return ($a["name"] < $b["name"]) ? -1 : 1;
	}

ksort($Captures);
foreach($Captures AS $Capture => $discard)
	{ uasort($Captures[$Capture],"capSort"); }

// ############################################################################
// # Parse Previous Run
// ############################################################################

if($_REQUEST["captureName"])
	{
	$CaptureName = $_REQUEST["captureName"];
	unset($_REQUEST["captureName"]);

	foreach($_REQUEST AS $Name => $Value)
		{
		$NameA = explode("_",$Name);
		$VType = $NameA[count($NameA)-1];
		unset($NameA[count($NameA)-1]);
		$Tool = implode("_",$NameA);

		$RunPoss[$Tool][$VType] = $Value;
		}

	foreach($RunPoss AS $Run => $Params)
		{
		if($Params["enable"])
			{
			$RunList[$Run]["nice"] = $Params["nice"];
			$RunList[$Run]["cmd"]  = "%1\\utilities\\".$Params["exe"]." ".$Params["args"];
			$RunList[$Run]["log"]  = "%1\\output\\%computername%\\".$Run."-%computername%.txt";
			}
		}

	if(count($RunList))
		{
		$CapBatch  = array();
		$CapBatch[] = "@ECHO OFF";
		$CapBatch[] = "REM ###########################################################################";
		$CapBatch[] = "REM # ".$CaptureName." - Created ".date("Y-m-d H:i:s");
		$CapBatch[] = "REM ###########################################################################";
		$CapBatch[] = "";

		$CapBatch[] = 'IF "%1"=="" ECHO You must specify the root of your capture device';
		$CapBatch[] = 'IF "%1"=="" EXIT /B';
		$CapBatch[] = "";

		$CapBatch[] = "REM Create Output Directories";
		$CapBatch[] = 'CD /D %1';
		$CapBatch[] = 'IF NOT EXIST %1\output MD %1\output';
		$CapBatch[] = 'IF NOT EXIST %1\output\%computername% MD %1\output\%computername%';
		$CapBatch[] = "";

		foreach($RunList AS $RunID => $RunParams)
			{
			$CapBatch[] = "REM ###########################################################################";
			$CapBatch[] = "REM # ".$RunParams["nice"];
			$CapBatch[] = "REM ###########################################################################";
			$CapBatch[] = "";
			$CapBatch[] = "ECHO ".$RunParams["nice"];
			$CapBatch[] = "IF NOT EXIST ".$RunParams["log"]." ECHO ".$RunParams["nice"]." >> ".$RunParams["log"];
			$CapBatch[] = "ECHO . >> ".$RunParams["log"];
			$CapBatch[] = "ECHO %date% %time% >> ".$RunParams["log"];
			$CapBatch[] = "CALL ".$RunParams["cmd"]." >> ".$RunParams["log"];
			$CapBatch[] = "";
			}

		$CapString = trim(implode("\n",$CapBatch));

		$FileName = cleanFileName($CaptureName);
		if(file_put_contents($Root."\\jobs\\".$FileName.".bat",$CapString))
			{ $Msg = "Created Job File: <a href='capture_execute.php?action=execute&executable[]=".$FileName."' target='content'>Run Now</a>"; }
		else
			{ $Msg = "Unable to Create Job File"; }
		}
	else
		{
		$Msg = "No Captures Selected?";
		}
	}


?><html>
<head>
<title>Build Capture</title>
<link type="text/css" href="css/<?php echo $config["jQuery"]["ui"]; ?>/jquery-ui-<?php echo $config["jQuery"]["ui_ver"]; ?>.css" rel="stylesheet" />
<script type="text/javascript" src="js/jquery-<?php echo $config["jQuery"]["jq_ver"]; ?>.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-<?php echo $config["jQuery"]["ui_ver"]; ?>.min.js"></script>
<script>
$(function() {
	$("#tabs").tabs();
	$(".sel_all").button( { icons: { primary: "ui-icon-circle-plus" } });
	$(".sel_none").button( { icons: { primary: "ui-icon-circle-minus" } });
	$(".help").button( { icons: { primary: "ui-icon-help" } });
	$(".no_text").button( { text: false });
	$(".submit").button ( { icons: { primary: "ui-icon-circle-triangle-e" } });
	});

</script>
<style type="text/css">

body { font-size: 70%; }

.capture_label,.capture_exe,.capture_args,.capture_man_body { padding: 5px; }
.capture_man { clear: both; width: 100%; height: 200px; overflow: scroll; border-top: 1px solid black; display: none; }

.capture_exe		{ display: inline; float: left; }
.capture_args		{ display: inline; float: left; }
.capture_man_toggle	{ display: inline; float: right; }

#disabled-tabs UL LI .white_but .ui-icon,.ui-icon-text { background-color: white; }
#disabled-tabs UL LI .white_but { background: transparent url(css/<?php echo $config["jQuery"]["ui"]; ?>/images/ui-bg_highlight-hard_95_cccccc_1x100.png); }

#tabs { margin-top: 10px; }

</style>
<body>

<?php if($Msg) { ?>
	<h1><?php echo $Msg; ?></h1>
<?php } ?>

<form method="post" action="<?php echo basename(__FILE__); ?>" onsubmit="return validateThis(this)">

<button type="submit" class="submit">Build Capture</button>
<button type="button" class="sel_all" onclick="$('input[type=checkbox]').attr('checked','checked');">Select All</button>
<button type="button" class="sel_none" onclick="$('input[type=checkbox]').removeAttr('checked');">Select None</button>

<p>Capture Name: <input type="text" name="captureName" value="<?php echo date('Ymd-His-e'); ?>" size="60" />

<div id="tabs">
	<ul>
		<?php foreach($Captures AS $Tab => $discard) { ?>
			<li>
				<a href="#<?php echo str_replace(" ","_",$Tab); ?>"><?php echo $Tab; ?></a>
				<button type="button" class="no_text white_but sel_all" onclick="$('#<?php echo str_replace(" ","_",$Tab); ?> input[type=checkbox]').attr('checked','checked');">Select All</button>
				<button type="button" class="no_text white_but sel_none" onclick="$('#<?php echo str_replace(" ","_",$Tab); ?> input[type=checkbox]').removeAttr('checked');">Select None</button>
			</li>
		<?php } ?>
	</ul>
	<?php foreach($Captures AS $Tab => $TabCont) { ?>
		<div id="<?php echo str_replace(" ","_",$Tab); ?>" style="padding: 5px">
			<?php

			foreach($TabCont AS $Capture => $Params)
				{
				$id = str_replace(".","_",$Capture);
				?>
				<div style="border: 1px solid black; margin: 5px 0px; padding: 0px;">
					<div class="capture_label">
						<label for="<?php echo $id; ?>_enable">
							<input type="checkbox" name="<?php echo $id; ?>_enable" id="<?php echo $id; ?>_enable" value="1">
							<?php echo $Params["name"]; ?>
						</label>
					</div>
					<div class="capture_body">
						<div class="capture_exe">
							Command &raquo;
							<input type="hidden" name="<?php echo $id; ?>_exe" value="<?php echo $Capture; ?>" />
							<input type="hidden" name="<?php echo $id; ?>_nice" value="<?php echo $Params["name"]; ?>" />
							<?php echo $Capture; ?>
						</div>
						<div class="capture_args">
							<input type="text" width="30" name="<?php echo $id; ?>_args" value="<?php echo $Params["defaults"]; ?>" />
						</div>
						<div class="capture_man_toggle">
							<button type="button" class="help no_text" onclick="$('.capture_man:not(#capture_man_<?php echo $id; ?>)').slideUp(250); $('#capture_man_<?php echo $id; ?>').slideToggle(250);" />Help</button>
						</div>
						<div style="clear: both;"></div>
						<div class="capture_man" id="capture_man_<?php echo $id; ?>">
							<div class="capture_man_body">
								<pre><?php echo stripslashes($Params["manpage"]); ?></pre>
							</div>
						</div>
					</div>
				</div>
				<?php
				}

			?>
		</div>
	<?php } ?>
</div>
</form>

</body>
</html>