<?php
namespace Bolt\Extension\RixBeck\Gapps\Twig;

use Bolt\Extension\RixBeck\Gapps\EventMatrix;
use Bolt\Extension\RixBeck\Gapps\Iterator\PagingEventsIterator;
use Bolt\Extension\RixBeck\Gapps\Recurrences;

class Calendar
{
    /**
     * @var \Pimple
     */
    private $calServices;

    public function __construct(\Pimple $calServices)
    {
        $this->calServices = $calServices;
    }

    public function getService($calendarName)
    {
        $service = $this->calServices[$calendarName];
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
}
