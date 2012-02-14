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

if(!is_array($_REQUEST["searchdir"]))
	{ $_REQUEST["searchdir"] = array(); }
else
	{
	foreach($_REQUEST["searchdir"] AS $key => $value)
		{
		$_REQUEST["searchdir"][$key] = urldecode($value);
		}
	}

?><html>
<head>
<title>Grep Search-engine</title>
<link type="text/css" href="css/<?php echo $config["jQuery"]["ui"]; ?>/jquery-ui-<?php echo $config["jQuery"]["ui_ver"]; ?>.css" rel="stylesheet" />
<script type="text/javascript" src="js/jquery-<?php echo $config["jQuery"]["jq_ver"]; ?>.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-<?php echo $config["jQuery"]["ui_ver"]; ?>.min.js"></script>
<script>

$(function() {
	$(".refresh").button(  { icons: { primary: "ui-icon-refresh" } });
	$(".search").button(   { icons: { primary: "ui-icon-search" } });
	$(".tool_add").button( { icons: { primary: "ui-icon-plus" } });
	$(".tool_cnf").button( { icons: { primary: "ui-icon-link" } });
	$(".tool_del").button( { icons: { primary: "ui-icon-close" } });
	});

</script>
<style type="text/css">

BODY { font-size: 70%; }
BUTTON { width: 150px; }
FORM { padding: 0px; margin: 0px; }

</style>
</head>
<body>

<div class="ui-tabs ui-widget ui-widget-content ui-corner-all" style="width: 510px; padding: 10px; text-align: center; margin-left: auto; margin-right: auto; margin-bottom: 10px; ">
	<form action="<?php echo basename(__FILE__); ?>" method="post">
		<table border="0" cellspacing="5" cellpadding="4" align="center" style="margin-bottom: 10px">
			<tr>
				<td>Keyphrase:</td>
				<td><input type="text" name="searchstr" value="<?php echo $searchstr; ?>" size="50" maxlength="100"  style="width: 300px;" /></td>
			</tr>
			<tr valign="top">
				<td>Capture:</td>
				<td>
					<select name="searchdir[]" size="10" multiple="multiple" style="width: 300px;">
					<?php foreach(glob($Root."\\output\\*",GLOB_ONLYDIR) AS $Dir) { ?>
						<option value="<?php echo urlencode($Dir) ?>"
							<?php echo in_array($Dir,$_REQUEST["searchdir"]) ? 'selected="selected"' : ''; ?>
							><?php echo $Dir; ?></option>
					<?php } ?>
					</select>
				</td>
			</tr>
			<tr><td align="center" colspan="2"><small>Hold down Ctrl to search multiple directories.</small></td></tr>
		</table>
		<button type="submit" class="search">Search</button>
	</form>
</div>

<?php

if(count($searchdir))
	{
	echo "<hr/>";

	foreach($searchdir AS $dirToSearch)
		{
		$grepstr = $Root."\\utilities\\grep.exe -Ri ".$searchstr." ".urldecode($dirToSearch)."\\*"; echo $cmdstr;
		$results = trim(shell_exec($grepstr));
		if($results)
			{
			$resultsA = explode("\n",$results);
			if(count($resultsA))
				{
				foreach($resultsA AS $line)
					{
					$line2 = str_replace($Root."\\output\\","",$line);
					list($file, $fileline) = split(":",$line2,2);
					$thefile = basename($file);
					$thedir = dirname($file);
					$allResults[$thedir][$thefile][] = $fileline;
					$allCount[$thedir]++;
					}
				}
			}
		}

	if(!count($allResults))
		{
		echo "Sorry. Search on <strong>".$searchstr."</strong> returned no results.<br/>\n";
		}
	else
		{
?>
<table border="1" cellspacing="0" cellpadding="5" align="center">
	<?php foreach($allResults AS $Folder => $FolderResults) { ?>
		<tr valign="top">
			<td rowspan="<?php echo $allCount[$Folder]; ?>"><?php echo $Folder; ?></td>
			<?php foreach($FolderResults AS $File => $Lines) { ?>
				<td rowspan="<?php echo count($FolderResults[$File]); ?>"><?php echo $File; ?></td>
				<?php foreach($Lines AS $Line) { ?>
					<td><?php echo $Line; ?></td>
					<tr valign="top">
	<?php } } } ?>
</table>
<?php
		}

/*
	$fp = popen( $cmdstr, "r" );
	$myresult = array();

	while($buffer = fgetss($fp, 4096))
		{
		if(!defined($myresult[$fname])) { $myresult[$fname] = $fline; }
		}

	if(count($myresult))
		{
		echo "<ol>";
		while(list($fname,$fline) = each($myresult))
			{
			echo '<li><a href="'.$fname.'">'.$fname.'</a> : '.$fline.'</li>'."\n";
			}
		echo "</ol> ";
		}
	else
		{
		}
	pclose($fp);
*/
	}
?>

</body>
</html>