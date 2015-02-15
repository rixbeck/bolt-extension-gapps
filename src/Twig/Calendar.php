<?php
namespace Bolt\Extension\Rixbeck\Gapps\Twig;

use Bolt\Application;
use Bolt\Extension\Rixbeck\Gapps\Provider\CalendarServiceProvider;
use Bolt\Extension\Rixbeck\Gapps\Extension;
use Bolt\Extension\Rixbeck\Gapps\Recurrences;
use Bolt\Extension\Rixbeck\Gapps\Iterator\PagingEventsIterator;
use Bolt\Extension\Rixbeck\Gapps\EventMatrix;
use Bolt\Extension\Rixbeck\Gapps\RomanNumbers;

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
            'calendarevents' => new \Twig_Function_Method($this, 'getService'),
            'recurrences' => new \Twig_Function_Method($this, 'createRecurrence'),
            'eventmatrix' => new \Twig_Function_Method($this, 'createEventMatrix')
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
            new \Twig_SimpleFilter('localedate',
                array(
                    $this,
                    'dateFormatFilter'
                ), array(
                    'needs_environment' => true
                )),
            new \Twig_SimpleFilter('roman',
                array(
                    $this,
                    'romanNumberFilter'
                ), array(
                    'needs_environment' => true
                )),
            new \Twig_SimpleFilter('trim',
                array(
                    $this,
                    'trim'
                ))
        );
    }

    public function trim($string, $width, $marker = '…')
    {
        return mb_strimwidth($string, 0, $width, $marker);
    }

    public function romanNumberFilter(\Twig_Environment $env, $number)
    {
        $rc = new RomanNumbers($number);

        return (string) $rc;
    }

    public function dateFormatFilter(\Twig_Environment $env, $date, $informat = 'Y-m-d H:i:s', $format = '%Y.%m.%d', $timezone = null)
    {
        // @todo pick up this settings to config
        setlocale(LC_TIME, 'hu_HU.UTF-8');
        if ($date instanceof DateInterval) {
            $date = $date->format($informat);
        } else {
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

    public function createRecurrence(\Google_Service_Calendar_Event $event)
    {
        return new Recurrences($event);
    }

    public function createEventMatrix(PagingEventsIterator $events, $type = 'weekbyhours')
    {
        $matrix = new EventMatrix($events, $type);

        return $matrix->matrix;
    }

    public function getName()
    {
        return 'gapps.calendar';
    }
}
