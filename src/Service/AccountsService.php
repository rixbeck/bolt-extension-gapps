<?php
namespace Bolt\Extension\Rixbeck\Gapps\Service;

use Bolt\Application;
use Bolt\Extension\Rixbeck\Gapps\Extension;
use Bolt\Extension\Rixbeck\Gapps\Exception\AccountServiceException;

class AccountsService
{

    protected $app;

    protected $config;

    protected $accountId;

    protected $client;

    protected $key;

    protected $serviceId;

    /**
     * Instance of an gapps account for using api
     *
     * @param Application $app
     * @param string $accountId
     */
    public function __construct(Application $app, $accountId)
    {
        $this->app = $app;
        $config = $this->app[Extension::CONTAINER_ID]->getConfig()['accounts'];
        $this->config = $config[$accountId];
        $this->accountId = $accountId;
        $this->serviceId = $this->config['ServiceID'];
    }

    /**
     * Authorize API with credentials
     *
     * @param \Google_Auth_AssertionCredentials $cred
     */
    public function authenticate(\Google_Auth_AssertionCredentials $cred)
    {
        $this->client = $client = new \Google_Client();
        $sitename = $this->app['config']->get('general/sitename');
        // @todo url::slugify() will be deprecated in 2.1
        $this->client->setApplicationName(\utilphp\util::slugify($sitename));

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
     */
    public function createCredentialsFor($serviceName)
    {
        $cred = new \Google_Auth_AssertionCredentials($this->config['ServiceID'],
            array(
                sprintf('https://www.googleapis.com/auth/%s', $serviceName)
            ), $this->getKey());
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
