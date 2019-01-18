<?php

namespace Modules\Tenants\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Arr;
use Modules\Tenants\Console\Commands\TenantMigrations;

class TenantServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        Route::namespace('Modules\Tenants\Http\Controllers')
            ->group(__DIR__ . '/../Routes/web.php');
        Route::namespace('Modules\Tenants\Http\Controllers')
            ->group(__DIR__ . '/../Routes/api.php');


        $this->loadMigrationsFrom(__DIR__ . '/../Migrations');


        $this->publishes([
            __DIR__ . '/../Config/tenant.php' => config_path('tenant.php')
        ], 'config');

        if ($this->app->runningInConsole()) {
            $this->commands([
                TenantMigrations::class,
            ]);
        }

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

        $this->mergeConfigFrom(
            __DIR__ . '/../Config/database.php',
            'database'
        );

    }

    /**
     * Merge the given configuration with the existing configuration.
     *
     * @param  string  $path
     * @param  string  $key
     * @return void
     */
    protected function mergeConfigFrom($path, $key)
    {
        $config = $this->app['config']->get($key, []);
        $this->app['config']->set($key, $this->mergeConfig(require $path, $config));

    }
    /**
     * Merges the configs together and takes multi-dimensional arrays into account.
     *
     * @param  array  $original
     * @param  array  $merging
     * @return array
     */
    protected function mergeConfig(array $original, array $merging)
    {
        $array = array_merge($original, $merging);
        foreach ($original as $key => $value) {
            if (!is_array($value)) {
                continue;
            }
            if (!Arr::exists($merging, $key)) {
                continue;
            }
            if (is_numeric($key)) {
                continue;
            }
            $array[$key] = $this->mergeConfig($value, $merging[$key]);
        }
        return $array;
    }

}
