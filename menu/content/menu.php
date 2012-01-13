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
<title>Menu</title>
<style type="text/css">

UL	{
	list-style-type: none;
	margin: 0;
	padding: 0;
	}

.topNav > LI { margin-top: 1em; }

UL UL { padding-left: 0px; }

UL UL LI:before { content: "\00BB \0020"; }

A { text-decoration: none; color: black; }

</style>
</head>
<body bgcolor="<?php echo $config["colors"]["menuleft"]; ?>">

<table border="0" width="100%" cellspacing="0" cellpadding="3">
	<tr>
		<td rowspan="3" width="<?php echo $config["logo"]["width"]+2; ?>">
			<?php if($config["logo"]["file"] && is_file($config["logo"]["file"])) { ?>
				<img src="<?php echo $config["logo"]["file"]; ?>" height="<?php echo $config["logo"]["height"]; ?>" width="<?php echo $config["logo"]["width"]; ?>" />
			<?php } ?>
		</td>
		<td align="center"><?php echo ($config["logo"]["line1"]?$config["logo"]["line1"]:""); ?></td>
	</tr>
	<tr valign="top" align="center">
		<td><?php echo ($config["logo"]["line2"]?$config["logo"]["line2"]:""); ?></td>
	</tr>
	<tr valign="top" align="center">
		<td><?php echo ($config["logo"]["line3"]?$config["logo"]["line3"]:""); ?></td>
	</tr>
</table>

<?php if(count($config["logo"])) { echo "<hr />"; } ?>


<?php if($config["options"]["showrefresh"]) { ?>
<p><small><a href="menu.php">Refresh</a></small></p>
<p><small><a href="about.php" target="content">PHP Info</a></small></p>
<?php } ?>

<ul class="topNav">

	<?php if(is_dir($Root."\\isos")) { ?>
		<li>Bootable Images</li>
		<ul>
			<?php if(!glob($Root."\\isos\\*.*")) { echo "<li>!! No ISOs Installed !!</li>\n"; } ?>
			<li><a href="iso_config.php" target="content">Configure ISO</a>
			<li><a href="iso_bootmenu.php" target="content">Configure Boot Menu</a>
			<li><a href="iso_boot_build.php" target="content">Rebuild Boot Menu</a>
		</ul>
	<?php }?>

	<?php if(is_dir($Root."\\capture")) { ?>
		<li>Live Capture</li>
		<ul>
			<li><a href="capture_maintain.php" target="content">Maintain Tools</a>
			<li><a href="capture_build.php" target="content">Build Capture</a>
			<li><a href="capture_execute.php" target="content">Execute Capture</a>
			<li><a href="capture_remove.php" target="content">Remove Prior Capture</a>
		</ul>
	<?php } ?>

	<?php if(is_dir($Root."\\capture") && count(glob($Root."\\capture\\*.*"))) { ?>
		<li>Live Capture</li>
		<ul>
			<li><a href="post_quick.php" target="content">Quick Search</a>
		</ul>
	<?php } ?>

</ul>

</body>
</html>