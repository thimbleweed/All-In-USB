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

$Fields["name"]			= array("required" => 1,	"label" => "Easy Name",		"type" => "text",		"width" => 50);
$Fields["tab"]			= array("required" => 1,	"label" => "Tab",			"type" => "sel-tab");
$Fields["manpage"]		= array("required" => 0,	"label" => "Description",	"type" => "area-man",	"width" => 70, "height" => 10);
$Fields["defaults"]		= array("required" => 0,	"label" => "Default Args",	"type" => "text",		"width" => 50);

