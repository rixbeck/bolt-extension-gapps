<?php

namespace Bolt\Extension\RixBeck\Gapps\Service;

use Bolt\Collection\Arr;
use Bolt\Extension\RixBeck\Gapps\RecordType;

abstract class BaseService
{
    public $config;
    public $name;
    /**
     * Account service
     *
     * @var AccountsService
     */
    public $account;
    protected $service;
    protected $defaultOptions;
    protected $recordType;
    protected $recordTypes;

    public function __construct(AccountsAwareInterface $account, array $config, array $recordTypes)
    {
        $this->account = $account;
        $this->config = $config;
        $this->recordTypes = $recordTypes;
    }

    /**
     * @return \Google_Service_Calendar
     * @throws \Google_Exception
     */
    public function initialize()
    {
        if (!$this->service) {
            $client = $this->account->authenticate();
            $this->createService($client);
        }

        return $this->service;
    }

    public function getService()
    {
        return $this->service;
    }

    abstract protected function createService($client);

    protected function initializeDefaultOptions($method = '')
    {
        $this->recordType = array();
        $valRecordType = Arr::get($this->config, "{$this->name}/recordtype/{$method}", 'full');
        if ($valRecordType !== 'full') {
            $this->recordType = RecordType::decode($this->recordTypes[$valRecordType]);
        }
    }

    protected function prepareOptions($methodfor, $options = array())
    {
        $this->initializeDefaultOptions($methodfor);
        if (!empty($options)) {
            if (key_exists('fields', $options)) {
                $fields = $this->recordType;
                $fields = array_merge_recursive($fields, $options['fields']);
                $fields = new RecordType($fields);
                $options['fields'] = (string) $fields;
            }
        }
        if (!empty($this->recordType) && empty($fields)) {
            $fields = new RecordType($this->recordType);
            $options['fields'] = (string) $fields;
        }
        $options = array_merge($this->defaultOptions, $options);

        return $options;
    }
}
