<?php declare(strict_types = 1);

namespace Sbludufunk\Randown\Parser\Nodes;

interface Node
{
    public function __toString(): String;
}
