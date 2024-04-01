<?php

namespace Sayeed\EnvUpdater\Providers;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\ServiceProvider;

class EnvUpdaterServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
		$this->loadViewsFrom(__DIR__ . '/../resources/views', 'env_updater');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
		//
    }
}
