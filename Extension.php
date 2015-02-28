<?php
namespace Bolt\Extension\Rixbeck\Gapps;

use Bolt\BaseExtension;

/**
 * This extension initializes its modules as services under $app container.
 *
 * @author Rix Beck <rix at neologik.hu>
 * Copyright 2015
 *
 */
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

    /**
     * Initializes service providers, twig extensions by module definition
     */
    protected function initializeModules()
    {
        $modules = array(
            'accounts'
        );
        $modules = array_merge($modules, $this->config['modules']);
        foreach ($modules as $module) {
            $this->initializeProvider($module);
            $this->initializeTwigModule($module);
        }

        $this->initializeTwigModule('general');
    }

    /**
     * Initializes a twig module
     * @param unknown $module
     */
    protected function initializeTwigModule($module)
    {
        $twigmodule = $this->createTwigModule($module);
        if ($twigmodule && $twigmodule->canAdd()) {
            $this->app['twig']->addExtension($twigmodule);
        }
    }

    /**
     * Creates the twig module
     * @param unknown $modulename
     * @return unknown|boolean
     */
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

    /**
     * Service provider initializer for module
     * @param unknown $module
     */
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
