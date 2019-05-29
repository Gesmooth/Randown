<?php declare(strict_types = 1);

namespace Sbludufunk\Randown\Evaluator;

interface Objecto
{
    public function __toString(): String;

    public function invoke(String $name, Array $arguments): Objecto;
}
