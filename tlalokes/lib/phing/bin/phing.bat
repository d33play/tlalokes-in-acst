@echo off

rem *************************************************************************
rem ** the phing build script for Windows based systems
rem **
rem ** This is a modified version that works with Tlaloques, a PHP5 framework
rem ** written by Basilio Briceno Hernandez <bbh@tampico.org.mx>
rem **
rem ** Tlaloques (c) 2007 Basilio Briceno Hernandez <bbh@tampico.org.mx>
rem **
rem *************************************************************************

if "%OS%"=="Windows_NT" @setlocal

rem %~dp0 is expanded pathname of the current script under NT
set PHING_BIN=%~dp0
goto init
goto cleanup

:init
if "%PHING_HOME%" == "" set PHING_HOME=%PHING_BIN:~0,-5%
if "%PHP_COMMAND%" == "" goto no_phpcommand
if "%PHP_CLASSPATH%" == "" goto set_classpath
goto run
goto cleanup

:run
%PHP_COMMAND% -c %PHP_COMMAND:~0,-8% -d html_errors=off -qC %PHING_HOME%\bin\phing.php %1 %2 %3 %4 %5 %6 %7 %8 %9
goto cleanup

:no_phpcommand
REM -------------------------------------------------------------------
REM WARNING: Set environment var PHP_COMMAND to the location of php.exe
REM -------------------------------------------------------------------
IF EXIST C:\php\php.exe SET PHP_COMMAND=C:\php\php.exe
REM ------------------------------------------------------------------------
REM WARNING: Set your own php.exe's path in case doesn't exist in this batch
REM ------------------------------------------------------------------------
IF EXIST D:\tmp\php\php.exe SET PHP_COMMAND=D:\tmp\php\php.exe

goto init

:err_home
echo ERROR: Environment var PHING_HOME not set. Please point this
echo variable to your local phing installation!
goto cleanup

:set_classpath
set PHP_CLASSPATH=%PHING_HOME%\classes;%PHING_HOME:~0,-6%\pear;%PHING_HOME:~0,-6%\phpdb\creole
goto init

:cleanup
if "%OS%"=="Windows_NT" @endlocal
REM pause
