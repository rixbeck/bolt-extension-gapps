<?php
namespace Bolt\Extension\Rixbeck\Gapps\Twig;

use Bolt\Application;
use Bolt\Extension\Rixbeck\Gapps\Provider\CalendarServiceProvider;
use Bolt\Extension\Rixbeck\Gapps\Extension;

class Calendar
{

    protected $app;

    protected $functions = array();

    protected $calendar;

    // put default functions here which must work on both backend and frontend
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function initialize($frontend)
    {
        $this->app->register(new CalendarServiceProvider($frontend));
        $this->functions = array_merge(
            array(
                'gapps_calendar' => new \Twig_Function_Method($this, 'getService')
            ));
    }

    public function getFunctions()
    {
        return $this->functions;
    }

    public function getService($calendarName)
    {
        return $this->app[CalendarServiceProvider::getProviderId()][$calendarName];
    }
}