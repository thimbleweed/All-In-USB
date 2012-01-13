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

$Recipes["Floppy Image"][] = "root (hd0,0)";
$Recipes["Floppy Image"][] = "kernel /memdisk";
$Recipes["Floppy Image"][] = "initrd /isos/%image%";

$Recipes["Standard 2 - Win Install"][] = "map (hd0,0)/isos/%image% (0xFF)";
$Recipes["Standard 2 - Win Install"][] = "map --hook";
$Recipes["Standard 2 - Win Install"][] = "root (0xFF)";
$Recipes["Standard 2 - Win Install"][] = "chainloader (0xFF)";
$Recipes["Standard 2 - Win Install"][] = "boot";

$Recipes["Standard 1"][] = "map (hd0,0)/isos/%image% (hd32)";
$Recipes["Standard 1"][] = "map --hook";
$Recipes["Standard 1"][] = "root (hd32)";
$Recipes["Standard 1"][] = "chainloader (hd32)";
$Recipes["Standard 1"][] = "boot";



ksort($Recipes);