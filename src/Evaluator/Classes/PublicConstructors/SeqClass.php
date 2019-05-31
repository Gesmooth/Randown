<?php declare(strict_types = 1);

namespace Sbludufunk\Randown\Evaluator\Classes\PublicConstructors;

use Error;
use Sbludufunk\Randown\Evaluator\Classes\Objecto;
use Sbludufunk\Randown\Evaluator\Classes\PrivateConstructors\SeqShuffleClass;
use Sbludufunk\Randown\Evaluator\Classes\PrivateConstructors\SeqSubClass;

class SeqClass extends Objecto
{
    private $_storage;

    public function __construct(Objecto ...$objects){
        $this->_storage = $objects;
    }

    public function staticize(): Objecto{
        $storage = [];
        foreach($this->_storage as $element){
            $storage[] = $element->staticize();
        }
        return new SeqClass(...$storage);
    }

    public function invoke(String $name, Objecto ...$arguments): Objecto{
        if($name === "shuffle"){
            return new SeqShuffleClass(...$this->_storage);
        }

        if($name === "sub"){
            return new SeqSubClass($arguments[0], $arguments[1], ...$this->_storage);
        }

        return parent::invoke($name, ...$arguments);
    }

    public function __toString(): String{
        return implode(", ", $this->_storage);
    }
}
