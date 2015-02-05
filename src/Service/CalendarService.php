<?php
namespace Bolt\Extension\Rixbeck\Gapps\Service;

use Bolt\Application;

class CalendarService
{
    protected $app;

    protected $config;

    protected $calname;

    public function __construct(Application $app, $calname)
    {
        $this->app = $app;
        $this->config = $this->app[Extension::CONTAINER_ID]->getConfig();
        $this->calname = $calname;
    }

    public function test()
    {
        return 'Test is OK';
    }
}