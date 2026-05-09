<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

class UpdateVSCodeFont extends Command
{
    protected $signature = 'vscode:update-font {--install : Ejecutar el .bat para intentar instalar la fuente si no está presente}';
    protected $description = 'Actualizar la fuente en el archivo settings.json de VS Code (Windows)';

    public function handle()
    {
        if (PHP_OS_FAMILY !== 'Windows') {
            $this->error('Este comando solo está pensado para Windows.');
            return 1;
        }

        $windir = getenv('WINDIR') ?: 'C:\\Windows';
        $windowsFontsPath = $windir . '\\Fonts\\Dank Mono Italic.ttf';

        // Si la fuente no existe, intentar instalar si el flag --install está presente
        if (!File::exists($windowsFontsPath)) {
            $batFile = resource_path('fonts/font.bat');

            if (!File::exists($batFile)) {
                $this->error("El archivo .bat no se encuentra en: $batFile");
                $this->line('Coloca el instalador en resources/fonts/font.bat o instala la fuente manualmente.');
                return 2;
            }

            if (! $this->option('install')) {
                $this->info('La fuente Dank Mono no parece estar instalada.');
                $this->info('Ejecuta el comando con la opción --install para lanzar el instalador:');
                $this->line('  php artisan vscode:update-font --install');
                return 0;
            }

            // Ejecutar el .bat con Process y capturar salida
            $this->info('Ejecutando instalador de fuente...');
            $process = Process::fromShellCommandline('cmd /c "' . $batFile . '"');
            $process->setTimeout(300);
            try {
                $process->run();
            } catch (\Throwable $e) {
                $this->error('Error al ejecutar el instalador: ' . $e->getMessage());
                return 3;
            }

            if (! $process->isSuccessful()) {
                $this->error('El instalador terminó con errores. Salida:');
                $this->line($process->getErrorOutput() ?: $process->getOutput());
                return 4;
            }

            $this->info('Instalador ejecutado. Revisa si la ventana del instalador requiere interacción.');
            return 0;
        }

        // Actualizar settings.json del usuario (APPDATA -> Roaming)
        $appData = getenv('APPDATA') ?: (getenv('HOMEDRIVE') . getenv('HOMEPATH') . '\\AppData\\Roaming');
        $settingsPath = rtrim($appData, '\\/') . '\\Code\\User\\settings.json';

        if (!File::exists($settingsPath)) {
            $this->error("El archivo settings.json no se encontró en: $settingsPath");
            return 5;
        }

        $raw = File::get($settingsPath);
        $settings = json_decode($raw, true);

        if (!is_array($settings)) {
            $this->error('El archivo settings.json no contiene JSON válido. Se aborta para evitar corrupción.');
            return 6;
        }

        // Respaldo
        $backupPath = $settingsPath . '.bak-' . date('YmdHis');
        File::copy($settingsPath, $backupPath);
        $this->info("Se creó un respaldo en: $backupPath");

        // Fusionar y escribir de forma atómica
        $settings['editor.fontFamily'] = 'Dank Mono';
        $settings['editor.fontLigatures'] = true;

        $tmpPath = $settingsPath . '.tmp';
        $written = File::put($tmpPath, json_encode($settings, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        if ($written === false) {
            $this->error('Hubo un problema al escribir el archivo temporal. Aborto.');
            return 7;
        }

        // Reemplazar de forma segura
        File::move($tmpPath, $settingsPath);
        $this->info("La fuente y las ligaduras se actualizaron correctamente en: $settingsPath");
        return 0;
    }
}