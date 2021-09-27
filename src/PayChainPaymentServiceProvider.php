<?php

namespace PayAccept\LaravelPaychain;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Database\Eloquent\Factory as EloquentFactory;
use PayAccept\LaravelPaychain\PayChain;

class PayChainPaymentServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //publish config file and merge config
        $path = realpath(__DIR__.'/../config/paychain.php');
        $this->publishes([$path => config_path('paychain.php')], 'bitcoin');
        $this->mergeConfigFrom($path, 'paychain');

        //publish listeners for payment events
        $this->publishes([
                     __DIR__.'/Listeners' => base_path('app/Listeners'),
                 ], 'bitcoin');
        $this->loadMigrationsFrom(__DIR__.'/migrations');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Commands\CheckPayment::class,
            ]);
        }

        $this->registerEloquentFactoriesFrom(__DIR__.'/factories');


        $this->registerPaychain();

        $this->registerPaychainPayment();
    }

    /**
     *
     * @param  string $path [path to factory]
     * @return Illuminate\Database\Eloquent\Factory
     */
    protected function registerEloquentFactoriesFrom($path)
    {
        $this->app->make(EloquentFactory::class)->load($path);
    }

    /**
     * @return \PayAccept\LaravelPaychain\PayChain object
     */
    protected function registerPaychain()
    {
        $this->app->singleton('paychain', function ($app) {
            return $this->resolvePaychain($app);
        });

        $this->app->singleton('PayAccept\LaravelPaychain\PayChain', function ($app) {
            return $this->resolvePaychain($app);
        });
    }

    /**
     *
     * @param App $app
     * @return \PayAccept\LaravelPaychain\PayChain object
     */
    private function resolvePaychain($app)
    {
        return new \PayAccept\LaravelPaychain\PayChain(
            $app['config']->get('paychain.user'),
            $app['config']->get('paychain.password'),
            $app['config']->get('paychain.host', 'localhost'),
            $app['config']->get('paychain.port', 18332)
        );
    }

    /**
    * @return \PayAccept\LaravelPaychain\Models\Payment object
    */
    protected function registerPaychainPayment()
    {
        $this->app->bind('bitcoinPayment', function ($app) {
            return $this->resolvePaychainPayment($app);
        });

        $this->app->bind('PayAccept\LaravelPaychain\Models\Payment', function ($app) {
            return $this->resolvePaychainPayment($app);
        });
    }

    /**
     *
     * @param App $app
     * @return \PayAccept\LaravelPaychain\Models\Payment object
     */
    private function resolvePaychainPayment($app)
    {
        $payment = new \PayAccept\LaravelPaychain\Models\Payment;
        $payment->address = resolve("paychain")->getnewaddress();
        return $payment;
    }
}
