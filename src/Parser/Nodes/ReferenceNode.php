<?php declare(strict_types = 1);

namespace Sbludufunk\Randown\Parser\Nodes;

use Sbludufunk\Randown\Tokenizer\Tokens\ReferenceToken;

class ReferenceNode implements ValidRootNode
{
    private $_token;

    private $_calls;

    public function __construct(
        ReferenceToken $token,
        Array $calls
    ){
        $this->_token = $token;
        $this->_calls = $calls;
    }

    public function token(): ReferenceToken{
        return $this->_token;
    }

    public function calls(): array{
        return $this->_calls;
    }

    public function __toString(): String{
        return $this->_token . implode("", $this->_calls);
    }
}
