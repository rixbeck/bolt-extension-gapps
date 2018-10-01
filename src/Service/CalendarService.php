<?php
namespace Bolt\Extension\Rixbeck\Gapps\Service;

use Bolt\Application;
use Bolt\Extension\Rixbeck\Gapps\Extension;
use Bolt\Extension\Rixbeck\Gapps\Iterator\PagingEventsIterator;
use Bolt\Extension\Rixbeck\Gapps\RecordType;

/**
 *
 * @author Rix Beck <rix at neologik.hu>
 *         Copyright 2015
 *
 * @property \Bolt\Extension\Rixbeck\Gapps\Service\AccountsService $account
 */
class CalendarService extends BaseService
{

    protected $events;

    public function test()
    {
        return 'Test is OK';
    }

    public function initialize()
    {
        $this->recordType = array(
            'description',
            'nextSyncToken',
            'summary',
            'items' => array(
                'created',
                'description',
                'kind',
                'originalStartTime',
                'recurrence',
                'source',
                'start',
                'status',
                'summary'
            )
        );

        return parent::initialize();
    }

    /*
     * (non-PHPdoc)
     * @see \Bolt\Extension\Rixbeck\Gapps\Service\BaseService::createService()
     */
    protected function createService($client)
    {
        $this->service = new \Google_Service_Calendar($client);
    }

    public function eventList($options = array())
    {
        $this->defaultOptions = array(
            'singleEvents' => true
        );

        $options = $this->prepareOptions(strtolower(__FUNCTION__), $options);

        return $this->events = new PagingEventsIterator($this->service->events, $this->config['CalendarID'], $options);
    }

    public function info()
    {
        if ($this->events) {
            return $this->events->getEventlist();
        }

        return false;
    }

    public function upcoming($howmany = 100)
    {
        $tz = date_default_timezone_get();
        $now = new \DateTime();
        $end = clone $now;
        $end = $end->add(\DateInterval::createFromDateString('1 year'));
        $endDate = $end->format('c');

        $options = array(
            'timeMax' => $endDate,
            'timeMin' => $now->format('c'),
            'timeZone' => $tz,
            'maxResults' => $howmany,
            'orderBy' => 'startTime'
        );

        return $this->eventList($options);
    }

    public function getEvent($id, $options = array())
    {
        $this->defaultOptions = array();

        $options = $this->prepareOptions(strtolower(__FUNCTION__), $options);
        /* @var $evt \Google_Service_Calendar_Events_Resource */
        $evt = $this->service->events;
        $event = $evt->get($this->config['CalendarID'], $id, $options);

        return $event;
    }
}
