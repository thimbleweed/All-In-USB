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

if(!$_FILES["file"]["error"] && $_FILES["file"]["size"])
	{
	if(move_uploaded_file($_FILES["file"]["tmp_name"],$Root."\\utilities\\".$_REQUEST["filename"]))
		{ $Msg = "Uploaded Successful: <a href='capture_maintain_config.php?action=load&executable=".$_REQUEST["filename"]."'>Configure</a>"; }
	else
		{ $Msg = "Upload failed"; }

	}


?><html>
<head>
<link type="text/css" href="css/<?php echo $config["jQuery"]["ui"]; ?>/jquery-ui-<?php echo $config["jQuery"]["ui_ver"]; ?>.css" rel="stylesheet" />
<script type="text/javascript" src="js/jquery-<?php echo $config["jQuery"]["jq_ver"]; ?>.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-<?php echo $config["jQuery"]["ui_ver"]; ?>.min.js"></script>
<script>

$(function() {
	$(".refresh").button( { icons: { primary: "ui-icon-refresh" } });
	$(".tool_add").button( { icons: { primary: "ui-icon-plus" } });
	$(".tool_cnf").button( { icons: { primary: "ui-icon-link" } });
	$(".tool_del").button( { icons: { primary: "ui-icon-close" } });
	});

</script>
<style type="text/css">

BODY { font-size: 70%; }
BUTTON { width: 250px; }
FORM { padding: 0px; margin: 0px; }

</style>
</head>
<body>

<div class="ui-tabs ui-widget ui-widget-content ui-corner-all" style="width: 510px; padding: 10px; text-align: center; margin-left: auto; margin-right: auto; margin-bottom: 10px; ">
<form action="<?php echo basename(__FILE__); ?>" method="post" enctype="multipart/form-data" onsubmit="this.filename.value = this.file.value">
	<input type="hidden" name="filename">
	<input type="submit" name="submit" value="Upload" />
	<input type="file" name="file" id="file" />
</form>

<?php echo $Msg; ?>

<p>Multiple files can be installed by going to <?php echo $Root; ?>\utilities and placing the files there.</p>

<p>This function should not be utilized on a target machine.<br /> Files are initially written to the temporary folder of the logged in user.</p>

</body>
</html>