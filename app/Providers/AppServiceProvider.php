<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\Helpers\AssetHelper;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        // AchievementService удалён
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Регистрируем хелпер для версионирования статических файлов
        Blade::directive('versioned', function ($expression) {
            return "<?php echo App\\Helpers\\AssetHelper::versioned($expression); ?>";
        });
    }
}
