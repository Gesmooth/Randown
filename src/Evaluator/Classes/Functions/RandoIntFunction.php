<?php declare(strict_types = 1);

namespace Sbludufunk\Randown\Evaluator\Classes\Functions;

use Sbludufunk\Randown\Evaluator\Classes\Objecto;
use Sbludufunk\Randown\Evaluator\Classes\PrivateConstructors\IntClass;

class RandoIntFunction extends FunctionBase
{
    public function invokeSelf(Objecto ...$arguments): Objecto{
        return new IntClass($arguments[0], $arguments[1]);
    }
}
