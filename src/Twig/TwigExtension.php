<?php
namespace Bolt\Extension\Rixbeck\Gapps\Twig;

use Bolt\Extension\Rixbeck\Gapps\Extension;

class TwigExtension extends \Twig_Extension
{

    /**
     *
     * @var Application
     */
    protected $app;

    /**
     *
     * @var array
     */
    protected $config;

    protected $functions;

    public function __construct(\Silex\Application $app)
    {
        $this->app = $app;
        $this->config = $this->app[Extension::CONTAINER_ID]->getConfig();

        foreach ($this->config['modules'] as $module) {
            $twigmodule = $this->createModule($module);
            $twigmodule->initialize($app['config']->getWhichEnd() === 'frontend');
            $this->functions = array_merge($this->functions, $twigmodule->getFunctions());
        }
    }

    protected function createModule($modulename)
    {
        $class = __NAMESPACE__ . '\\' . ucfirst($modulename);
        $obj = new $class($this->app);

        return $obj;
    }

    protected function getFunctions()
    {
        return $this->functions;
    }
}