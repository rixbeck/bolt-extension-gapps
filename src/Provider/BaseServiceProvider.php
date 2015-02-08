<?php
namespace Bolt\Extension\Rixbeck\Gapps\Provider;

use Bolt\Extension\Rixbeck\Gapps\Extension;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Bolt\Extension\Rixbeck\Gapps\Service\CalendarService;

abstract class BaseServiceProvider implements ServiceProviderInterface
{

    protected $className;

    protected $providerId;

    protected $sectionId;

    public function __construct($className = null)
    {
        if ($className) {
            $this->className = $this->serviceFQName($className);
        }
    }

    public function register(Application $app)
    {
        $self = $this;
        $app[Extension::getProviderId($this->sectionId)] = $app->share(
            function ($app) use($self)
            {
                $config = $app[Extension::CONTAINER_ID]->getConfig();
                $names = array_keys($config[$self->sectionId]);
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

    protected function serviceFQName($classname)
    {
        return sprintf('\\Bolt\\Extension\\Rixbeck\\Gapps\\Service\\%sService', ucfirst($classname));
    }
}
