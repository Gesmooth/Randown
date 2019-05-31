<?php declare(strict_types = 1);

namespace Sbludufunk\Randown\Evaluator\Classes\PrivateConstructors;

use Sbludufunk\Randown\Evaluator\Classes\Objecto;
use function random_int;

class IntClass extends Objecto
{
    private $_min;

    private $_max;

    public function __construct(Objecto $min, Objecto $max){
        $this->_min = $min;
        $this->_max = $max;
    }

    public function staticize(): Objecto{
        $min = (Int)(String)$this->_min;
        $max = (Int)(String)$this->_max;
        $static = (String)random_int($min, $max);
        return new IntClass(new TextClass($static), new TextClass($static));
    }

    public function __toString(): String{
        return (String)$this->staticize();
    }
}
