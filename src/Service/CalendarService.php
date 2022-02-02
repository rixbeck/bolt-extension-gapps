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

        $this->recordType = [
            'description',
            'nextSyncToken',
            'summary',
            'items' => [
                'created',
                'description',
                'kind',
                'originalStartTime',
                'recurrence',
                'source',
                'start',
                'status',
                'summary',
            ],
        ];
        ($this->account->getClient())->addScope(\Google_Service_Calendar::CALENDAR);

        return $this->service;
    }

    /*
     * (non-PHPdoc)
     * @see \Bolt\Extension\RixBeck\Gapps\Service\BaseService::createService()
     */

    public function eventList($options = [], $from = null, $to = null)
    {
        $this->defaultOptions = [
            'singleEvents' => true,
        ];

        if ($from || $to) {
            $options = array_merge($options, $this->applyRangeOptions($from, $to));
        }

        $options = $this->prepareOptions(strtolower(__FUNCTION__), $options);
        $calId = $this->config['CalendarID'];

        return $this->events = (new PagingEventsIterator(
            $this->service->events, $options
        ))->setCalendarId($calId);
    }

    /**
     * @return \DateTime|false
     */
    public static function firstDayOfWeek()
    {
        return (new \DateTime())->modify('monday -1 week')->setTime(0, 0, 0);
    }

    /**
     * @return \DateTime|false
     */
    public static function lastDayOfWeek()
    {
        return (new \DateTime())->modify('monday 0 week')->setTime(0, 0, 0);
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

        $options = [
            'timeMax' => $endDate,
            'timeMin' => $now->format('c'),
            'timeZone' => $tz,
            'maxResults' => $howmany,
            'orderBy' => 'startTime',
        ];

        return $this->eventList($options);
    }

    public function getEvent($id, $options = [])
    {
        $this->defaultOptions = [];

        $options = $this->prepareOptions(strtolower(__FUNCTION__), $options);
        $evt = $this->service->events;
        $event = $evt->get($this->config['CalendarID'], $id, $options);

        return $event;
    }

    protected function createService($client)
    {
        $this->service = new \Google_Service_Calendar($client);
    }

    protected function applyRangeOptions($from = null, $to = null)
    {
        $options = array();
        if ($from) {
            /** @var \DateTime $start */
            $start = self::$from();
            $options['timeMin'] = $start->format('c');
        }
        if ($to) {
            /** @var \DateTime $to */
            $end = self::$to();
            $options['timeMax'] = $end->format('c');
        }

        return $options;
    }
}
