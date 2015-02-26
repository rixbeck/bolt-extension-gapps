<?php
namespace Bolt\Extension\Rixbeck\Gapps\Service;

use Bolt\Application;
use Bolt\Extension\Rixbeck\Gapps\Extension;
use Bolt\Extension\Rixbeck\Gapps\RecordType;

abstract class BaseService
{

    protected $app;

    public $config;

    /**
     *
     * @var service type name (unique) section in config
     */
    public $name;

    /**
     * Account service
     *
     * @var AccountsService
     */
    public $account;

    /**
     *
     * @var Google service instance
     */
    protected $service;

    /**
     *
     * @var Service type
     */
    protected $serviceName;

    /**
     *
     * @var Default options for service call
     */
    protected $defaultOptions;

    protected $recordType;

    abstract protected function createService($client);

    public function __construct(Application $app, $name, $sectionname)
    {
        // @todo implement config check
        $this->app = $app;
        $config = $this->app[Extension::CONTAINER_ID]->getConfig($sectionname, '.');
        $this->config = $config[$this->name = $name];
        $this->accountName = $this->config['account'];
        $this->serviceName = $this->createScopes($sectionname);
    }

    public function initialize()
    {
        // $this->initializeDefaultOptions();

        if (! $this->service) {
            $this->account = $this->app[Extension::getProviderId('accounts')][$this->accountName];
            $cred = $this->account->createCredentialsFor($this->serviceName);
            $client = $this->account->authenticate($cred);
            $this->createService($client);
        }

        return $this->service;
    }

    protected function initializeDefaultOptions($method = '')
    {
        $this->recordType = array();
        /* @var $ext \Bolt\Extension\Rixbeck\Gapps\Extension */
        $ext = $this->app[Extension::CONTAINER_ID];
        $etype = $ext->getConfig('recordtypes');
        $section = implode('/', array($this->serviceName, $this->name, 'recordtype', $method));
        $value = $ext->getConfig($section) ?: 'full';
        if ($value !== 'full') {
            $recordtype = RecordType::decode($etype[$value]);
            $this->recordType = $recordtype ?  : $this->recordType;
        }
    }

    protected function prepareOptions($methodfor, $options = array())
    {
        $this->initializeDefaultOptions($methodfor);
        if (! empty($options)) {
            if (key_exists('fields', $options)) {
                $fields = $this->recordType;
                $fields = array_merge_recursive($fields, $options['fields']);
                $fields = new RecordType($fields);
                $options['fields'] = (string) $fields;
            }
        }
        if (! empty($this->recordType) && empty($fields)) {
            $fields = new RecordType($this->recordType);
            $options['fields'] = (string) $fields;
        }
        $options = array_merge($this->defaultOptions, $options);

        return $options;
    }

    protected function createScopes($section)
    {
        return explode('.', $section)[0];
    }

    public function getService()
    {
        return $this->service;
    }
}