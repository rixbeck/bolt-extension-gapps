<?php
namespace Bolt\Extension\RixBeck\Gapps;

use Bolt\Extension\RixBeck\Gapps\Iterator\PagingEventsIterator;

class EventMatrix
{

    public $matrix;

    protected $events;

    protected $weekdays = array(
        'MO',
        'TU',
        'WE',
        'TH',
        'FR',
        'SA',
        'SU'
    );

    public function __construct(PagingEventsIterator $events, $type = 'weekbyhours')
    {
        $this->events = $events;
        if (method_exists($this, $type)) {
            $this->{$type}();
        }
    }

    protected function weekbyhours()
    {
        $this->matrix = array();

        foreach ($this->events as $event) {
            $recur = new Recurrences($event);
            foreach ($recur->recurrences as $recurrence) {
                /* @var $recurrence When */
                if ($recurrence->getFequency() == 'WEEKLY') {
                    foreach ($recurrence->getByDay() as $day) {
                        if ($day[0] == '+') {
                            $weekday = substr($day, 2);
                            $start = new \DateTime($event->getStart()->dateTime);
                            $time = $start->format('H:i');
                            if (! key_exists($time, $this->matrix)) {
                                $this->matrix[$time] = array();
                            }
                            $this->matrix[$time][$weekday][] = $event;
                        }
                    }
                }
            }
        }

        ksort($this->matrix);
        return $this->matrix;
    }
}