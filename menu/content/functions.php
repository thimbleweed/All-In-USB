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
// # If Refresh is toggled then enable debugging
// ############################################################################

if($config["options"]["showrefresh"])
	{
	ini_set('error_reporting', E_ALL);
	ini_set('display_errors', 1);
	}

// ############################################################################
// # Include .TWC Required Fields
// ############################################################################

include_once "capture_fields.php";
include_once "iso_fields.php";
include_once "iso_recipes.php";

// ############################################################################
// # Actual Function Definitions
// ############################################################################

/**
* Recursive Glob
*
* @param string  $path     Path to glob into - Must end with a slash
* @param string  $pattern  Pattern to pass to glob
* @param int     $flags    Any needed GLOB_X flags
* @return array  returns array containing files
*/

// Shamelessly copied from php@hm2k.org on http://php.net/manual/en/function.glob.php
// Swapped Parameter order though... We'll always provide a path

function rglob($path='', $pattern='*', $flags = 0)
	{
    $paths=glob($path.'*', GLOB_MARK|GLOB_ONLYDIR|GLOB_NOSORT);
    $files=glob($path.$pattern, $flags);
    foreach ($paths as $path) { $files=array_merge($files,rglob($path, $pattern, $flags)); }
    return $files;
	}

/**
* Graphic Boolean
*
* @param string  $statement  boolean statement to evaluate
* @param enum    $type       text, image, or image_tag
* @param int     $size       size in pixels
* @return mixed  returns one of a <span> tag, <img> tag or image file name.
*/

function boolMark($statement, $type = "", $size = "")
	{
	switch($type)
		{
		case "text":
			if($statement)	{ return "<span style='display: inline; color: #00FF00; font-weight: bold; padding-bottom: 2px; ".($size ? "font-size: ".intval($size*.9)."px;" : "")."'>&radic;</span>"; }
			else			{ return "<span style='display: inline; color: #FF0000; font-weight: bold; padding-bottom: 2px; ".($size ? "font-size: ".intval($size* 1)."px;" : "")."'>&times;</span>"; }
			break;

		case "image":
			if($statement)	{ return "icon_check.png"; }
			else			{ return "icon_xmark.png"; }
			break;

		default:
			if($statement)	{ return "<img src='images/icon_check.png' border='0' ".($size ? "height='".$size."' width='".$size."'" : "")." />"; }
			else			{ return "<img src='images/icon_xmark.png' border='0' ".($size ? "height='".$size."' width='".$size."'" : "")." />"; }
			break;
		}
	}

/**
* Clean File Name
*
* @param string  $fileName  Filename to clean to (universally?) safe characters
* @return string
*/

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
	$config["options"]["exec_ext"]		= "exe,com,bat";

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
