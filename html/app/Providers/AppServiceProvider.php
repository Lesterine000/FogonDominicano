<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::directive('euro', function (string $expression): string {
            // Use an HTML entity to avoid encoding issues across environments/editors.
            return "<?php echo number_format((float) ({$expression}), 2, ',', '.') . ' &euro;'; ?>";
        });
    }
}
