<?php
namespace Bolt\Extension\Rixbeck\Gapps\Iterator;

/**
 *
 * @author Rix Beck <rix at neologik.hu>
 *         Copyright 2015
 *
 * @property \Google_Service_Calendar_Events_Resource $resource
 * @property \Google_Service_Calendar_Events $collection
 */
class PagingEventsIterator extends BasePagingIterator
{

    protected $calendarId;

    public function __construct(\Google_Service_Calendar_Events_Resource $events, $calendarId, $options = array())
    {
        parent::__construct($events, $options);
        $this->calendarId = $calendarId;
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
