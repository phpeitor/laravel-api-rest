<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;

class UpdateVSCodeFontJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        Log::info('El Job UpdateVSCodeFontJob se está ejecutando.');
        try {
            // Ejecuta el comando artisan de forma segura y captura errores
            \Artisan::call('vscode:update-font');
            Log::info('Comando vscode:update-font ejecutado desde el Job.');
        } catch (\Throwable $e) {
            Log::error('Error al ejecutar vscode:update-font desde el Job: ' . $e->getMessage());
        }
    }
}