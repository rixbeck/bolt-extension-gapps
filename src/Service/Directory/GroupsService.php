<?php
namespace Bolt\Extension\Rixbeck\Gapps\Service\Directory;

use Bolt\Extension\Rixbeck\Gapps\Extension;
use Bolt\Extension\Rixbeck\Gapps\RecordType;
use Bolt\Application;
use Bolt\Extension\Rixbeck\Gapps\Iterator\PagingGroupsIterator;
use Bolt\Extension\Rixbeck\Gapps\Service\BaseService;

class GroupsService extends BaseService
{

    public $groups;

    public function __construct(Application $app, $name)
    {
        parent::__construct($app, $name, 'directory.groups');
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

    public function groupList($options = array())
    {
        $options['domain'] = $this->config['domain'];
        $options = $this->prepareOptions($options);

        return $this->groups = new PagingGroupsIterator($this->service->groups, $options);
    }

    /*
     * (non-PHPdoc)
     * @see \Bolt\Extension\Rixbeck\Gapps\Service\BaseService::createServiceName()
     */
    protected function createServiceName($section)
    {
        return array(
            'admin.directory.group'
        );
    }
}