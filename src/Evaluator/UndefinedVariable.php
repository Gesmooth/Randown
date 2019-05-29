<?php declare(strict_types = 1);

namespace Sbludufunk\Randown\Evaluator;

use Exception;
use Sbludufunk\Randown\Nodes\VariableNode;

class UndefinedVariable extends Exception
{
    public function __construct(Array $nodes, VariableNode $node){
        parent::__construct("BLA");
    }
}
