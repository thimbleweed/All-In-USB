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

$checkFiles = glob($Root."\\utilities\\check\\*.*");

foreach($checkFiles AS $checkFile)
	{
	$tChk = parse_ini_file($checkFile);
	$checks[$tChk["sig_tab"]][$tChk["title"]] = $tChk;
	}

ksort($checks);

?><html>
<head>
<title>PHP Template</title>
<link type="text/css" href="css/<?php echo $config["jQuery"]["ui"]; ?>/jquery-ui-<?php echo $config["jQuery"]["ui_ver"]; ?>.css" rel="stylesheet" />
<script type="text/javascript" src="js/jquery-<?php echo $config["jQuery"]["jq_ver"]; ?>.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-<?php echo $config["jQuery"]["ui_ver"]; ?>.min.js"></script>
<script>

$(function() {
	$(".tabs").tabs();
	$(".accordion").accordion({ "active":"false", "collapsible":"true" });
	$(".sel_all").button( { icons: { primary: "ui-icon-circle-plus" } });
	$(".sel_none").button( { icons: { primary: "ui-icon-circle-minus" } });
	$(".help").button( { icons: { primary: "ui-icon-help" } });
	$(".no_text").button( { text: false });
	$(".submit").button ( { icons: { primary: "ui-icon-circle-triangle-e" } });
	});

</script>
<style type="text/css">

	.accordion h3 { padding: 5px 5px 7px 25px; }

</style>
</head>
<body>

<h3>Tools Check</h3>

<p>For various reasons, usually licensing, the following are tools that we are unable to ship with All-in-USB.</p>

<div class="tabs">
	<ul>
		<li><a href="#Essentials">Essentials</a></li>
		<?php foreach(array_keys($checks) AS $Tab) { if($Tab != "Essentials") { ?>
			<li><a href="#<?php echo str_replace(" ","_",$Tab); ?>"><?php echo $Tab; ?></a></li>
		<?php } } ?>
	</ul>
	<?php foreach($checks AS $Tab => $TabCont) { ?>
		<div id="<?php echo str_replace(" ","_",$Tab); ?>" style="padding: 5px">
		<?php ksort($TabCont); foreach($TabCont AS $check) { ?>
			<div class="accordion">
				<?php
					$path = $Root."\\\\utilities\\\\".$check["sig_file"];
					eval('$checked = is_'.$check["sig_type"].'("'.$path.'");');
				?>
				<h3 style="line-height: 1em;"><?php echo $check["title"]; ?> <?php echo boolMark($checked,"text","25"); ?></h3>
				<div>
					<table border="0" cellspacing="0" cellpadding="4">
						<tr valign="top"><td>Location:</td><td><?php echo stripslashes($path); ?></td></tr>
						<tr valign="top"><td>Description:</td><td><?php echo $check["description"]; ?></td></tr>
					</table>
					<?php
						switch($check["link_type"])
							{
							case "link":
								echo "<form method='post' action='".$check["link"]."'>\n";
								echo "	<button type='submit' class='submit'>Download</button>\n";
								echo "</form>\n";
								break;
							}
					?>
				</div>
			</div>
		<?php } ?>
		</div>
	<?php } ?>
</div>

<?php $checked = is_dir("D:\\utilities\\sysint"); ?>

</body>
</html>