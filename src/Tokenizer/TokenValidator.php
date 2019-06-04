<?php

namespace Sbludufunk\Randown\Tokenizer;

use Sbludufunk\Randown\Tokenizer\Tokens\Token;
use function implode;

class TokenValidator
{
    private $_tokenizer;

    public function __construct(Tokenizer $tokenizer){
        $this->_tokenizer = $tokenizer;
    }

    public function isTokenValid(Token $token): Bool{
        $stringifiedToken = (String)$token;
        $compareTokens = $this->_tokenizer->tokenize($stringifiedToken);
        return [$token] == $compareTokens;
    }

    public function isTokenSequenceValid(Array $tokens): Bool{
        $stringifiedTokens = implode("", $tokens);
        $compareTokens = $this->_tokenizer->tokenize($stringifiedTokens);
        return $tokens == $compareTokens;
    }

    public function findFirstInvalidToken(Array $tokens): ?Token{
        foreach($tokens as $token){
            if(!$this->isTokenValid($token)){
                return $token;
            }
        }
        return NULL;
    }
}
