<?php declare(strict_types = 1);

namespace Sbludufunk\Randown\Evaluator\Intraclasses;

use Error;

class Rando implements Objecto
{
    private $_bag;

    public function __construct(Bag $bag){
        $this->_bag = $bag;
    }

    public function __toString(): String{
        $index = random_int(0, $this->_bag->count() - 1);
        return $this->_bag->toArray()[$index]->__toString();
    }

    public function invoke(String $name, array $arguments): Objecto{
        throw new Error("Method $name does not exist");
    }
}
