<?php
/**
 * @link https://github.com/vuongxuongminh/laravel-mobile-first
 *
 * @copyright (c) Vuong Xuong Minh
 * @license [MIT](https://opensource.org/licenses/MIT)
 */

namespace VXM\MobileFirst;

use Illuminate\Support\ServiceProvider;

/**
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since  1.0.0
 */
class MobileFirstServiceProvider extends ServiceProvider
{
    /**
     * Register package.
     */
    public function register(): void
    {
        $this->mergeDefaultConfigs();
        $this->registerServices();
    }

    /**
     * Merge default package configs to config service.
     */
    protected function mergeDefaultConfigs(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/mobilefirst.php', 'mobilefirst');
    }

    /**
     * Register package services.
     */
    protected function registerServices(): void
    {
        $this->app->singleton(ViewComposer::class, function ($app) {
            return new ViewComposer($app['agent'], $app['config']->get('mobilefirst'));
        });
    }

    /**
     * Boot package.
     */
    public function boot(): void
    {
        $this->publishConfigs();
        $this->registerViewComposeListener();
    }

    /**
     * Publish package config files.
     */
    protected function publishConfigs(): void
    {
        $this->publishes([
            __DIR__.'/../config/mobilefirst.php' => config_path('mobilefirst.php'),
        ], 'config');
    }

    /**
     * Composer view when auto switch view by device enabled.
     */
    protected function registerViewComposeListener(): void
    {
        if ($this->app['config']->get('mobilefirst.auto_switch_view_by_device')) {
            $this->app['view']->composer('*', ViewComposer::class);
        }
    }

}
