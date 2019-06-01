<?php declare(strict_types = 1);

namespace Sbludufunk\Randown\Evaluator;

use Exception;
use Sbludufunk\Randown\Parser\Nodes\ReferenceNode;

class UndefinedVariable extends Exception
{
    public function __construct(Array $nodes, ReferenceNode $node){
        parent::__construct($node->token()->name());
    }
}
