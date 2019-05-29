<?php declare(strict_types = 1);

namespace Sbludufunk\Randown\Evaluator;

interface FunctionInterface
{
    public function invoke(Concatenation ...$arguments): Objecto;
}
