<?php

namespace Bolt\Extension\Rixbeck\Gapps\Provider;

use Bolt\Extension\Rixbeck\Gapps\Service\CalendarService;
use Silex\ServiceProviderInterface;

class CalendarServiceProvider implements ServiceProviderInterface
{
    /**
     * @var array
     */
    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Registers services on the given app.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     */
    public function register(\Silex\Application $app)
    {
        $app['gapps.calendar'] = $app->share(function ($app) {
            return new CalendarService($app, $this->config);
        });
    }

    /**
     * Bootstraps the application.
     *
     * This method is called after all services are registered
     * and should be used for "dynamic" configuration (whenever
     * a service must be requested).
     */
    public function boot(\Silex\Application $app)
    {
        // TODO: Implement boot() method.
    }
}
