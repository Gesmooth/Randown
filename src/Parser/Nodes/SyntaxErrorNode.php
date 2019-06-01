<?php declare(strict_types = 1);

namespace Sbludufunk\Randown\Parser\Nodes;

use Sbludufunk\Randown\Tokenizer\Tokens\Token;

class SyntaxErrorNode implements RootNode
{
    private $_token;

    public function __construct(Token $token){
        $this->_token = $token;
    }

    public function token(): Token{
        return $this->_token;
    }

    public function __toString(): String{
        return (String)$this->_token;
    }
}
