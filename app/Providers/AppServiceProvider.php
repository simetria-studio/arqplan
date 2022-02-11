<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //check that app is local
   
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        Blade::directive('moneytext', function ($money) {
            return "R$ <?php echo number_format($money, 2, ',', '.'); ?>";
        });

        Blade::directive('date', function ($datetime, $format) {
            return "<?php echo date_format($datetime, $format); ?>";
        });
    }
}
