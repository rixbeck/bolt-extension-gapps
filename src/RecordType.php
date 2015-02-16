<?php
namespace Bolt\Extension\Rixbeck\Gapps;

class RecordType extends \ArrayObject
{

    /*
     * (non-PHPdoc)
     * @see ArrayObject::__construct()
     */
    public function __construct($initial)
    {
        if (! is_array($initial)) {
            $initial = static::decode($initial);
        }

        parent::__construct($initial);
    }

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

    public static function decode($string)
    {
        $matches = array();
        $fields = array();
        $found = preg_match('/([\w]*)\((.*)\)/', $string, $matches);
        if ($found) {
            $pname = $matches[1];
            $string = preg_replace('/(' . $pname . '\(.*\))/', '', $string);
            if (substr($string, - 1, 1) == ',') {
                $string = substr($string, 0, - 1);
            }
            $items = explode(',', $matches[2]);
            $fields[$pname] = $items;
        }
        $fields = array_merge($fields, explode(',', $string));

        return $fields;
    }
}
