<?php
namespace Bolt\Extension\Rixbeck\Gapps\Provider\Directory;

use Bolt\Extension\Rixbeck\Gapps\Service\CalendarService;
use Bolt\Extension\Rixbeck\Gapps\Provider\BaseServiceProvider;

class GroupsServiceProvider extends BaseServiceProvider
{

    public function __construct()
    {
        $this->sectionId = 'directorygroups';
        parent::__construct('Directory\\Groups');
    }
}
