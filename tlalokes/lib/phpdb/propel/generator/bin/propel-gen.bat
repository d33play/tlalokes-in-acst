@echo off

rem *************************************************************************
rem ** The Propel generator convenience script for Windows based systems
rem **
rem ** This is a modified version that works with Tlaloques, a PHP5 framework
rem ** written by Basilio Briceno Hernandez <bbh@tampico.org.mx>
rem **
rem ** Tlaloques (c) 2007 Basilio Briceno Hernandez <bbh@tampico.org.mx>
rem **
rem *************************************************************************

if "%OS%"=="Windows_NT" @setlocal

rem %~dp0 is expanded pathname of the current script under NT
set PROPEL_GEN_BIN=%~dp0

goto init
goto cleanup

:init
if "%PROPEL_GEN_HOME%" == "" set PROPEL_GEN_HOME=%PROPEL_GEN_BIN:~0,-5%
if "%PHING_COMMAND%" == "" set PHING_COMMAND=%PROPEL_GEN_HOME:~0,-23%\phing\bin\phing.bat

goto run
goto cleanup

:run
%PHING_COMMAND% -f %PROPEL_GEN_HOME%\build.xml -Dusing.propel-gen=true -Dproject.dir=%*
goto cleanup

:cleanup
if "%OS%"=="Windows_NT" @endlocal
rem pause
