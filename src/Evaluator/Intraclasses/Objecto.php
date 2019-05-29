<?php declare(strict_types = 1);

namespace Sbludufunk\Randown\Evaluator\Intraclasses;

interface Objecto
{
    public function __toString(): String;

    public function invoke(String $name, Array $arguments): Objecto;
}
