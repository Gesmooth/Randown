<?php declare(strict_types = 1);

namespace Sbludufunk\Randown\Nodes;

interface ObjectNode extends Node
{
    public function methodCalls(): array;
}
