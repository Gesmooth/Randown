<?php

namespace Sbludufunk\Randown\Tokenizer;

use Sbludufunk\Randown\Tokenizer\Tokens\Token;

class TokenizerFunctions
{
    public function isTokenValid(Tokenizer $tokenizer, Token $token): Bool{
        $stringifiedToken = (String)$token;
        $compareTokens = $tokenizer->tokenize($stringifiedToken);
        return [$token] == $compareTokens;
    }

    public function findFirstInvalidToken(Array $tokens): ?Token{
        foreach($tokens as $token){
            if(!$this->isTokenValid($token)){
                return $token;
            }
        }
        return NULL;
    }

    public function findTokenLine(Array $tokens, Token $searchToken): ?Int{
        $sumLines = 1;
        foreach($tokens as $token){
            if($token === $searchToken){
                return $sumLines;
            }
            $sumLines += $token->newlines();
        }
        return NULL;
    }
}
