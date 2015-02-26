<?php
namespace Bolt\Extension\Rixbeck\Gapps\Service;

use Bolt\Extension\Bolt\BoltForms\Event\BoltFormsEvents;
use Bolt\Extension\Bolt\BoltForms\Event\BoltFormsEvent;
use utilphp\util;
use Symfony\Component\Form\Form;
use Bolt\Extension\Rixbeck\Gapps\Extension;

class ExtenderService
{

    protected $app;

    protected $formName;

    protected $elementsFrom;

    protected $attributes;

    protected $options;

    public function __construct($app, $formname = '')
    {
        $this->formName = $formname;
        $this->app = $app;
    }

    public function attachForm($elements, $attributes)
    {
        $this->elementsFrom = $elements;
        $this->prepareOptions($attributes);
        $this->app['dispatcher']->addListener(
            BoltFormsEvents::POST_SET_DATA,
            array(
                $this,
                'addElementsHandler'
            ));
    }

    public function prepareOptions($attributes)
    {
        if (! is_array($attributes)) {
            $section = $attributes;
            $attributes = $this->app[Extension::CONTAINER_ID]->getConfig('extender/' . $section);
        }
        $this->options = $attributes['options'];
        unset($attributes['options']);
        $this->attributes = $attributes;
    }

    public function addElementsHandler(BoltFormsEvent $event)
    {
        $product = $event->getData();
        $form = $event->getForm();

        if (! $product || null === $product->getId()) {
            /* @var $group \Symfony\Component\Form\FormBuilder */
            // $group = $this->app['form.factory']->createNamedBuilder('Gyerekek', 'form', array('virtual'=>true,'auto_initialize'=>false));
            // $form = $group->getForm();
            $sub = $this->addFields($form);
            // $form->add($sub);
        }
    }

    protected function addFields(Form $form)
    {
        foreach ($this->elementsFrom as $element) {
            $options = $this->createOptionsFor($element);
            $name = $this->encodeFieldname($options['label']);
            $form->add($name, $this->attributes['type'], $options);
        }

        return $form;
    }

    public function encodeFieldname($name)
    {
        $name = 'xtend_' . bin2hex($name);
        return $name;
    }

    public function decodeFieldname($name)
    {
        $name = hex2bin(substr($name, 6));
        return $name;
    }

    protected function getElementAttribute($element, $attributeName)
    {
        $value = $this->options[$attributeName];
        if ($value[0] == '@') {
            return $element[substr($value, 1)];
        }

        return $value;
    }

    protected function createOptionsFor($element)
    {
        $options = array();
        foreach ($this->options as $key => $option) {
            $options[$key] = $this->getElementAttribute($element, $key);
        }

        return $options;
    }
}