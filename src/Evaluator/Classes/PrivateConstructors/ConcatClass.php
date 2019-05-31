<?php declare(strict_types = 1);

namespace Sbludufunk\Randown\Evaluator\Classes\PrivateConstructors;

use Sbludufunk\Randown\Evaluator\Classes\Objecto;

class ConcatClass extends Objecto
{
    private $_values;

    public function __construct(Objecto ...$values){
        $this->_values = $values;
    }

    public function staticize(): Objecto{
        $newValues = [];
        foreach($this->_values as $value){
            $newValues[] = $value->staticize();
        }
        return new ConcatClass(...$newValues);
    }

    public function __toString(): String{
        return implode("", $this->_values);
    }
}
