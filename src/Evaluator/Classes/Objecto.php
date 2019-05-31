<?php declare(strict_types = 1);

namespace Sbludufunk\Randown\Evaluator\Classes;

use Exception;

abstract class Objecto
{
    abstract public function __toString(): String;

    public function invokeSelf(Objecto ...$arguments): Objecto{
        throw new Exception("Objects of class " . get_class($this) . " are not callable");
    }

    public function invoke(String $name, Objecto ...$arguments): Objecto{
        throw new Exception("Method $name in class " . get_class($this) . " does not exist");
    }

    abstract public function staticize(): Objecto;
}
