<?php
namespace Bolt\Extension\Rixbeck\Gapps\Provider;

use Bolt\Extension\Rixbeck\Gapps\Extension;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Bolt\Extension\Rixbeck\Gapps\Service\CalendarService;

abstract class BaseServiceProvider implements ServiceProviderInterface
{

    protected $className;
    protected $sectionId;

    public function __construct()
    {
        $this->className = static::class;
        $this->sectionId = implode('.', array_map('lcfirst', explode('\\', $this->className)));
    }

    public function register(Application $app)
    {
        $self = $this;
        $app[] = $app->share(
            function ($app) use($self)
            {
                $config = $app[Extension::CONTAINER_ID]->getConfig($self->sectionId, '.');
                // @todo Exception if $config == false
                $names = array_keys($config);
                $services = new Application();
                foreach ($names as $name) {
                    $services[$name] = $app->share(
                        function ($sapp) use($app, $name)
                        {
                            $class = $this->className;
                            $service = new $class($app, $name);
                            return $service;
                        });
                }

                return $services;
            });
    }

    public function boot(Application $app)
    {
    }
}
