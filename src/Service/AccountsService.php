<?php

namespace Bolt\Extension\RixBeck\Gapps\Service;

use Bolt\Extension\RixBeck\Gapps\Exception\AccountServiceException;

/**
 * Account service for google apps service calls.
 * In config.yml you may define an account section which must be referred by services.
 * See config.yml.dist for sample.
 *
 * @author Rix Beck <rix at neologik.hu>
 * Copyright 2015
 *
 */
class AccountsService implements AccountsAwareInterface
{
    protected $app;
    protected $config;
    protected $accountId;
    protected $client;
    protected $apiKey;
    protected $appName;
    protected $authConfig;
    /**
     * @var string
     */
    private $configDir;

    /**
     * Instance of an gapps account for using api
     *
     * @param array $config
     * @param string $accountId
     * @param string $configDir
     * @throws AccountServiceException
     */
    public function __construct(array $config, $accountId = '', $configDir = '')
    {
        $this->config = $config;
        $this->accountId = $accountId;
        $this->prepare();
        $this->configDir = $configDir;
    }

    /**
     * @return string
     */
    public function getAccountId()
    {
        return $this->accountId;
    }

    /**
     * @return \Google_Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Authorize API with credentials
     * @return \Google_Client
     * @throws \Google_Exception
     */
    public function authenticate()
    {
        $this->client = $client = new \Google_Client();
        $this->client->setApplicationName($this->appName);
        $this->client->setAuthConfigFile("{$this->configDir}/{$this->authConfig}");
        $this->client->setSubject('administrator@brke.org');

        return $this->client;
    }

    /**
     * @throws AccountServiceException
     */
    protected function prepare()
    {
        $this->appName = (array_key_exists('app_name', $this->config)) ? $this->config['app_name'] : '';
        if ($this->appName == '') {
            throw new AccountServiceException("Missing application name in config({$this->accountId})");
        }

        $this->authConfig = (array_key_exists('KeyFile', $this->config)) ? $this->config['KeyFile'] : '';
        if ($this->authConfig == '') {
            throw new AccountServiceException("Missing application KeyFile in config({$this->accountId})");
        }
/*
        $this->accountId = (array_key_exists('api_key', $this->config)) ? $this->config['api_key'] : '';
        if ($this->accountId == '') {
            throw new AccountServiceException("Missing application api_key in config({$this->accountId})");
        }*/
    }
}
