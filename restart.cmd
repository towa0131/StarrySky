@echo off
TITLE Pocketmine-MP: Auto restart
cd /d %~dp0
netstat -o -n -a | findstr 0.0.0.0:19132>nul
if %ERRORLEVEL% equ 0 (
    echo Your server is running.
    goto :loop
) ELSE (
    echo Starting your PocketMine-MP server.
    goto :StartPM
)


:loop
echo Checking if server is online...
PING 127.0.0.1 -n 10 >NUL


netstat -o -n -a | findstr 0.0:19132>nul
if %ERRORLEVEL% equ 0 (
    echo Server is running.
    goto :loop
) ELSE (
    echo Starting PocketMine-MP in 10 seconds...
    PING 127.0.0.1 -n 10 >NUL
    goto :StartPM
)


:StartPM
if exist bin\php\php.exe (
    set PHP_BINARY=bin\php\php.exe
) else (
    set PHP_BINARY=php
)

if exist PocketMine-MP.phar (
    set POCKETMINE_FILE=PocketMine-MP.phar
) else (
    if exist src\pocketmine\PocketMine.php (
        set POCKETMINE_FILE=src\pocketmine\PocketMine.php
    ) else (
        echo "Couldn't find a valid PocketMine-MP installation"
        pause
        exit 1
    )
)

#if exist bin\php\php_wxwidgets.dll (
#    %PHP_BINARY% %POCKETMINE_FILE% --enable-gui %*
#) else (
    if exist bin\mintty.exe (
        start "" bin\mintty.exe -o Columns=88 -o Rows=32 -o AllowBlinking=0 -o FontQuality=3 -o Font="DejaVu Sans Mono" -o FontHeight=10 -o CursorType=0 -o CursorBlinks=1 -h error -t "PocketMine-MP" -i bin/pocketmine.ico -w max %PHP_BINARY% %POCKETMINE_FILE% --enable-ansi %*
    ) else (
        %PHP_BINARY% -c bin\php %POCKETMINE_FILE% %*
    )
#)
goto :loop