<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

class UpdateVSCodeFont extends Command
{
    protected $signature = 'vscode:update-font {--install : Ejecutar el .bat para intentar instalar la fuente si no está presente} {--font= : Nombre del archivo de fuente dentro de resources/fonts (ej: "Dank Mono Italic.ttf")} {--list : Mostrar fuentes disponibles en resources/fonts}';
    protected $description = 'Actualizar la fuente en el archivo settings.json de VS Code (Windows) — permite listar/seleccionar fuentes desde resources/fonts';

    public function handle()
    {
        if (PHP_OS_FAMILY !== 'Windows') {
            $this->error('Este comando solo está pensado para Windows.');
            return 1;
        }

        $windir = getenv('WINDIR') ?: 'C:\\Windows';

        // Fuentes disponibles en resources/fonts
        $fontsDir = resource_path('fonts');
        $available = glob($fontsDir . DIRECTORY_SEPARATOR . '*.{ttf,otf}', GLOB_BRACE) ?: [];
        $availableFiles = array_map('basename', $available);

        if ($this->option('list')) {
            if (empty($availableFiles)) {
                $this->info('No se encontraron archivos de fuentes en resources/fonts.');
                return 0;
            }
            $this->info('Fuentes disponibles:');
            foreach ($availableFiles as $f) {
                $this->line(' - ' . $f);
            }
            $this->line('Usar --font="Nombre Fuente.ttf" para seleccionar una fuente.');
            return 0;
        }

        // Fuente seleccionada (archivo). Si hay fuentes en resources/fonts forzamos selección interactiva
        $optFont = $this->option('font');
        if (!empty($availableFiles)) {
            if ($optFont) {
                if (!in_array($optFont, $availableFiles)) {
                    $this->error("El archivo de fuente especificado no se encontró en resources/fonts: $optFont");
                    $this->info('Fuentes disponibles:');
                    foreach ($availableFiles as $f) {
                        $this->line(' - ' . $f);
                    }
                    $this->line('Usa --font="Nombre Fuente.ttf" con el nombre exacto o ejecuta sin --font para seleccionar.');
                    return 2;
                }
                $fontFile = $optFont;
            } else {
                // Selección interactiva obligatoria para evitar typos
                $fontFile = $this->choice('Selecciona una fuente de resources/fonts', $availableFiles, 0);
            }
        } else {
            // Si no hay fuentes en resources/fonts, conservar comportamiento por compatibilidad
            $fontFile = $optFont ?: 'Dank Mono Italic.ttf';
        }

        $windowsFontsPath = $windir . '\\Fonts\\' . $fontFile;

        // Si la fuente no existe, intentar instalar si el flag --install está presente
        if (!File::exists($windowsFontsPath)) {
            $batFile = resource_path('fonts/font.bat');

            if (!File::exists($batFile)) {
                $this->error("El archivo .bat no se encuentra en: $batFile");
                $this->line('Coloca el instalador en resources/fonts/font.bat o instala la fuente manualmente.');
                return 2;
            }

            if (! $this->option('install')) {
                $this->info("La fuente $fontFile no parece estar instalada.");
                $this->info('Ejecuta el comando con la opción --install para lanzar el instalador:');
                $this->line('  php artisan vscode:update-font --install --font="' . $fontFile . '"');
                return 0;
            }

            // Ejecutar el .bat con Process y pasar el nombre de la fuente como argumento
            $this->info('Ejecutando instalador de fuente...');
            try {
                // Construir comando correctamente con comillas para manejar espacios
                $cmd = 'cmd /c ""' . $batFile . '" "' . $fontFile . '""';
                $process = Process::fromShellCommandline($cmd);
                $process->setTimeout(300);
                $process->run();

                // Guardar salida para diagnóstico
                $out = $process->getOutput();
                $err = $process->getErrorOutput();
                if ($out) {
                    \Log::info('vscode:update-font installer output: ' . $out);
                }
                if ($err) {
                    \Log::warning('vscode:update-font installer error output: ' . $err);
                }
            } catch (\Throwable $e) {
                $this->error('Error al ejecutar el instalador: ' . $e->getMessage());
                \Log::error('vscode:update-font exception: ' . $e->getMessage());
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
        // Mapear nombres comunes a la familia real de la fuente para evitar nombres incorrectos
        $lower = strtolower($fontFile);
        $family = null;
        $mappings = [
            'firacode' => 'Fira Code',
            'fira-code' => 'Fira Code',
            'dank mono' => 'Dank Mono',
            'dank-mono' => 'Dank Mono',
            'dejavu' => 'DejaVu Sans'
        ];

        foreach ($mappings as $key => $val) {
            if (strpos($lower, $key) !== false) {
                $family = $val;
                break;
            }
        }

        if ($family === null) {
            // Derivar nombre si no hay mapeo conocido
            $family = pathinfo($fontFile, PATHINFO_FILENAME);
            $family = str_replace(['-','_'], ' ', $family);
        }

        $settings['editor.fontFamily'] = $family;
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