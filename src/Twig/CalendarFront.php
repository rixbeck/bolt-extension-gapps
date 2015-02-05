<?php
namespace Bolt\Extension\Rixbeck\Gapps\Twig;

use Bolt\Application;
use Bolt\Extension\Rixbeck\Gapps\Service\CalendarService;

class CalendarFront
{

    protected $service;

    public function __construct(Application $app, $calname)
    {
        $this->service = new CalendarService($app, $calname);
    }

    public function test()
    {
        return $this->service->test();
    }
}