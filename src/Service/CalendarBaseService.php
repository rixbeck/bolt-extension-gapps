<?php
namespace Bolt\Extension\Rixbeck\Gapps\Service;

use Bolt\Application;
use Bolt\Extension\Rixbeck\Gapps\Extension;
use Bolt\Extension\Rixbeck\Gapps\Iterator\PagingEventsIterator;
use Bolt\Extension\Rixbeck\Gapps\CalendarItemFields;

/**
 *
 * @author Rix Beck <rix at neologik.hu>
 *         Copyright 2015
 *
 * @property \Bolt\Extension\Rixbeck\Gapps\Service\AccountsService $account
 */
class CalendarBaseService
{

    protected $app;

    public $config;

    public $calname;

    public $accountName;

    public $account;

    protected $service;

    protected $events;

    protected $defaultOptions;

    protected $eventType = array();

    public function __construct(Application $app, $calname)
    {
        // @todo implement config check
        $this->app = $app;
        $config = $this->app[Extension::CONTAINER_ID]->getConfig()['calendar'];
        $this->config = $config[$this->calname = $calname];
        $this->accountName = $this->config['account'];
    }

    public function test()
    {
        return 'Test is OK';
    }

    public function initialize()
    {
        $this->initializeDefaultOptions();

        if (! $this->service) {
            $this->account = $this->app[Extension::getProviderId('accounts')][$this->accountName];
            $cred = $this->account->createCredentialsFor('calendar');
            $client = $this->account->authenticate($cred);
            $this->service = new \Google_Service_Calendar($client);
        }

        return $this->service;
    }

    protected function initializeDefaultOptions($defaults = array())
    {
        $etype = $this->app[Extension::CONTAINER_ID]->getConfig()['eventtypes'];
        if ($this->config['eventtype'] !== 'full') {
            $eventtype = CalendarItemFields::decode($etype[$this->config['eventtype']]);
            $this->eventType = $eventtype ?  : array(
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
        }

        $this->defaultOptions = array_merge($defaults, array(
            'singleEvents' => true
        ));
    }

    protected function prepareOptions($options = array())
    {
        if (! empty($options)) {
            if (key_exists('fields', $options)) {
                $fields = $this->eventType;
                $fields = array_merge_recursive($fields, $options['fields']);
                $fields = new CalendarItemFields($fields);
                $options['fields'] = (string) $fields;
            }
        }
        if (! empty($this->eventType) && empty($fields)) {
            $fields = new CalendarItemFields($this->eventType);
            $options['fields'] = (string) $fields;
        }
        $options = array_merge($this->defaultOptions, $options);

        return $options;
    }

    public function eventList($options = array())
    {
        $options = $this->prepareOptions($options);

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

    public function getService()
    {
        return $this->service;
    }
}
