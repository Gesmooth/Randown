<?php declare(strict_types = 1);

namespace Sbludufunk\Randown\Evaluator\Classes\PrivateConstructors;

use Sbludufunk\Randown\Evaluator\Classes\Objecto;
use Sbludufunk\Randown\Evaluator\Classes\PublicConstructors\BagClass;

class RandoClass extends Objecto
{
    private $_bag;

    public function __construct(BagClass $bag){
        $this->_bag = $bag;
    }

    public function staticize(): Objecto{
        // TODO: Implement staticize() method.
    }

    public function __toString(): String{
        $index = random_int(0, $this->_bag->count() - 1);
        return $this->_bag->toArray()[$index]->__toString();
    }
}
