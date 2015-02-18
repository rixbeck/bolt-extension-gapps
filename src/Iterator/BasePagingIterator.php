<?php
namespace Bolt\Extension\Rixbeck\Gapps\Iterator;

abstract class BasePagingIterator implements \Iterator, \ArrayAccess
{

    protected $resource;

    protected $options;

    protected $collection;

    protected $items;

    protected $actual;

    abstract public function fetch();

    public function __construct(\Google_Service_Resource $resource, $options = array())
    {
        $this->resource = $resource;
        $this->options = $options;
    }

    public function next()
    {
        $this->actual = next($this->items);
        if (! $this->valid()) {
            $pageToken = $this->collection->getNextPageToken();
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

    public function rewind()
    {
        $this->fetch();
        $this->actual = current($this->items);
    }

    public function key()
    {
        return key($this->items);
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

    public function getList()
    {
        if (!$this->collection) {
            $this->fetch();
        }
        return $this->collection;
    }
}
