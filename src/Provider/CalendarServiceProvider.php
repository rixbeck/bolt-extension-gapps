<?php
namespace Bolt\Extension\Rixbeck\Gapps\Provider;

use Bolt\Extension\Rixbeck\Gapps\Extension;
use Bolt\Application;

class CalendarServiceProvider
{

    protected $calclass;

    static protected $providerId;

    public function __construct($frontend)
    {
        $this->calclass = '\\Bolt\\Extension\\Rixbeck\\Gapps\\Extension\\' . ($frontend) ? 'CalendarFront' : 'CalendarBack';
    }

    public function register(Application $app)
    {
        $config = $app[Extension::CONTAINER_ID]->getConfig();

        $calendarNames = array_keys($config['calendar']);
        $services = array();
        foreach ($calendarNames as $calname) {
            $services[$calname] = $app->share(
                function ($app) use($calname)
                {
                    $calclass = $this->calclass;
                    $service = new $calclass($app, $calname);
                    return $service;
                });
        }
        $app[self::getProviderId()] = $app->share(
            function ($app) use($services)
            {
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