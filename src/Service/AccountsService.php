<?php
namespace Bolt\Extension\Rixbeck\Gapps\Service;

use Bolt\Application;
use Bolt\Extension\Rixbeck\Gapps\Extension;
use Bolt\Extension\Rixbeck\Gapps\Exception\AccountServiceException;

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

    protected $key;

    protected $serviceId;
    private $appId;

    /**
     * Instance of an gapps account for using api
     *
     * @param array $config
     * @param $appId
     * @param string $accountId
     */
    public function __construct(array $config, $appId, $accountId = '')
    {
        $this->config = $config;
        $this->accountId = $accountId;
        $this->appId = $appId;
    }

    /**
     * Authorize API with credentials
     * @param \Google_Auth_AssertionCredentials $cred
     * @return \Google_Client
     */
    public function authenticate(\Google_Auth_AssertionCredentials $cred)
    {
        $this->client = $client = new \Google_Client();
        $this->client->setApplicationName($this->appId);

        if (isset($_SESSION['token_gapps'])) {
            $client->setAccessToken($_SESSION['token_gapps']);
        }

        $client->setClientId($this->config['ClientID']);
        $client->setAssertionCredentials($cred);

        if ($client->getAuth()->isAccessTokenExpired()) {
            $client->getAuth()->refreshTokenWithAssertion($cred);
        }

        $_SESSION[$this->getServiceSessionTk($cred->scopes)] = $client->getAccessToken();

        return $this->client;
    }

    /**
     * Creates creadentials for a specific google apps service like 'calendar'
     *
     * @param string $serviceName
     * @return \Google_Auth_AssertionCredentials
     * @throws AccountServiceException
     */
    public function createCredentialsFor($scopeNames)
    {
        $scopeNames = (array) $scopeNames;
        $scopes = array_map(function ($el)
        {
            return sprintf('https://www.googleapis.com/auth/%s', $el);
        }, $scopeNames);
        $cred = new \Google_Auth_AssertionCredentials($this->config['ServiceID'], $scopes, $this->getKey());
        if ($this->config['admin']) {
            $cred->sub = $this->config['admin'];
        }
        return $cred;
    }

    /**
     * Gets key for access service
     *
     * @throws AccountServiceException
     * @return string
     */
    public function getKey()
    {
        if ($this->key) {
            return $this->key;
        }
        $key = file_get_contents(
            sprintf("%s/extensions/%s", $this->app['resources']->getPath('config'), $this->config['KeyFile']));
        if (! $key) {
            throw new AccountServiceException(sprintf("Can't read KeyFile: %s", $this->config['KeyFile']));
        }
        return $this->key = $key;
    }

    /**
     * Makes a service session token string
     *
     * @param string $serviceName
     * @return string
     */
    protected function getServiceSessionTk($scope)
    {
        $serviceName = substr($scope, strrpos($scope, '/') + 1);
        return sprintf('service_token_%s_%s', $this->accountId, $serviceName);
    }
}
