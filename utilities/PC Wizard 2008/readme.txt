--------------------------------------------------------
PC Wizard
Copyright (C) 1996-2007 Laurent KUTIL & Franck DELATTRE
--------------------------------------------------------

WWW: http://www.cpuid.com
e-mail: pcwizard@cpuid.org



1. Running in Batch Mode (or Silent Mode)
-----------------------------------------

Usage:
PC Wizard.exe /R T[x] C[y] filename[.ext] /D
	      /H
	      /?
			
[/?], [/H] - Commands Help

T[x] - with x from 1 to 4 (1=Hardware Tab, 2=System Tab, 3=Files Tab, 4=Resources Tab).
C[y] - with y to specify which category to include with the report.
	T1 C[y]  with y from 1 to 12 (see 2. Parameters List)
	T2 C[y]  with y from 1 to 20 (see 2. Parameters List)
	T3 C[y]  with y from 1 to 15 (see 2. Parameters List)
	T4 C[y]  with y from 1 to 4 (see 2. Parameters List)

/D - To include detailled information.
	
You may specify different extension for different types of reports
[.ext] = .TXT for report in TEXT format
[.ext] = .CVS for report in CVS format
[.ext] = .HTM for report in HTML format


Example 1:
  PC Wizard.exe /R T1 C3 c:\reports\report.txt /D
Result:
  Save plain text report for Hardware Tab and Processor category only into the c:\reports folder, with detailed information.


Example 2:
  PC Wizard.exe /R T1 c:\reports\report.htm
Result:
  Save HTML report for all categories of the Hardware Tab into the c:\reports folder, without detail.



2. Paremeters List
-------------------

for T1 you can use following values :
1 = Summary
2 = Motherboard
3 = Processor
4 = Video
5 = Ports
6 = Disks
7 = Printers
8 = Devices
9 = Multimedia
10 = Network
11 = Power
12 = Voltage, Temperature and Fans

for T2 you can use following values :
1 = Operating System
2 = DOS
3 = Internet
4 = Control Panel
5 = Desktop
6 = Process and Threads
7 = Library DLL
8 = OLE Inscription
9 = Microsoft Applications
10 = Fonts
11 = UnInstall and MSI
12 = Boot-Start Applications
13 = Associated Files Extensions
14 = DirectX
15 = ODBC Data Source
16 = Passwords
17 = Security
18 = Multimedia
19 = Services
20 = UpTime Statistics

for T3 you can use following values :
1 = Config.nt
2 = Boot.ini
3 = Autoexec.nt
4 = Detlog.txt
5 = Bootlog.txt
6 = System.ini
7 = Win.ini
8 = DosStart.bat
9 = CMOS values
10 = Config.dos
11 = Autoexec.dos
12 = Environment Variables
13 = Event Log (Applications)
14 = Event Log (System)
15 = Event Log (Security)

for T4 you can use following values :
1 = Interruption Request (IRQ)
2 = Direct memory Access (DMA)
3 = I/O Ports
4 = memory Resources 

