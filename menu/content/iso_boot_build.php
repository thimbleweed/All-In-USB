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

?><html>
<head>
<title>Configure Boot Menu</title>
</head>
<body>

<?php

if(!count($config["boot"]))				{ die("<p>You need to Configure Boot Menu first.</p></body></html>"); }
if(!count($config["boot"]["images"]))	{ die("<p>You need to Configure Boot Menu first and select one or more images.</p></body></html>"); }

$Menu = array();

$Menu[] = "default hd(0,0)/default";
if($config["boot"]["timeout"]) { $Menu[] = "timeout ".$config["boot"]["timeout"]; }

if(		$config["boot"]["normal_text"]
    &&	$config["boot"]["normal_bg"]
    &&	$config["boot"]["highlight_text"]
    &&	$config["boot"]["highlight_bg"]
    &&	$config["boot"]["help_text"]
    &&	$config["boot"]["help_bg"]
    &&	$config["boot"]["heading_text"]
    &&	$config["boot"]["heading_bg"]
	) {
	$Norm	= $config["boot"]["normal_text"]	."/".	$config["boot"]["normal_bg"];
	$High	= $config["boot"]["highlight_text"]	."/".	$config["boot"]["highlight_bg"];
	$Help	= $config["boot"]["help_text"]		."/".	$config["boot"]["help_bg"];
	$Head	= $config["boot"]["heading_text"]	."/".	$config["boot"]["heading_bg"];

	$Menu[] = "color ".$Norm." ".$High." ".$Help." ".$Head;

	foreach(glob($Root."\\isos\\*.twc") AS $Image)
		{
		$tCnf = parse_ini_file($Image,true);
		$tCnf = $tCnf["iso"];
		$Missing = false;
		foreach($isoFields AS $isoField => $Params) { if($Params["required"] && !$tCnf[$isoField]) { $Missing = true; } }
		if(!$Missing)
			{
			if(!$Done) { echo "<ul>\n"; $Done = true; }
			echo "<li>Adding: ".$Image."\n";

			$ImagesUsed++;
			$tCnf["grub"] = base64_decode($tCnf["grub"]);

			$Menu[] = "";
			$Menu[] = "title ".$tCnf["name"].($tCnf["desc"] ? "\\n".$tCnf["desc"] : "");
			$Menu[] = str_replace("%image%",$tCnf["image"],$tCnf["grub"]);
			$Menu[] = "savedefault";
			}
		else
			{
			echo "<li>Invalid Config: ".$Image."\n";
			}
		}
	if($Done) { echo "</ul>\n"; }
	}

if(!$ImagesUsed)
	{
	die("<p>Images selected in Configure Boot Menu are not properly configured.</p></body></html>");
	}

$MenuLst = implode("\n",$Menu);

if(file_put_contents($Root."\\menu.lst",$MenuLst))
	{ echo "<p>Boot Menu Rebuilt</p>"; }
else
	{ echo "<p>Unable to write to Boot Menu.</p>"; }

?>

</body>
</html>