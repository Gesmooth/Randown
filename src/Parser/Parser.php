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

        // First off, we need to try consuming the orphan calls,
        // e.g. $ref$&{argument | &orphan-method{} &also-orphan{} argument | argument }
        // which are syntax errors that we choose to ignore
        // note that leading whitespace is included in the calls
        $orphanCalls = $this->consumeCalls($tokens);

        // This step aims at skipping the whitespace that appears before the actual
        // argument, if any
        /** @var WhitespaceToken|NULL $whitespaceBefore */
        $whitespaceBefore = NULL;
        if($tokens->peek() instanceof WhitespaceToken){
            $whitespaceBefore = $tokens->consume();
        }

        /** @var Node[] $argumentPieces */
        $argumentPieces = [];
        CONSUME_ARGUMENT_PIECE:

        // Next we try to consume any sequence of reference nodes and rando nodes
        // followed by their relative calls
        $argumentPiece = $this->consumeReferenceNode($tokens);
        $argumentPiece = $argumentPiece ?? $this->consumeRandoNode($tokens);
        if($argumentPiece !== NULL){
            $argumentPieces[] = $argumentPiece;
            goto CONSUME_ARGUMENT_PIECE;
        }

        // At this point the next token can be an argument terminator (i.e. block-end,
        // block-separator or NULL) or a textnode token (i.e. a whitespace, a word,
        // or an escape)

        // In a branch, check whether the next token is an argument terminator,
        // ignoring a leading whitespace token if any
        /** @var WhitespaceToken|NULL $whitespaceAfter */
        $whitespaceAfter = NULL;
        $terminateArgumentBranch = $tokens->branch();
        $token = $terminateArgumentBranch->consume();
        if($token instanceof WhitespaceToken){
            $whitespaceAfter = $token;
            $token = $terminateArgumentBranch->consume();
        }
        if(
            $token === NULL ||
            $token instanceof BlockEndToken ||
            $token instanceof BlockSeparatorToken
        ){
            // Since we have found an argument terminator, we finalzie the branch in the
            // $tokens main.
            $tokens->merge($terminateArgumentBranch);

            // If it is an argument terminator, append it to the list of collected arguments
            $arguments[] = new ArgumentNode($whitespaceBefore, $argumentPieces, $whitespaceAfter);

            if($token === NULL){
                // If the token stream is now empty, return the arguments
                return new ArgumentsNode($arguments, FALSE);
            }elseif($token instanceof BlockEndToken){
                // If the {} block is complete, return the arguments
                return new ArgumentsNode($arguments, TRUE);
            }else{// BlockSeparatorToken
                // Otherwise an argument separator means we need to proceed collecting
                // another argument
                goto CONSUME_ARGUMENT;
            }
        }

        // Instead, if the token is not an argument terminator, we scratch the
        // $terminateArgumentBranch and we continue using the $tokens one. Reached this
        // step, the next token can be any of a text node (word, whitespace and escape)
        // and we are sure it's not a whitespace followed by an argument terminator
        $token = $tokens->consume();


        $calls = $this->consumeCalls($tokens);




    }
}
