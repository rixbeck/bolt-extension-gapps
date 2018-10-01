<?php

namespace Bolt\Extension\Rixbeck\Gapps\Twig;

use Bolt\Extension\Rixbeck\Gapps\RomanNumbers;
use Symfony\Component\Finder\Finder;

class General
{
    /**
     * @return array
     */
    public function filters()
    {
        return [
            new \Twig_SimpleFilter('localedate',
                [
                    $this,
                    'dateFormatFilter'
                ], [
                    'needs_environment' => true
                ]),
            new \Twig_SimpleFilter('roman',
                [
                    $this,
                    'romanNumberFilter'
                ], [
                    'needs_environment' => true
                ]),
            new \Twig_SimpleFilter('wtrim',
                [
                    $this,
                    'trim'
                ])
        ];
    }

    /**
     * @return array
     */
    public function functions()
    {
        return [
            new \Twig_SimpleFunction('randomfile',
                [
                    $this,
                    'randomFile'
                ])
        ];
    }

    /*
     * (non-PHPdoc)
     * @see \Bolt\Extension\Rixbeck\Gapps\Twig\BaseExtension::frontendFunctions()
     */

    public function trim($string, $width, $marker = '…')
    {
        return mb_strimwidth($string, 0, $width, $marker);
    }

    /**
     * @param \Twig_Environment $env
     * @param $number
     * @return bool|string
     */
    public function romanNumberFilter(\Twig_Environment $env, $number)
    {
        $rc = new RomanNumbers($number);

        return $rc();
    }

    /**
     * @param \Twig_Environment $env
     * @param $date
     * @param string $informat
     * @param string $format
     * @param null $timezone
     * @return string
     */
    public function dateFormatFilter(\Twig_Environment $env, $date, $informat = 'Y-m-d H:i:s', $format = '%Y.%m.%d', $timezone = null)
    {
        // @todo pick up this settings to config
        setlocale(LC_TIME, 'hu_HU.UTF-8');
        if ($date instanceof \DateInterval) {
            $date = $date->format($informat);
        } else {
            $date = \DateTime::createFromFormat($informat, $date);
        }

        return strftime($format, $date->getTimestamp());
    }

    /**
     * @param string $from
     * @return string
     */
    public function randomFile($from = '')
    {
        $infolder = 'files://'.$from;
        $finder = new Finder();
        $finder->files()->in($infolder);
        $files = iterator_to_array($finder);

        $idx = rand(0, count($files) - 1);
        $file = array_values($files)[$idx];
        $filename = $from.'/'.$file->getFilename();

        return $filename;
    }
}
