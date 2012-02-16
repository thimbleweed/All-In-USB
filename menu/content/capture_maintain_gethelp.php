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

$exe = $_REQUEST["exe"];

if(strpos($exe,"\\utilities\\sysint") !== false) { $exe .= " /accepteula"; }

switch($_REQUEST["type"])
	{
	case "unix":	$exe .= " --help 2> help_error";	break;
	case "win":		$exe .= " /? 2> help_error";		break;
	}

$output = trim(shell_exec($exe));
$outputA = explode("\n",$output);

if(is_file("help_error"))
	{
	$error = trim(file_get_contents("help_error"));
	unlink("help_error");
	$errorA = explode("\n",$error);
	foreach($errorA AS $errorLine) { $outputA[] = $errorLine; }
	}

?><html>
<head>
<title>Fetching Built In Help</title>
<script type="text/javascript">

function fillInHelp()
	{
	tFld = window.opener.document.getElementById("<?php echo $_REQUEST['fld']; ?>");
	tFld.value = "";
	<?php foreach($outputA AS $line) { ?>
	tFld.value += "<?php echo str_replace('"',"'",trim($line)); ?>"+"\n";
	<?php } ?>
	window.close();
	}

</script>
</head>
<body onload="fillInHelp()">



</body>
</html>
