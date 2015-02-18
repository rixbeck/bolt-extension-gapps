<?php
namespace Bolt\Extension\Rixbeck\Gapps\Iterator;

/**
 *
 * @author Rix Beck <rix at neologik.hu>
 *         Copyright 2015
 *
 * @property \Google_Service_Directory_Groups_Resource $resource
 * @property \Google_Service_Directory_Groups $collection
 */
class PagingGroupsIterator extends BasePagingIterator
{

    protected $domain;

    public function __construct(\Google_Service_Directory_Groups_Resource $resource, $options = array())
    {
        parent::__construct($resource, $options);
    }

    public function fetch()
    {
        $this->collection = $this->resource->listGroups($this->options);
        $this->items = null;
        $this->items = $this->collection->getGroups();
    }

    public function getGrouplist()
    {
        parent::getList();
    }
}
