<?php declare(strict_types = 1);

namespace Sbludufunk\Randown\DS;

use Sbludufunk\Randown\Evaluator\Value;

class OptionBag implements Value
{
    private $_bag;

    public function __construct(Bag $bag){
        $this->_bag = $bag;
    }

    public function invoke(String $name, array $arguments): Value{
        throw new \Error;
    }

    public function __toString(): String{
        return "lol";
    }
}
