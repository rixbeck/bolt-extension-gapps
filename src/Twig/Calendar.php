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
            'calendarevents' => new \Twig_Function_Method($this, 'getService')
        );
    }

    /*
     * (non-PHPdoc)
     * @see Twig_Extension::getFilters()
     */
    public function getFilters()
    {
        // TODO: Auto-generated method stub
        return array(
            new \Twig_SimpleFilter('localedate', array($this, 'date_format_filter'), array(
                'needs_environment' => true
            ))
        );
    }

    public function date_format_filter(\Twig_Environment $env, $date, $informat = 'Y-m-d H:i:s', $format = '%Y.%m.%d', $timezone = null)
    {
        // @todo pick up this settings to config
        setlocale(LC_TIME, 'hu_HU.UTF-8');
        if ($date instanceof DateInterval) {
            $date = $date->format($informat);
        }
        else {
            $date = \DateTime::createFromFormat($informat, $date);
        }

        return strftime($format, $date->getTimestamp());
    }

    public function getService($calendarName)
    {
        $service = $this->app[Extension::getProviderId('calendar')][$calendarName];
        $service->initialize();
        return $service;
    }

    public function getName()
    {
        return 'gapps.calendar';
    }
}
