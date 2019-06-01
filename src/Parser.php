<?php declare(strict_types = 1);

namespace Sbludufunk\Randown;

use Sbludufunk\Randown\Nodes\ArgumentNode;
use Sbludufunk\Randown\Nodes\ArgumentsNode;
use Sbludufunk\Randown\Nodes\FunctionCallNode;
use Sbludufunk\Randown\Nodes\MethodCallNode;
use Sbludufunk\Randown\Nodes\RandoCallNode;
use Sbludufunk\Randown\Nodes\TextNode;
use Sbludufunk\Randown\Nodes\ReferenceNode;
use Sbludufunk\Randown\Tokens\BlockEndToken;
use Sbludufunk\Randown\Tokens\BlockStartToken;
use Sbludufunk\Randown\Tokens\EscapeToken;
use Sbludufunk\Randown\Tokens\FunctionCallToken;
use Sbludufunk\Randown\Tokens\MethodCallToken;
use Sbludufunk\Randown\Tokens\ReferenceToken;
use Sbludufunk\Randown\Tokens\BlockSeparatorToken;
use Sbludufunk\Randown\Tokens\TextToken;

class Parser
{
    public function parse(TokenStream $tokens){
        $nodes = [];
        while($tokens->hasMore()){
            $node =
                $this->consumeReference($tokens) ??
                $this->consumeTextNode($tokens) ??
                $this->consumeRandoCall($tokens) ??
                $tokens->consume(); // aka syntax error
            $nodes[] = $node;
        }
        return $nodes;
    }

    private function consumeTextNode(TokenStream $tokens): ?TextNode{
        $pieces = [];
        CONSUME_PIECE:
        $token = $tokens->peek();
        if($token instanceof TextToken || $token instanceof EscapeToken){
            $pieces[] = $tokens->consume();
            goto CONSUME_PIECE;
        }

        if($pieces === []){
            return NULL;
        }

        return new TextNode($pieces, $this->consumeCalls($tokens));
    }

    private function consumeRandoCall(TokenStream $tokens): ?RandoCallNode{
        $blockStart = $tokens->peek();
        if(!$blockStart instanceof BlockStartToken){ return NULL; }
        $tokens->consume();
        $arguments = $this->consumeArguments($tokens);
        $calls = $this->consumeCalls($tokens);
        return new RandoCallNode($arguments, $calls);
    }

    private function consumeReference(TokenStream $tokens): ?ReferenceNode{
        $referenceToken = $tokens->peek();
        if(!$referenceToken instanceof ReferenceToken){ return NULL; }
        $tokens->consume();
        $calls = $this->consumeCalls($tokens);
        return new ReferenceNode($referenceToken, $calls);
    }

    private function consumeCalls(TokenStream $tokens): array{
        $calls = [];
        CONSUME_CALL:
        $call = $this->consumeSelfCall($tokens) ?? $this->consumeMethodCall($tokens);
        if($call !== NULL){
            $calls[] = $call;
            goto CONSUME_CALL;
        }
        return $calls;
    }

    private function consumeSelfCall(TokenStream $tokens): ?FunctionCallNode{
        $functionCallToken = $tokens->peek();
        if(!$functionCallToken instanceof FunctionCallToken){ return NULL; }
        $tokens->consume();
        $arguments = $this->consumeArguments($tokens);
        return new FunctionCallNode($functionCallToken, $arguments);
    }

    private function consumeMethodCall(TokenStream $tokens): ?MethodCallNode{
        $methodCallToken = $tokens->peek();
        if(!$methodCallToken instanceof MethodCallToken){ return NULL; }
        $tokens->consume();
        $arguments = $this->consumeArguments($tokens);
        return new MethodCallNode($methodCallToken, $arguments);
    }

    private function consumeArguments(TokenStream $tokens): ArgumentsNode{
        $arguments = [];

        CONSUME_ARGUMENT:

        $pieces = [];

        CONSUME_PIECE:

        $piece =
            $this->consumeReference($tokens) ??
            $this->consumeTextNode($tokens) ??
            $this->consumeRandoCall($tokens);

        if($piece !== NULL){
            $pieces[] = $piece;
            goto CONSUME_PIECE;
        }

        $token = $tokens->consume();
        assert(
            $token === NULL ||
            $token instanceof BlockEndToken ||
            $token instanceof BlockSeparatorToken
        );
        $multiplier = $token === NULL ? NULL : $token->multiplier();

        $arguments[] = new ArgumentNode($pieces, $multiplier);

        if($token instanceof BlockSeparatorToken){
            goto CONSUME_ARGUMENT;
        }elseif($token instanceof BlockEndToken){
            return new ArgumentsNode($arguments, TRUE);
        }

        return new ArgumentsNode($arguments, FALSE);
    }
}
