<?php
namespace Bolt\Extension\Rixbeck\Gapps\Provider;

use Bolt\Extension\Rixbeck\Gapps\Provider\BaseServiceProvider;

class CalendarServiceProvider extends BaseServiceProvider
{

    public function __construct($frontend)
    {
        $this->sectionId = 'calendar';
        parent::__construct(($frontend) ? 'CalendarBase' : 'Calendar');
    }
}
