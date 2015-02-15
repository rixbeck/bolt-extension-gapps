<?php
namespace Bolt\Extension\Rixbeck\Gapps;

class Recurrences
{

    public $event;

    public $recurrences = array();

    public function __construct(\Google_Service_Calendar_Event $event)
    {
        $this->event = $event;
        $this->decodeRecurrence();
    }

    protected function decodeRecurrence()
    {
        $recurr = $this->event->getRecurrence();
        if ($recurr) {
            foreach ($recurr as $recurrence) {
                $when = new When();
                $rule = substr($recurrence, 6);
                $when->recur($this->event->getStart()->dateTime)
                    ->rrule($rule);
                $this->recurrences[] = $when;
            }
        }
    }
}
