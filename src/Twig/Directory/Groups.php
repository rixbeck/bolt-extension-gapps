<?php
namespace Bolt\Extension\Rixbeck\Gapps\Twig\Directory;

use Bolt\Application;
use Bolt\Extension\Rixbeck\Gapps\Provider\CalendarServiceProvider;
use Bolt\Extension\Rixbeck\Gapps\Extension;
use Bolt\Extension\Rixbeck\Gapps\Recurrences;
use Bolt\Extension\Rixbeck\Gapps\Iterator\PagingEventsIterator;
use Bolt\Extension\Rixbeck\Gapps\EventMatrix;

class Groups extends \Twig_Extension
{

    protected $app;

    protected $functions;

    // put default functions here which must work on both backend and frontend
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->functions = $this->createFunctions();
    }

    /*
     * (non-PHPdoc)
     * @see Twig_Extension::getFunctions()
     */
    public function getFunctions()
    {
        return $this->functions;
    }

    protected function createFunctions()
    {
        $whichend = $this->app['config']->getWhichEnd();
        $functions = sprintf('%sFunctions', $whichend);

        return $this->$functions();
    }

    protected function frontendFunctions()
    {
        return array(
            'directorygroups' => new \Twig_Function_Method($this, 'getService')
        );
    }

    protected function backendFunctions()
    {
        return array();
    }

    protected function asyncFunctions()
    {
        return array();
    }

    public function canAdd()
    {
        return ! empty($this->functions);
    }

    public function getService($serviceName)
    {
        $service = $this->app[Extension::getProviderId('directory.groups')][$serviceName];
        $service->initialize();

        return $service;
    }

    public function getName()
    {
        return 'gapps.directorygroups';
    }
}
