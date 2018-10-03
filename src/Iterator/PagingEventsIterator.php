<?php
namespace Bolt\Extension\RixBeck\Gapps\Iterator;

/**
 *
 * @author Rix Beck <rix at neologik.hu>
 *         Copyright 2015
 *
 * @property \Google_Service_Calendar_Resource_Events $resource
 */
class PagingEventsIterator extends BasePagingIterator
{
    protected $calendarId;

    /**
     * @param mixed $calendarId
     * @return PagingEventsIterator
     */
    public function setCalendarId($calendarId)
    {
        $this->calendarId = $calendarId;

        return $this;
    }

    public function fetch()
    {
        $this->collection = $this->resource->listEvents($this->calendarId, $this->options);
        $this->items = null;
        $this->items = $this->collection->getItems();
    }

    public function getEventlist()
    {
        return parent::getList();
    }
}
