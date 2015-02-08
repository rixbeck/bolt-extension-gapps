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
        $modules = array(
            'accounts'
        );
        $modules = array_merge($modules, $this->config['modules']);
        foreach ($modules as $module) {
            $this->initializeProvider($module);
            $this->initializeTwig($module);
        }
    }

    protected function initializeTwig($module)
    {
        $twigmodule = $this->createTwigModule($module);
        if ($twigmodule) {
            $this->app['twig']->addExtension($twigmodule);
        }
    }

    protected function createTwigModule($modulename)
    {
        $class = sprintf("%s\\Twig\\%s", __NAMESPACE__, ucfirst($modulename));
        if (class_exists($class)) {
            $obj = new $class($this->app);
            return $obj;
        }

        return false;
    }

    protected function initializeProvider($module)
    {
        $class = sprintf("%s\\Provider\\%sServiceProvider", __NAMESPACE__, ucfirst($module));
        if (class_exists($class)) {
            $this->app->register(new $class($this->app['config']->getWhichEnd() === 'frontend'));
        }
    }

    public static function getProviderId($id)
    {
        return sprintf("%s.%s", self::CONTAINER_ID, $id);
    }
}
