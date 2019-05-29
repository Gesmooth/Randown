<?php declare(strict_types = 1);

namespace Sbludufunk\Randown\Evaluator;

use Sbludufunk\Randown\Evaluator\Bag;
use Sbludufunk\Randown\Evaluator\Objecto;

class OptionBag implements Objecto
{
    private $_bag;

    public function __construct(Bag $bag){
        $this->_bag = $bag;
    }

    public function invoke(String $name, array $arguments): Objecto{
        throw new \Error;
    }

    public function __toString(): String{
        return "lol";
    }
}
