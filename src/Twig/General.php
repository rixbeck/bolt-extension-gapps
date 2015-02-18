<?php
namespace Bolt\Extension\Rixbeck\Gapps\Twig;

use Bolt\Application;
use Bolt\Extension\Rixbeck\Gapps\Provider\CalendarServiceProvider;
use Bolt\Extension\Rixbeck\Gapps\Extension;
use Bolt\Extension\Rixbeck\Gapps\RomanNumbers;

class General extends \Twig_Extension
{

    protected $app;

    // put default functions here which must work on both backend and frontend
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /*
     * (non-PHPdoc)
     * @see Twig_Extension::getFilters()
     */
    public function getFilters()
    {
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

    public function getName()
    {
        return 'gapps.general';
    }
}
