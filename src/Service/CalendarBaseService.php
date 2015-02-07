<?php
namespace Bolt\Extension\Rixbeck\Gapps\Service;

use Bolt\Application;
use Bolt\Extension\Rixbeck\Gapps\Extension;

class CalendarBaseService
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
