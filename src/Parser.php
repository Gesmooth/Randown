<?php declare(strict_types = 1);

namespace Sbludufunk\Randown;

use Sbludufunk\Randown\Nodes\ArgumentNode;
use Sbludufunk\Randown\Nodes\ArgumentsNode;
use Sbludufunk\Randown\Nodes\FunctionCallNode;
use Sbludufunk\Randown\Nodes\MethodCallNode;
use Sbludufunk\Randown\Nodes\RandoCallNode;
use Sbludufunk\Randown\Nodes\TextNode;
use Sbludufunk\Randown\Nodes\VariableNode;
use Sbludufunk\Randown\Tokens\BlockEndToken;
use Sbludufunk\Randown\Tokens\BlockStartToken;
use Sbludufunk\Randown\Tokens\EscapeToken;
use Sbludufunk\Randown\Tokens\FunctionCallToken;
use Sbludufunk\Randown\Tokens\MethodCallToken;
use Sbludufunk\Randown\Tokens\SeparatorToken;
use Sbludufunk\Randown\Tokens\TextToken;
use Sbludufunk\Randown\Tokens\VariableToken;

class Parser
{
    public function parse(TokenStream $tokens){
        $nodes = [];
        while($tokens->hasMore()){
            $node =
                $this->consumeVariable($tokens) ??
                $this->consumeTextNode($tokens) ??
                $this->consumeFunctionCall($tokens) ??
                $this->consumeRandoCall($tokens) ??
                $tokens->consume();
            $nodes[] = $node;
        }
        return $nodes;
    }

    private function consumeTextNode(TokenStream $tokens): ?TextNode{
        $pieces = [];
        CONSUME_TEXTUAL_BITS:
        $token = $tokens->peek();
        if($token instanceof TextToken || $token instanceof EscapeToken){
            $pieces[] = $token;
            $tokens->consume();
            goto CONSUME_TEXTUAL_BITS;
        }

        if($pieces === []){
            return NULL;
        }

        $methodCalls = [];
        CONSUME_METHOD_CALL:
        $methodCall = $this->consumeMethodCall($tokens);
        if($methodCall){
            $methodCalls[] = $methodCall;
            goto CONSUME_METHOD_CALL;
        }

        return new TextNode($pieces, $methodCalls);
    }

    private function consumeRandoCall(TokenStream $tokens): ?RandoCallNode{
        $blockStart = $tokens->peek();
        if(!$blockStart instanceof BlockStartToken){ return NULL; }
        $tokens->consume();
        $arguments = $this->consumeArguments($tokens);
        $methodCalls = $this->consumeMethodCalls($tokens);
        return new RandoCallNode($arguments, $methodCalls);
    }

    private function consumeFunctionCall(TokenStream $tokens): ?FunctionCallNode{
        $functionCallToken = $tokens->peek();
        if(!$functionCallToken instanceof FunctionCallToken){ return NULL; }
        $tokens->consume();
        $arguments = $this->consumeArguments($tokens);
        $methodCalls = $this->consumeMethodCalls($tokens);
        return new FunctionCallNode($functionCallToken, $arguments, $methodCalls);
    }

    private function consumeVariable(TokenStream $tokens): ?VariableNode{
        $variableToken = $tokens->peek();
        if(!$variableToken instanceof VariableToken){ return NULL; }
        $tokens->consume();
        $methodCalls = $this->consumeMethodCalls($tokens);
        return new VariableNode($variableToken, $methodCalls);
    }

    private function consumeMethodCalls(TokenStream $tokens): array{
        $methodCalls = [];
        CONSUME_METHOD_CALL:
        $methodCall = $this->consumeMethodCall($tokens);
        if($methodCall !== NULL){
            $methodCalls[] = $methodCall;
            goto CONSUME_METHOD_CALL;
        }
        return $methodCalls;
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
            $this->consumeVariable($tokens) ??
            $this->consumeTextNode($tokens) ??
            $this->consumeFunctionCall($tokens) ??
            $this->consumeRandoCall($tokens);
        if($piece){
            $pieces[] = $piece;
            goto CONSUME_PIECE;
        }

        $token = $tokens->consume();
        assert(
            $token === NULL ||
            $token instanceof BlockEndToken ||
            $token instanceof SeparatorToken
        );
        $multiplier = $token === NULL ? NULL : $token->multiplier();
        $arguments[] = new ArgumentNode($pieces, $multiplier);

        if($token instanceof SeparatorToken){
            goto CONSUME_ARGUMENT;
        }elseif($token instanceof BlockEndToken){
            return new ArgumentsNode($arguments, TRUE);
        }

        return new ArgumentsNode($arguments, FALSE);
    }
}
