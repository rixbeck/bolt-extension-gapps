<?php
namespace Bolt\Extension\Rixbeck\Gapps\Provider;

use Bolt\Extension\Rixbeck\Gapps\Extension;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Bolt\Extension\Rixbeck\Gapps\Service\CalendarService;

class CalendarServiceProvider implements ServiceProviderInterface
{

    protected $calclass;

    static protected $providerId;

    public function __construct($frontend)
    {
        $this->calclass = '\\Bolt\\Extension\\Rixbeck\\Gapps\\Service\\' . (($frontend) ? 'CalendarBaseService' : 'CalendarService');
    }

    public function register(Application $app)
    {
        $app[self::getProviderId()] = $app->share(
            function ($app)
            {
                $config = $app[Extension::CONTAINER_ID]->getConfig();
                $calendarNames = array_keys($config['calendar']);
                $services = new Application();
                foreach ($calendarNames as $calname) {
                    $services[$calname] = $app->share(
                        function ($sapp) use($app, $calname)
                        {
                            $calclass = $this->calclass;
                            $service = new $calclass($app, $calname);
                            return $service;
                        });
                }

                return $services;
            });
    }

    public function boot(Application $app)
    {
    }

    static public function getProviderId()
    {
        if (!static::$providerId) {
            static::$providerId = Extension::CONTAINER_ID . '.calendar';
        }
        return static::$providerId;
    }
}
