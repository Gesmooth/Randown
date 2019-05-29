<?php declare(strict_types = 1);

namespace Sbludufunk\Randown\Evaluator;

use Exception;
use Sbludufunk\Randown\Nodes\FunctionCallNode;

class UndefinedFunction extends Exception
{
    public function __construct(Array $nodes, FunctionCallNode $node){
        parent::__construct("BLA");
    }
}
