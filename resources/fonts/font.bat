@echo off

REM Font installer batch: acepta un argumento con el nombre del archivo de fuente
REM Uso: font.bat "FiraCode-Regular.ttf"  OR simplemente font.bat (elige el primer .ttf/.otf en la carpeta)

SETLOCAL ENABLEDELAYEDEXPANSION

SET "FONT_FILE="

IF NOT "%~1"=="" (
    SET "FONT_FILE=%~1"
) ELSE (
    REM Buscar archivos en la carpeta del script (%~dp0)
    FOR %%F IN ("%~dp0*.ttf" "%~dp0*.otf") DO (
        SET "FONT_FILE=%%~nxF"
        GOTO :FOUND
    )
)

:FOUND
IF "%FONT_FILE%"=="" (
    echo No se encontro ningun archivo .ttf/.otf en %~dp0
    ENDLOCAL
    exit /b 1
)

IF NOT EXIST "%~dp0%FONT_FILE%" (
    echo El archivo de fuente no se encuentra en: "%~dp0%FONT_FILE%"
    ENDLOCAL
    exit /b 1
)

echo Abriendo instalador de fuente: "%~dp0%FONT_FILE%"
start "" "%~dp0%FONT_FILE%"

ENDLOCAL
exit /b 0
