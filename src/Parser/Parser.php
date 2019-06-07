<?php declare(strict_types = 1);

namespace Sbludufunk\Randown\Parser;

use Sbludufunk\Randown\Parser\Nodes\ArgumentNode;
use Sbludufunk\Randown\Parser\Nodes\ArgumentsNode;
use Sbludufunk\Randown\Parser\Nodes\FunctionCallNode;
use Sbludufunk\Randown\Parser\Nodes\MethodCallNode;
use Sbludufunk\Randown\Parser\Nodes\Node;
use Sbludufunk\Randown\Parser\Nodes\RandoNode;
use Sbludufunk\Randown\Parser\Nodes\ReferenceNode;
use Sbludufunk\Randown\Parser\Nodes\SyntaxErrorNode;
use Sbludufunk\Randown\Parser\Nodes\TextNode;
use Sbludufunk\Randown\Tokenizer\Tokens\BlockEndToken;
use Sbludufunk\Randown\Tokenizer\Tokens\BlockSeparatorToken;
use Sbludufunk\Randown\Tokenizer\Tokens\BlockStartToken;
use Sbludufunk\Randown\Tokenizer\Tokens\EscapeToken;
use Sbludufunk\Randown\Tokenizer\Tokens\FunctionCallToken;
use Sbludufunk\Randown\Tokenizer\Tokens\MethodCallToken;
use Sbludufunk\Randown\Tokenizer\Tokens\ReferenceToken;
use Sbludufunk\Randown\Tokenizer\Tokens\WhitespaceToken;
use Sbludufunk\Randown\Tokenizer\Tokens\WordToken;

class Parser
{
    public function parse(TokenStream $tokens){
        $nodes = [];
        while($tokens->hasMore()){
            $node =
                $this->consumeReferenceNode($tokens) ??
                $this->consumeTextNode($tokens) ??
                $this->consumeRandoNode($tokens) ??
                new SyntaxErrorNode($tokens->consume()); // aka syntax error
            $nodes[] = $node;
        }
        return $nodes;
    }

    private function consumeIgnoredWhitespaceBeforeArgument(
        TokenStream $tokens
    ): ?WhitespaceToken{
        $cloneTokens = $tokens->branch();
        $token = $cloneTokens->consume();
        if($token instanceof WhitespaceToken){
            $calls = $this->consumeCalls($cloneTokens);
            if($calls === []){
                $tokens->merge($cloneTokens);
                return $token;
            }
        }
        return NULL;
    }

    private function consumeTextNode(TokenStream $tokens): ?TextNode{
        $pieces = [];
        CONSUME_PIECE:
        $token = $tokens->peek();
        if(
            $token instanceof WhitespaceToken ||
            $token instanceof WordToken ||
            $token instanceof EscapeToken
        ){
            $pieces[] = $tokens->consume();
            goto CONSUME_PIECE;
        }

        if($pieces === []){
            return NULL;
        }

        return new TextNode($pieces, $this->consumeCalls($tokens));
    }

    private function consumeRandoNode(TokenStream $tokens): ?RandoNode{
        $blockStart = $tokens->peek();
        if(!$blockStart instanceof BlockStartToken){ return NULL; }
        $tokens->consume();
        $arguments = $this->consumeArguments($tokens);
        $calls = $this->consumeCalls($tokens);
        return new RandoNode($arguments, $calls);
    }

    private function consumeReferenceNode(TokenStream $tokens): ?ReferenceNode{
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

        $whitespaceBefore = $this->consumeIgnoredWhitespaceBeforeArgument($tokens);

        /** @var Node[] $pieces */
        $pieces = [];

        CONSUME_PIECE:

        $piece =
            $this->consumeReferenceNode($tokens) ??
            $this->consumeTextNode($tokens) ??
            $this->consumeRandoNode($tokens);

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

        $arguments[] = new ArgumentNode($whitespaceBefore, $pieces, $whitespaceAfter);

        if($token instanceof BlockSeparatorToken){
            goto CONSUME_ARGUMENT;
        }elseif($token instanceof BlockEndToken){
            return new ArgumentsNode($arguments, TRUE);
        }

        return new ArgumentsNode($arguments, FALSE);
    }
}
