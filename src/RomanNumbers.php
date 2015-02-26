<?php
namespace Bolt\Extension\Rixbeck\Gapps;

use Sirius\Validation\Rule\Length;

class RomanNumbers
{

    protected $digits = array(
        array(
            '',
            'i',
            'ii',
            'iii',
            'iv',
            'v',
            'vi',
            'vii',
            'viii',
            'ix'
        ),
        array(
            '',
            'x',
            'xx',
            'xxx',
            'xl',
            'l',
            'lx',
            'lxx',
            'lxxx',
            'xc'
        ),
        array(
            '',
            'c',
            'cc',
            'ccc',
            'cd',
            'd',
            'dc',
            'dcc',
            'dccc',
            'dm'
        ),
        array(
            '',
            'm',
            'mm',
            'mmm',
            'mmmm',
            'V',
            'VI',
            'VII',
            'VIII',
            'X'
        )
    );

    protected $value;

    public function __construct($number)
    {
        $this->value = $number;
    }

    public function convert()
    {
        if ($this->value > 10000) {
            return false;
        }

        $str = strrev((string) $this->value);
        $value = '';
        for ($i = 0; $i < strlen($str); $i ++) {
            $value = $this->digits[$i][$str[$i]] . $value;
        }

        return $value;
    }

    public function __toString()
    {
        return $this->convert();
    }
}