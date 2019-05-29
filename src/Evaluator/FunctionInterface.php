<?php declare(strict_types = 1);

namespace Sbludufunk\Randown\Evaluator;

interface FunctionInterface
{
    public function __invoke(Concatenation ...$arguments): Value;
}
