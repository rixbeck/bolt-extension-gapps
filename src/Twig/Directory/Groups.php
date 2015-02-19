<?php
namespace Bolt\Extension\Rixbeck\Gapps\Twig\Directory;

use Bolt\Application;
use Bolt\Extension\Rixbeck\Gapps\Provider\CalendarServiceProvider;
use Bolt\Extension\Rixbeck\Gapps\Extension;
use Bolt\Extension\Rixbeck\Gapps\Recurrences;
use Bolt\Extension\Rixbeck\Gapps\Iterator\PagingEventsIterator;
use Bolt\Extension\Rixbeck\Gapps\EventMatrix;
use Bolt\Extension\Rixbeck\Gapps\Twig\BaseExtension;

class Groups extends BaseExtension
{

    protected function frontendFunctions()
    {
        return array(
            'directorygroups' => new \Twig_Function_Method($this, 'getService')
        );
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
