<?php

namespace Bolt\Extension\RixBeck\Gapps;

class RomanNumbers
{

    protected $digits = [
        [
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
        ],
        [
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
        ],
        [
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
        ],
        [
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
        ]
    ];

    protected $value;

    public function __construct($number = 0)
    {
        $this->value = $number;
    }

    public function roman()
    {
        if ($this->value > 10000) {
            return false;
        }

        $str = strrev((string) $this->value);
        $value = '';
        for ($i = 0; $i < strlen($str); $i++) {
            $value = $this->digits[$i][$str[$i]].$value;
        }

        return $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function __toString()
    {
        return $this->roman();
    }

    public function __invoke($number = 0)
    {
        if (is_int($number)) {
            $this->value = $number;
        }

        return $this->roman();
    }
}