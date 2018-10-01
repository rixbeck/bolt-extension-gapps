<?php

namespace Bolt\Extension\Rixbeck\Gapps\Provider;

use Bolt\Extension\Bolt\BoltForms\Config\Config;
use Bolt\Extension\Rixbeck\Gapps\Extender;
use Silex\Application;
use Silex\ServiceProviderInterface;

class FormExtenderServiceProvider implements ServiceProviderInterface
{
    /**
     * Registers services on the given app.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     */
    public function register(Application $app)
    {
        $extenders = new \Pimple();

        $app['formextender'] = $app->share(function (Application $app) use ($extenders) {
            /** @var Config $formsConf */
            $formsConf = $app['boltforms.config'];
            $forms = $formsConf->getBaseForms();
            foreach ($forms->keys() as $formName) {
                $extenders[$formName] = $extenders->share(
                    function () use ($app, $formName) {
                        return new Extender($app['dispatcher'], $formName);
                    }
                );
            }

            return $extenders;
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