<?php

/*
 * This file is part of jwt-auth.
 *
 * (c) Sean Tymon <tymon148@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fatryst\ICBCPay;

use Illuminate\Support\ServiceProvider;

class ICBCPayServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/icbc.php' => config_path('icbc.php'),
        ]);
    }

    public function register()
    {
        $this->app->singleton('icbc', function () {
            return new ICBCPay;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['icbc'];
    }
}
