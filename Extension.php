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

        $this->initializeTwig('general');
    }

    protected function initializeTwig($module)
    {
        $twigmodule = $this->createTwigModule($module);
        if ($twigmodule && $twigmodule->canAdd()) {
            $this->app['twig']->addExtension($twigmodule);
        }
    }

    protected function createTwigModule($modulename)
    {
        $classpath = $this->moduleToClassname($modulename);
        $class = sprintf("%s\\Twig\\%s", __NAMESPACE__, $classpath);
        if (class_exists($class)) {
            $obj = new $class($this->app);
            return $obj;
        }

        return false;
    }

    protected function initializeProvider($module)
    {
        $classpath = $this->moduleToClassname($module);
        $class = sprintf("%s\\Provider\\%sServiceProvider", __NAMESPACE__, $classpath);
        if (class_exists($class)) {
            $this->app->register(new $class($this->app));
        }
    }

    public function isSafe()
    {
        return true;
    }

    public static function getProviderId($id)
    {
        return sprintf("%s.%s", self::CONTAINER_ID, $id);
    }

    public function getConfig($path = '', $delim = '/')
    {
        $conf = parent::getConfig();
        if ($path) {
            $segments = explode($delim, $path);
            foreach ($segments as $index) {
                if (array_key_exists($index, $conf)) {
                    $conf = $conf[$index];
                } else {
                    return false;
                }
            }
        }

        return $conf;
    }

    public function moduleToClassname($modulename)
    {
        $classpath = implode('\\', array_map('ucfirst', explode('.', $modulename)));

        return $classpath;
    }

    public function classToModulename($classname)
    {
        $modulename = implode('.', array_map('lcfirst', explode('\\', $classname)));

        return $modulename;
    }
}
