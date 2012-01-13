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

// ############################################################################
// # Fields
// ############################################################################

$isoFields["name"]		= array("required" => 1,	"label" => "Title",			"type" => "text",		"width" => 50);
$isoFields["desc"]		= array("required" => 0,	"label" => "Description",	"type" => "text",		"width" => 50);
$isoFields["grub"]		= array("required" => 1,	"label" => "Boot Recipe",	"type" => "area-grub",	"width" => 70, "height" => 10);

