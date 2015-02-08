<?php
namespace Bolt\Extension\Rixbeck\Gapps\Provider;

use Bolt\Extension\Rixbeck\Gapps\Service\CalendarService;

class AccountsServiceProvider extends BaseServiceProvider
{

    public function __construct()
    {
        parent::__construct($this->sectionId = 'accounts');
    }
}
