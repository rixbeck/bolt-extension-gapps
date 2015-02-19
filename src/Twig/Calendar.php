<?php
namespace Bolt\Extension\Rixbeck\Gapps\Twig;

use Bolt\Application;
use Bolt\Extension\Rixbeck\Gapps\Provider\CalendarServiceProvider;
use Bolt\Extension\Rixbeck\Gapps\Extension;
use Bolt\Extension\Rixbeck\Gapps\Recurrences;
use Bolt\Extension\Rixbeck\Gapps\Iterator\PagingEventsIterator;
use Bolt\Extension\Rixbeck\Gapps\EventMatrix;

class Calendar extends BaseExtension
{

    protected function frontendFunctions()
    {
        return array(
            'calendarevents' => new \Twig_Function_Method($this, 'getService'),
            'recurrences' => new \Twig_Function_Method($this, 'createRecurrence'),
            'eventmatrix' => new \Twig_Function_Method($this, 'createEventMatrix')
        );
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
