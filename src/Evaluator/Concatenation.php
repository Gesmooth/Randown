<?php declare(strict_types = 1);

namespace Sbludufunk\Randown\Evaluator;

class Concatenation
{
    private $_values;

    public function __construct(Array $values){
        $this->_values = $values;
    }

    public function __toString(): String{
        return implode("", $this->_values);
    }
}
