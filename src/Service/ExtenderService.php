<?php
namespace Bolt\Extension\Rixbeck\Gapps\Service;

use Bolt\Extension\Bolt\BoltForms\Event\BoltFormsEvents;
use Bolt\Extension\Bolt\BoltForms\Event\BoltFormsEvent;
use utilphp\util;

class ExtenderService
{

    protected $app;

    protected $formName;

    protected $elementsFrom;

    protected $attributes;

    public function __construct($app, $formname)
    {
        $this->formName = $formname;
        $this->app = $app;
    }

    public function attachForm($elements, $attributes)
    {
        $this->elementsFrom = $elements;
        $this->attributes = $attributes;
        $this->app['dispatcher']->addListener(
            BoltFormsEvents::PRE_SET_DATA,
            array(
                $this,
                'addElementsHandler'
            ));
    }

    public function addElementsHandler(BoltFormsEvent $event)
    {
        $product = $event->getData();
        $form = $event->getForm();

        if (! $product || null === $product->getId()) {
            // $form->add('optional', 'checkbox');
            $this->addFields($form);
        }
    }

    protected function addFields($form)
    {
        foreach ($this->elementsFrom as $element) {
            $name = $this->makeFieldname($element[$this->attributes['label']]);
            $form->add($name, $this->attributes['type']);
        }
    }

    protected  function makeFieldname($name)
    {
        return preg_replace('/[^[:alnum:]]+/im', '_', $name);
    }
}