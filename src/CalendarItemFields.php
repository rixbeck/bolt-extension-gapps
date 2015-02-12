<?php
namespace Bolt\Extension\Rixbeck\Gapps;

class CalendarItemFields extends \ArrayObject
{

    public function __toString()
    {
        return $this->encode($this);
    }

    public function encode($what)
    {
        $encoded = '';
        foreach ($what as $key => $element) {
            $encoded .= (is_array($element)) ? sprintf('%s(%s)', $key, $this->encode($element)) : $element;
            $encoded .= ',';
        }
        $encoded = substr($encoded, 0, - 1);

        return $encoded;
    }
}
