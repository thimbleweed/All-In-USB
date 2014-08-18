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

cd /d %~dp0

REM ########################################################################
REM # Inform about Memory Dump
REM ########################################################################

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

REM ########################################################################
REM # Ask about Memory Dump
REM ########################################################################

ECHO Would you like to attempt to dump the memory of this machine?
SET /P Choice= Press Y or N and then press Enter: (y/N)
IF '%Choice%'=='' SET Choice=N
SET Choice=%Choice:~0,1%
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

REM ########################################################################
REM # Screen Record
REM ########################################################################

IF NOT EXIST PortableApps GOTO NoPortableApps
IF NOT EXIST PortableApps\VLCPortable GOTO NoPortableVLC

REM ########################################################################
REM # Inform about Screen Capture
REM ########################################################################

cls
ECHO.
utilities\sfk echo [Green]All-In-USB[def] Copyright (C) 2011 [Blue]Thimbleweed Consulting[def]
ECHO Licensed under the GNU General Public License v3
ECHO.

utilities\sfk color white
ECHO With the current utility set you will be able to capture a video of
ECHO your actions on this machine. This is done using VLC Portable. This
ECHO is very useful for accountability but can rapidly eat drive space.
ECHO.
utilities\sfk color yellow
ECHO To be recorded actions must take place on the primary monitor.
ECHO.
ECHO Be sure you have sufficient space for your video capture!
utilities\sfk color white
ECHO.
ECHO When you are finished your screen capture simply close VLC.
ECHO.

REM ########################################################################
REM # Ask about Screen Capture
REM ########################################################################

ECHO Would you like to attempt to capture your screen to a video file?
SET /P Capture= Press Y or N and then press Enter: (y/N)
IF '%Capture%'=='' SET Capture=N
SET Capture=%Capture:~0,1%
ECHO.

IF /I NOT '%Capture%'=='Y' GOTO NoCapture
   IF NOT EXIST ScreenCap MD ScreenCap
   REM Must specify both additional options to capture Mouse Pointer.
   REM :screen-follow-mouse
   REM :screen-mouse-image="menu\content\images\mouse_pointer_small.png"

   START PortableApps\VLCPortable\VLCPortable.exe screen:// :screen-fps=24 :sout=#transcode{vcodec=h264,venc=x264{scenecut=100,bframes=0,keyint=10},vb=1024,acodec=none,scale=1.0}:duplicate{dst=std{mux=mp4,access=file,dst="ScreenCap\screencast.mp4"}}

   utilities\sfk echo Your capture has not started until VLC is launched!.
   PAUSE

:NoCapture

GOTO LaunchGUI

REM ########################################################################
REM # Screen Capture Failures
REM ########################################################################

:NoPortableVLC
utilities\sfk color yellow
ECHO.
ECHO You have not installed VLC Portable into your PortableApps toolkit.
ECHO When this is installed you will have the ability to record a screencast
ECHO of the actions that you take while utilizing All-In-USB.
ECHO.
utilities\sfk color grey
ECHO To add VLC to your PortableApps toolkit click the Apps button in your
ECHO PortableApps menu. From there select Get More Apps and then By Title.
ECHO Select VLC Portable from the list. If you wish to add more than just
ECHO VLC Portable we suggest you select to Get More Apps by Catagory.
ECHO.
GOTO LaunchGUI

:NoPortableApps
utilities\sfk color yellow
ECHO.
ECHO You have not installed the PortableApps toolkit. We highly recommend
ECHO that you download this and install at least Portable VLC. When this
ECHO is installed you will have the ability to record a screencast of the
ECHO actions that you take while utilizing All-In-USB.
ECHO.
utilities\sfk color grey
GOTO LaunchGUI

REM ########################################################################
REM # Launch GUI
REM ########################################################################

:LaunchGUI
IF "%1"=="NOGUI" GOTO END
IF "%1"=="NoGUI" GOTO END
IF "%1"=="nogui" GOTO END

start menu/QuickPHP.exe /start /root="./menu/content/"
start menu/QtWeb http://localhost:5723/

:END