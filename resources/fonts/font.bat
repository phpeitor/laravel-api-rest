@echo off

SET FONT_FILE="Dank Mono Italic.ttf"

IF NOT EXIST %FONT_FILE% (
    echo El archivo de fuente no se encuentra en: %FONT_FILE%
    exit /b 1
)

start "" %FONT_FILE%

exit /b 0
