<?php
namespace Bolt\Extension\Rixbeck\Gapps\Twig;

use Bolt\Application;
use Bolt\Extension\Rixbeck\Gapps\Extension;
use Bolt\Extension\Rixbeck\Gapps\Service\ExtenderService;
use Bolt\Extension\Rixbeck\Gapps\Iterator\BasePagingIterator;

class Extender extends BaseExtension
{

    protected $instances = array();

    protected function frontendFilters()
    {
        return array(
            new \Twig_SimpleFilter('attachform',
                array(
                    $this,
                    'attachForm'
                ), array(
                    'needs_environment' => true
                ))
        );
    }

    /*public function getService($formname)
    {
        if (array_key_exists($formname, $this->instances)) {
            return $this->instances[$formname];
        }

        return $this->instances[$formname] = new ExtenderService($this->app, $formname);
    }*/
    public function getService($formname)
    {
        return new ExtenderService($this->app, $formname);
    }

    public function attachForm(\Twig_Environment $env, $elements, $formname, $options)
    {
        if ($elements instanceof BasePagingIterator) {
            $service = $this->getService($formname);
            $service->attachForm($elements, $options);
        }
    }


    public function getName()
    {
        return "gapps.extender";
    }
}