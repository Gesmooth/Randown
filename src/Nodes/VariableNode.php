<?php declare(strict_types = 1);

namespace Sbludufunk\Randown\Nodes;

use Sbludufunk\Randown\Tokens\VariableToken;

class VariableNode implements ObjectNode
{
    private $_token;

    private $_methodCalls;

    public function __construct(
        VariableToken $token,
        Array $methodCalls
    ){
        $this->_token = $token;
        $this->_methodCalls = $methodCalls;
    }

    public function __toString(): String{
        return $this->_token . implode("", $this->_methodCalls);
    }

    public function token(): VariableToken{
        return $this->_token;
    }

    public function methodCalls(): array{
        return $this->_methodCalls;
    }
}
