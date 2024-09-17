<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class UpdateVSCodeFont extends Command
{
    protected $signature = 'vscode:update-font';
    protected $description = 'Actualizar la fuente en el archivo settings.json de VS Code';

    public function handle()
    {
        $batFile = resource_path('fonts/font.bat');
        
        if (!File::exists($batFile)) {
            $this->error("El archivo .bat no se encuentra en: $batFile");
            return;
        }
    
        chdir(resource_path('fonts'));
    
        exec("cmd /c start $batFile", $output, $returnVar);
    
        if ($returnVar !== 0) {
            $this->error("Hubo un problema al ejecutar el archivo .bat. Código de error: $returnVar");
            $this->error("Salida del comando: " . implode("\n", $output));
            return;
        }
        
        $windowsFontsPath = getenv('WINDIR') . '\\Fonts\\Dank Mono Italic.ttf';

        if (!File::exists($windowsFontsPath)) {
            $this->info("Por favor, Haz click en instalar la fuente: $windowsFontsPath"); 
            return;
        }
       
        $userHome = getenv('HOMEDRIVE') . getenv('HOMEPATH');
        $settingsPath = $userHome . '\\AppData\\Roaming\\Code\\User\\settings.json';

        if (!File::exists($settingsPath)) {
            $this->error("El archivo settings.json no se encontró en: $settingsPath");
            return;
        }

        $settings = json_decode(File::get($settingsPath), true);
        $settings['editor.fontFamily'] = 'Dank Mono';
        $settings['editor.fontLigatures'] = true;

        if (File::put($settingsPath, json_encode($settings, JSON_PRETTY_PRINT))) {
            $this->info("La fuente & ligaduras se actualizó correctamente en: $settingsPath");
        } else {
            $this->error("Hubo un problema al escribir en settings.json");
        }
    }
}