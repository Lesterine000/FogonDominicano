<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Registra los servicios de la aplicación.
     */
    public function register(): void
    {
        // Sin servicios adicionales que registrar.
    }

    /**
     * Inicializa los servicios de la aplicación.
     */
    public function boot(): void
    {
        Blade::directive('euro', function (string $expression): string {
            // Usar una entidad HTML para evitar problemas de codificación entre entornos/editores.
            return "<?php echo number_format((float) ({$expression}), 2, ',', '.') . ' &euro;'; ?>";
        });
    }
}
