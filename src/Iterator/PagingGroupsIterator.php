<?php
namespace Bolt\Extension\RixBeck\Gapps\Iterator;

/**
 *
 * @author Rix Beck <rix at neologik.hu>
 *         Copyright 2015
 *
 * @property \Google_Service_Directory_Resource_Groups $resource
 */
class PagingGroupsIterator extends BasePagingIterator
{
    protected $domain;

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
