<?php declare(strict_types = 1);

namespace Sbludufunk\Randown\Nodes;

class RandoCallNode implements ObjectNode
{
    private $_arguments;

    private $_methodCalls;

    public function __construct(ArgumentsNode $arguments, Array $methodCalls){
        $this->_arguments = $arguments;
        $this->_methodCalls = $methodCalls;
    }

    public function __toString(): String{
        return "{" . $this->_arguments . implode("", $this->_methodCalls);
    }

    public function arguments(): ArgumentsNode{
        return $this->_arguments;
    }

    public function methodCalls(): array{
        return $this->_methodCalls;
    }
}
