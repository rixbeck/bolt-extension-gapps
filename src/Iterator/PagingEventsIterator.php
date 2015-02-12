<?php
namespace Bolt\Extension\Rixbeck\Gapps\Iterator;

class PagingEventsIterator implements \Iterator, \ArrayAccess
{

    protected $events;

    protected $calendarId;

    protected $options;

    protected $eventlist;

    protected $items;

    protected $actual;

    public function __construct(\Google_Service_Calendar_Events_Resource $events, $calendarId, $options = array())
    {
        $this->events = $events;
        $this->calendarId = $calendarId;
        $this->options = $options;
    }

    public function next()
    {
        $this->actual = next($this->items);
        if (! $this->valid()) {
            $pageToken = $this->eventlist->getNextPageToken();
            if ($pageToken) {
                if (isset($this->options['pageToken'])) {
                    unset($$this->options['pageToken']);
                }
                $this->options['pageToken'] = $pageToken;
                $this->rewind();
            }
        }
    }

    public function valid()
    {
        return ($this->key() !== null && $this->key() !== false);
    }

    public function current()
    {
        return $this->actual;
    }

    public function fetch()
    {
        $this->eventlist = $this->events->listEvents($this->calendarId, $this->options);
        $this->items = null;
        $this->items = $this->eventlist->getItems();
    }

    public function rewind()
    {
        $this->fetch();
        $this->actual = current($this->items);
    }

    public function key()
    {
        return key($this->items);
    }

    public function getEventlist()
    {
        return $this->eventlist;
    }

    /*
     * (non-PHPdoc)
     * @see ArrayAccess::offsetExists()
     */
    public function offsetExists($offset)
    {
        if (!$this->items) {
            $this->rewind();
        }

        return (isset($this->items[$offset]));
    }

    /*
     * (non-PHPdoc)
     * @see ArrayAccess::offsetGet()
     */
    public function offsetGet($offset)
    {
        return $this->items[$offset];
    }

    /*
     * (non-PHPdoc)
     * @see ArrayAccess::offsetSet()
     */
    public function offsetSet($offset, $value)
    {
        $this->items[$offset] = $value;
    }

    /*
     * (non-PHPdoc)
     * @see ArrayAccess::offsetUnset()
     */
    public function offsetUnset($offset)
    {
        unset($this->items[$offset]);
    }
}