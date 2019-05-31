<?php declare(strict_types = 1);

namespace Sbludufunk\Randown\Evaluator\Classes\PrivateConstructors;

use Sbludufunk\Randown\Evaluator\Classes\Objecto;
use Sbludufunk\Randown\Evaluator\Classes\PublicConstructors\SeqClass;

class SeqShuffleClass extends Objecto
{
    private $_storage;

    public function __construct(Objecto ...$objects){
        $this->_storage = [];
        foreach($objects as $object){
            $this->_storage[] = $object;
        }
    }

    public function __toString(): String{
        return $this->finalize()->__toString();
    }

    private function finalize(){
        $shuffled = $this->_storage;
        shuffle($shuffled);
        return new SeqClass(...$shuffled);
    }

    public function invoke(String $name, Objecto ...$arguments): Objecto{
        return $this->finalize()->invoke($name, $arguments);
    }
}
