<?php
namespace Bolt\Extension\RixBeck\Gapps\Twig;

use Bolt\Application;

abstract class BaseExtension extends \Twig_Extension
{
    protected $app;

    protected $functions;

    protected $filters;

    protected $whichend;

    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->whichend = $this->app['config']->getWhichEnd();
        $this->functions = $this->setFunctions($this->whichend);
        $this->filters = $this->setFilters($this->whichend);
    }

    public function setFunctions($whichend)
    {
        $functions = sprintf('%sFunctions', $whichend);

        return $this->$functions();
    }

    public function setFilters($whichend)
    {
        $filters = sprintf('%sFilters', $whichend);

        return $this->$filters();
    }

    public function getFunctions()
    {
        return $this->functions;
    }

    public function getFilters()
    {
        return $this->filters;
    }

    public function canAdd()
    {
        return count($this->functions) || count($this->filters);
    }

    protected function frontendFunctions()
    {
        return array();
    }

    protected function backendFunctions()
    {
        return array();
    }

    protected function asyncFunctions()
    {
        return array();
    }

    protected function frontendFilters()
    {
        return array();
    }

    protected function backendFilters()
    {
        return array();
    }

    protected function asyncFilters()
    {
        return array();
    }

    protected function cliFunctions()
    {
        return array();
    }

    protected function cliFilters()
    {
        return array();
    }

}