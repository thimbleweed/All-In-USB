@ECHO off

REM /* ********************************************************************\
REM |                                                                      |
REM |   Copyright (c) 2011 Thimbleweed Consulting. All Rights Reserved     |
REM |                                                                      |
REM |                  This file is part of All-In-USB.                    |
REM |                                                                      |
REM | All-In-USB is free software: you can redistribute it and/or modify   |
REM | it under the terms of the GNU General Public License as published    |
REM | by the Free Software Foundation, either version 3 of the License,    |
REM | or (at your option) any later version.                               |
REM |                                                                      |
REM | All-In-USB is distributed in the hope that it will be useful, but    |
REM | WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANT- |
REM | ABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General     |
REM | Public License for more details.                                     |
REM |                                                                      |
REM | You should have received a copy of the GNU General Public License    |
REM | along with All-In-USB. If not, see <http://www.gnu.org/licenses/>    |
REM |                                                                      |
REM \******************************************************************** */

cls
ECHO.
utilities\sfk echo [Green]All-In-USB[def] Copyright (C) 2011 [Blue]Thimbleweed Consulting[def]
ECHO Licensed under the GNU General Public License v3
ECHO.

utilities\sfk color white
ECHO If you are running this on a target machine to be captured you should
ECHO gather a memory dump before proceeding. Please be aware of the following:
ECHO.
ECHO    - If this is a 64-Bit machine...
ECHO    - If your capture device is formatted as Fat32...
ECHO    - If there is more than 4Gb of memory installed...
ECHO.
ECHO You may not be able to capture all memory installed and write the dump to
ECHO your capture device at this time!
ECHO.
utilities\sfk echo The memory dump process might take a [Yellow]LONG[def] time!
ECHO.
utilities\sfk color grey

ECHO Would you like to attempt to dump the memory of this machine?
SET /P Choice= Press Y or N and press Enter: (y/N)
IF NOT '%Choice%'=='' SET Choice=%Choice:~0,1%
ECHO.

IF /I NOT '%Choice%'=='Y' GOTO NoDump
   utilities\sfk echo The DumpIt utility will attempt a privilege elevation. You [Yellow]MUST[def] allow this!
   echo When the memory dump is completed hit enter to continue.
   pause
   ECHO Loading... Please wait...
   utilities\dumpit\dumpit.exe
   IF NOT EXIST output MD output
   IF NOT EXIST output\%computername% MD output\%computername%
   MOVE *.raw output\%computername%
:NoDump

start menu/QuickPHP.exe /start /root="./menu/content/"
start menu/QtWeb http://localhost:5723/