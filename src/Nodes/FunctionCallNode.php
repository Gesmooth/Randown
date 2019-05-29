<?php declare(strict_types = 1);

namespace Sbludufunk\Randown\Nodes;

use Sbludufunk\Randown\Tokens\FunctionCallToken;

class FunctionCallNode implements ObjectNode
{
    private $_token;

    private $_arguments;

    private $_methodCalls;

    public function __construct(
        FunctionCallToken $token,
        ArgumentsNode $arguments,
        Array $methodCalls
    ){
        $this->_token = $token;
        $this->_arguments = $arguments;
        $this->_methodCalls = $methodCalls;
    }

    public function __toString(): String{
        return $this->_token . $this->_arguments . implode("", $this->_methodCalls);
    }

    public function token(): FunctionCallToken{
        return $this->_token;
    }

    public function arguments(): ArgumentsNode{
        return $this->_arguments;
    }

    public function methodCalls(): array{
        return $this->_methodCalls;
    }
}
