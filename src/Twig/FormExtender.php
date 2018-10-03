<?php

namespace Bolt\Extension\RixBeck\Gapps\Twig;

use Bolt\Extension\RixBeck\Gapps\Iterator\BasePagingIterator;

class FormExtender
{
    protected $instances = [];
    private $extenders;

    public function __construct(\Pimple $extenders, $config)
    {
        $this->extenders = $extenders;
    }

    public function filters()
    {
        return [
            new \Twig_SimpleFilter('attachform',
                array(
                    $this,
                    'attachForm'
                ), array(
                    'needs_environment' => true
                ))
        ];
    }

    public function functions()
    {
        return [
            new \Twig_SimpleFunction('decodefieldname', array($this, 'decodefieldname'))
        ];
    }

    public function decodeFieldname($field)
    {
        $extender = $this->extenders;

        return $extender->decodeFieldname($field);
    }

    public function attachForm(\Twig_Environment $env, $elements, $formname, $options)
    {
        if ($elements instanceof BasePagingIterator) {
            $service = $this->extenders[$formname];
            $service->attachForm($elements, $options);
        }
    }
}