<?php declare(strict_types = 1);

namespace Sbludufunk\Randown\Evaluator\Intraclasses;

use Error;

class Concatenation implements Objecto
{
    private $_values;

    public function __construct(Array $values){
        $this->_values = $values;
    }

    public function __toString(): String{
        return implode("", $this->_values);
    }

    public function invoke(String $name, array $arguments): Objecto{
        throw new Error("Syntax not supported");
    }
}
