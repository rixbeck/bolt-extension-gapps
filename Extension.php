<?php
namespace Bolt\Extension\Rixbeck\Gapps;

use Bolt\BaseExtension;

class Extension extends BaseExtension
{

    const NAME = 'Gapps';

    const CONTAINER_ID = 'extensions.Gapps';

    protected $baseDir;

    public function getName()
    {
        return static::NAME;
    }

    public function initialize()
    {
        $this->baseDir = __DIR__;
        $this->initializeModules();
        $this->config = $this->app[self::CONTAINER_ID]->getConfig();
    }

    protected function initializeModules()
    {
        foreach ($this->config['modules'] as $module) {
            $this->initializeProvider($module);
            $this->initializeTwig($module);
        }
    }

    protected function initializeTwig($module)
    {
        $twigmodule = $this->createTwigModule($module);
        $this->app['twig']->addExtension($twigmodule);
    }

    protected function createTwigModule($modulename)
    {
        $class = sprintf("%s\\Twig\\%s", __NAMESPACE__, ucfirst($modulename));
        $obj = new $class($this->app);

        return $obj;
    }

    protected function initializeProvider($module)
    {
        $class = sprintf("%s\\Provider\\%sServiceProvider", __NAMESPACE__, ucfirst($module));
        if (class_exists($class)) {
            $this->app->register(new $class($this->app['config']->getWhichEnd() === 'frontend'));
        }
    }
}
