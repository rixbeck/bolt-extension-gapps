<?php
namespace Bolt\Extension\RixBeck\Gapps\Provider\Directory;

use Bolt\Extension\RixBeck\Gapps\Service\CalendarService;
use Bolt\Extension\RixBeck\Gapps\Provider\BaseServiceProvider;

class GroupsServiceProvider extends BaseServiceProvider
{

    public function __construct()
    {
        parent::__construct('Directory\\Groups');
    }
}
