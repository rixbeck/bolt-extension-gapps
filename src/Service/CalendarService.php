<?php

namespace Bolt\Extension\RixBeck\Gapps\Service;

use Bolt\Extension\RixBeck\Gapps\Iterator\PagingEventsIterator;

/**
 *
 * @author Rix Beck <rix at neologik.hu>
 *         Copyright 2015
 *
 * @property \Bolt\Extension\RixBeck\Gapps\Service\AccountsService $account
 * @property \Google_Service_Calendar $service
 */
class CalendarService extends BaseService
{
    /**
     * @var PagingEventsIterator
     */
    protected $events;

    public function test()
    {
        return 'Test is OK';
    }

    public function initialize()
    {
        parent::initialize();

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
        ($this->account->getClient())->addScope(\Google_Service_Calendar::CALENDAR);

        return $this->service;
    }

    /*
     * (non-PHPdoc)
     * @see \Bolt\Extension\RixBeck\Gapps\Service\BaseService::createService()
     */

    public function eventList($options = array())
    {
        $this->defaultOptions = array(
            'singleEvents' => true
        );

        $options = $this->prepareOptions(strtolower(__FUNCTION__), $options);
        $calId = $this->config['CalendarID'];

        return $this->events = (new PagingEventsIterator($this->service->events, $options))->setCalendarId($calId);
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
        $evt = $this->service->events;
        $event = $evt->get($this->config['CalendarID'], $id, $options);

        return $event;
    }

    protected function createService($client)
    {
        $this->service = new \Google_Service_Calendar($client);
    }
}
