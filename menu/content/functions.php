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

header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

define("TWC_VERSION","v0.1");

date_default_timezone_set("UTC");

function pecho($thingy) { echo "<pre>"; print_r($thingy); echo "</pre>"; }
function getRoot() { return str_replace("\\menu\\content\\functions.php","",__FILE__); }

// ############################################################################
// # Read, creating as needed, the configuration file.
// ############################################################################

if(!is_file("config.ini")) { buildDefaultConfig(); }
$config = parse_ini_file("config.ini",true);

if($config["boot"]["images"])
	{
	$config["boot"]["images"] = base64_decode($config["boot"]["images"]);
	if($config["boot"]["images"]) { $config["boot"]["images"] = unserialize($config["boot"]["images"]); }
	}


// ############################################################################
// # Include .TWC Required Fields
// ############################################################################

include_once "capture_fields.php";
include_once "iso_fields.php";
include_once "iso_recipes.php";

// ############################################################################
// # Clean File Name
// ############################################################################

function cleanFileName($fileName)
	{
	$replace="_";
	$pattern="/([[:alnum:]_\.-]*)/";
	$fileName = str_replace(str_split(preg_replace($pattern,$replace,$fileName)),$replace,$fileName);
	while(strpos($fileName,"__") !== false) { $fileName = str_replace("__","_",$fileName); }
	$fileName = trim($fileName,"_");
	return $fileName;
	}

// ############################################################################
// # File Get Functions
// ############################################################################

function getFiles($dirName, $StartOver = true)
	{
	global $Files;

	if($StartOver) { $Files = array(); }

	if(is_dir($dirName))
		{
		$SubDirs = array();
		$dirList = array();
		$dirA = scandir($dirName);

		if(count($dirA))
			{
			sort($dirA);
			foreach($dirA AS $dir)
				{ if(!in_array($dir,array(".",".."))) { $dirList[] = $dir; } }
			}

		if(count($dirList))
			{
			foreach($dirList AS $entry)
				{
				if(is_dir($dirName."\\".$entry))
					{ $SubDirs[] = $dirName."\\".$entry; }
				else
					{ $Files[] = $dirName."\\".$entry; }
				}
			}

		if(count($SubDirs))
			{
			foreach($SubDirs AS $SubDir)
				{ getFiles($SubDir,false); }
			}
		}

	return;
	}

// ############################################################################
// # Get Directories in Specified Folder
// ############################################################################

function getDirs($dirName)
	{
	global $Dirs;

	if(is_dir($dirName))
		{
		$SubDirs = array();
		$dirA = scandir($dirName);

		if(count($dirA))
			{
			sort($dirA);
			foreach($dirA AS $dir)
				{ if(!in_array($dir,array(".",".."))) { $dirList[] = $dir; } }
			}

		if(count($dirList))
			{
			foreach($dirList AS $entry)
				{
				if(is_dir($dirName."\\".$entry))
					{ $Dirs[] = $dirName."\\".$entry; }
				}
			}
		}

	return;
	}

// ############################################################################
// # Write INI Contents to File
// ############################################################################

function writeConfig($File,$Contents)
	{
	if(!is_array($Contents)) { return; }
	if(!count($Contents)) { return; }

	$output = "";
	foreach($Contents AS $Section => $SectVals)
		{
		$output .= "[".$Section."]\n";
		if(!is_array($SectVals)) { return; }
		if(!count($SectVals)) { return; }
		foreach($SectVals AS $Key => $Value) { $output .= $Key.' = "'.$Value.'"'."\n"; }
		$output .= "\n";
		}

	file_put_contents($File,$output);
	}

// ############################################################################
// # Build Default Config File
// ############################################################################

function buildDefaultConfig()
	{
	$config = array();

	$config["colors"]["menuleft"]		= "#327e04";
	$config["colors"]["menutop"]		= "#C0C0C0";
	$config["colors"]["zebra"]			= "#8DC262";

	$config["logo"]["file"]				= "logo.png";
	$config["logo"]["height"]			= 87;
	$config["logo"]["width"]			= 63;
	$config["logo"]["line1"]			= "<a href='http://www.twcsec.com' target='_blank'>TWCSec.com</a>";
	$config["logo"]["line2"]			= "All-in-USB";
	$config["logo"]["line3"]			= TWC_VERSION;

	$config["options"]["showrefresh"]	= false;

	$config["jQuery"]["ui_ver"]			= "1.8.16";
	$config["jQuery"]["jq_ver"]			= "1.7.1";
	$config["jQuery"]["ui"]				= "south-street";

	$config["boot"]["images"]			= "YToyOntpOjA7czoxODoiQlQ1UjEtR05PTUUtMzIuaXNvIjtpOjE7czoxMjoiV2luN194MzIuaXNvIjt9";
	$config["boot"]["timeout"]			= 30;
	$config["boot"]["normal_text"]		= "light-green";
	$config["boot"]["normal_bg"]		= "black";
	$config["boot"]["highlight_text"]	= "yellow";
	$config["boot"]["highlight_bg"]		= "black";
	$config["boot"]["help_text"]		= "white";
	$config["boot"]["help_bg"]			= "green";
	$config["boot"]["heading_text"]		= "light-green";
	$config["boot"]["heading_bg"]		= "green";

	writeConfig("config.ini",$config);
	}
