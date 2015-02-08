<?php
namespace Bolt\Extension\Rixbeck\Gapps\Twig;

use Bolt\Application;
use Bolt\Extension\Rixbeck\Gapps\Provider\CalendarServiceProvider;
use Bolt\Extension\Rixbeck\Gapps\Extension;

class Calendar extends \Twig_Extension
{

    protected $app;

    // put default functions here which must work on both backend and frontend
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function getFunctions()
    {
        return array(
            'gappscalendar' => new \Twig_Function_Method($this, 'getService')
        );
    }

    public function getService($calendarName)
    {
        return $this->app[Extension::getProviderId('calendar')][$calendarName];
    }

    public function getName()
    {
        return 'gapps.calendar';
    }
}
