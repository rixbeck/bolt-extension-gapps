<?php
namespace Bolt\Extension\Rixbeck\Gapps\Service;

use Bolt\Extension\Rixbeck\Gapps\Extension;
use Bolt\Extension\Rixbeck\Gapps\RecordType;
use Bolt\Application;

class GroupsService extends BaseService
{

    public function __construct(Application $app, $name)
    {
        parent::__construct($app, $name, 'directory');
    }

    public function initialize()
    {
        $this->recordType = array();

        $this->defaultOptions = array();

        return parent::initialize();
    }

    /*
     * (non-PHPdoc)
     * @see \Bolt\Extension\Rixbeck\Gapps\Service\BaseService::createService()
     */
    protected function createService($client)
    {
        return $this->service = new \Google_Service_Directory($client);
    }
}