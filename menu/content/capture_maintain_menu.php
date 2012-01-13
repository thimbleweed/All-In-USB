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
BUTTON { width: 150px; }

</style>
</head>
<body bgcolor="<?php echo $config["colors"]["menutop"]; ?>">

<?php if($config["options"]["showrefresh"]) { ?>
	<button class="refresh" type="button" onclick="window.location.reload()">Refresh</button>
<?php } ?>
<button class="tool_add" type="button" onclick="window.parent.frames['capture_content'].location = 'capture_maintain_add.php';">Add Tool</button>
<button class="tool_cnf" type="button" onclick="window.parent.frames['capture_content'].location = 'capture_maintain_config.php';">Configure Tools</button>
<button class="tool_del" type="button" onclick="window.parent.frames['capture_content'].location = 'capture_maintain_delete.php';">Remove Tool</button>

</body>
</html>