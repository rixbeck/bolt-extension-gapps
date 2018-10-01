<?php

namespace Bolt\Extension\Rixbeck\Gapps\Provider;

use Bolt\Collection\Arr;
use Bolt\Extension\Rixbeck\Gapps\Service\AccountsService;
use Silex\Application;
use Silex\ServiceProviderInterface;

class AccountsServiceProvider implements ServiceProviderInterface
{
    /**
     * Registers services on the given app.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     */
    public function register(Application $app)
    {
        $accounts = new \Pimple();

        $app['gapps.accounts'] = $app->share(function ($app) {
            $config = $this->config

            return new AccountsService($app, $this->config);
        });
    }

    /**
     * Bootstraps the application.
     *
     * This method is called after all services are registered
     * and should be used for "dynamic" configuration (whenever
     * a service must be requested).
     */
    public function boot(Application $app)
    {
        // TODO: Implement boot() method.
    }
}
