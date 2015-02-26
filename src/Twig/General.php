<?php
namespace Bolt\Extension\Rixbeck\Gapps\Twig;

use Bolt\Application;
use Bolt\Extension\Rixbeck\Gapps\Provider\CalendarServiceProvider;
use Bolt\Extension\Rixbeck\Gapps\Extension;
use Bolt\Extension\Rixbeck\Gapps\RomanNumbers;
use Symfony\Component\Finder\Finder;

class General extends BaseExtension
{

    protected function frontendFilters()
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
            new \Twig_SimpleFilter('wtrim',
                array(
                    $this,
                    'trim'
                ))
        );
    }

    /*
     * (non-PHPdoc)
     * @see \Bolt\Extension\Rixbeck\Gapps\Twig\BaseExtension::frontendFunctions()
     */
    protected function frontendFunctions()
    {
        return array(
            new \Twig_SimpleFunction('randomfile',
                array(
                    $this,
                    'randomFile'
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

    public function randomFile($from = '')
    {
        $infolder = $this->app['resources']->getPath('files') . '/' . $from;
        $finder = new Finder();
        $finder->files()->in($infolder);
        $files = iterator_to_array($finder);

        $idx = rand(0, count($files)-1);
        $file = array_values($files)[$idx];
        $filename = $from . '/' . $file->getFilename();

        return $filename;
    }

    public function getName()
    {
        return 'gapps.general';
    }
}
