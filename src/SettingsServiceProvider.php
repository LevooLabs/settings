<?php

namespace LevooLabs\Settings;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../migrations');

        $this->loadBladeDirectives();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('setting', function ($app) {
            return new Setting($app['cache'], $app['encrypter']);
        });
    }

    private function loadBladeDirectives() 
    {
        Blade::if('settingexists', function ($setting) {
            return setting_exists($setting);
        });

        Blade::if('settingtrue', function ($setting) {
            return setting_bool($setting) === true;
        });

        Blade::directive('setting', function ($setting) { 
            return "<?php echo setting($setting); ?>"; 
        });
    }
}
