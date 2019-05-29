<?php declare(strict_types = 1);

namespace Sbludufunk\Randown\Evaluator;

use Sbludufunk\Randown\Evaluator\Intraclasses\Objecto;

interface FunctionInterface
{
    public function invoke(Objecto ...$arguments): Objecto;
}
