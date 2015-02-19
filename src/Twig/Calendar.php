<?php
namespace Bolt\Extension\Rixbeck\Gapps\Twig;

use Bolt\Application;
use Bolt\Extension\Rixbeck\Gapps\Provider\CalendarServiceProvider;
use Bolt\Extension\Rixbeck\Gapps\Extension;
use Bolt\Extension\Rixbeck\Gapps\Recurrences;
use Bolt\Extension\Rixbeck\Gapps\Iterator\PagingEventsIterator;
use Bolt\Extension\Rixbeck\Gapps\EventMatrix;

class Calendar extends \Twig_Extension
{

    protected $app;

    protected $functions;

    // put default functions here which must work on both backend and frontend
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->functions = $this->createFunctions();
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
            'calendarevents' => new \Twig_Function_Method($this, 'getService'),
            'recurrences' => new \Twig_Function_Method($this, 'createRecurrence'),
            'eventmatrix' => new \Twig_Function_Method($this, 'createEventMatrix')
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
        return !empty($this->functions);
    }

    public function getService($calendarName)
    {
        $service = $this->app[Extension::getProviderId('calendar')][$calendarName];
        $service->initialize();

        return $service;
    }

    public function createRecurrence(\Google_Service_Calendar_Event $event)
    {
        return new Recurrences($event);
    }

    public function createEventMatrix(PagingEventsIterator $events, $type = 'weekbyhours')
    {
        $matrix = new EventMatrix($events, $type);

        return $matrix->matrix;
    }

    public function getName()
    {
        return 'gapps.calendar';
    }
}
