<?php declare(strict_types = 1);

namespace Sbludufunk\Randown\Evaluator\Classes\Functions;

use Sbludufunk\Randown\Evaluator\Classes\Objecto;

abstract class FunctionBase extends Objecto
{
    public function staticize(): Objecto{
        return $this;
    }

    public function __toString(): String{
        return "";
    }
}
