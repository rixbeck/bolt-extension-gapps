<?php
namespace Bolt\Extension\Rixbeck\Gapps\Provider;

use Bolt\Extension\Rixbeck\Gapps\Provider\BaseServiceProvider;
use Bolt\Application;

class CalendarServiceProvider extends BaseServiceProvider
{

    public function __construct(Application $app)
    {
        $frontend = ($app['config']->getWhichEnd() === 'frontend');
        parent::__construct(($frontend) ? 'CalendarBase' : 'Calendar');
        $this->sectionId = 'calendar';
    }
}
