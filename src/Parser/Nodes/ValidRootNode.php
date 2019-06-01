<?php declare(strict_types = 1);

namespace Sbludufunk\Randown\Parser\Nodes;

interface ValidRootNode extends RootNode
{
    public function calls(): array;
}
