<?php

namespace Bolt\Extension\RixBeck\Gapps\Service\Directory;

use Bolt\Extension\RixBeck\Gapps\Iterator\PagingGroupsIterator;
use Bolt\Extension\RixBeck\Gapps\Service\BaseService;

/**
 * Class GroupsService
 *
 * @author Rix Beck <rix@neologik.hu>
 *
 * @property \Google_Service_Directory $service
 */
class GroupsService extends BaseService
{
    public $groups;

    public function initialize()
    {
        parent::initialize();

        $this->recordType = array();
        $this->defaultOptions = array();
        $this->account->getClient()->addScope(\Google_Service_Directory::ADMIN_DIRECTORY_GROUP);

        return $this->service;
    }

    /*
     * (non-PHPdoc)
     * @see \Bolt\Extension\RixBeck\Gapps\Service\BaseService::createService()
     */

    public function groupList($options = array())
    {
        $options['domain'] = $this->config['domain'];
        $options = $this->prepareOptions(strtolower(__FUNCTION__), $options);

        return $this->groups = new PagingGroupsIterator($this->service->groups, $options);
    }

    protected function createService($client)
    {
        return $this->service = new \Google_Service_Directory($client);
    }

    /*
     * (non-PHPdoc)
     * @see \Bolt\Extension\RixBeck\Gapps\Service\BaseService::createServiceName()
     */

    protected function createScopes()
    {
        return array(
            'admin.directory.group'
        );
    }
}