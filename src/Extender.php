<?php
/**
 * @author Rix Beck <rix@neologik.hu>
 */

namespace Bolt\Extension\Rixbeck\Gapps;

use Bolt\Extension\Bolt\BoltForms\Event\BoltFormsEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class Extender
{
    protected $formName;
    protected $elementsFrom;
    protected $attributes;
    protected $options;
    protected $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher, $formname = '')
    {
        $this->formName = $formname;
        $this->dispatcher = $dispatcher;
    }

    public function attachForm($elements, $attributes)
    {
        $this->elementsFrom = $elements;
        $this->prepareOptions($attributes);
        $this->dispatcher->addListener(
            BoltFormsEvents::POST_SET_DATA,
            array(
                $this,
                'addElementsHandler'
            ));
    }

    public function prepareOptions($attributes)
    {
        $this->options = $attributes['options'];
        unset($attributes['options']);
        $this->attributes = $attributes;
    }

    public function addElementsHandler(BoltFormsEvent $event)
    {
        $product = $event->getData();
        $form = $event->getForm();

        if (!$product || null === $product->getId()) {
            /* @var $group \Symfony\Component\Form\FormBuilder */
            // $group = $this->app['form.factory']->createNamedBuilder('Gyerekek', 'form', array('virtual'=>true,'auto_initialize'=>false));
            // $form = $group->getForm();
            $sub = $this->addFields($form);
            // $form->add($sub);
        }
    }

    public function encodeFieldname($name)
    {
        $name = 'xtend_'.bin2hex($name);
        return $name;
    }

    public function decodeFieldname($name)
    {
        $name = hex2bin(substr($name, 6));
        return $name;
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
