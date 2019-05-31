<?php declare(strict_types = 1);

namespace Sbludufunk\Randown\Nodes;

use Sbludufunk\Randown\Tokens\EscapeToken;
use Sbludufunk\Randown\Tokens\MethodCallToken;
use Sbludufunk\Randown\Tokens\TextToken;

class TextNode implements ObjectNode
{
    private $_pieces;

    private $_methodCalls;

    public function __construct(Array $pieces, Array $methodCalls){
        assert(count($pieces) >= 1);

        foreach($pieces as $piece){
            assert($piece instanceof TextToken || $piece instanceof EscapeToken);
        }

        foreach($methodCalls as $methodCall){
            assert($methodCall instanceof MethodCallNode);
        }

        $this->_pieces = $pieces;
        $this->_methodCalls = $methodCalls;
    }

    public function __toString(): String{
        return implode("", $this->_pieces) . implode("", $this->_methodCalls);
    }

    public function unescape(): String{
        $b = "";
        foreach($this->_pieces as $piece){
            /** @var EscapeToken|TextToken $piece */
            $b .= $piece->text();
        }
        return $b;
    }

    public function pieces(): array{
        return $this->_pieces;
    }

    public function methodCalls(): array{
        return $this->_methodCalls;
    }
}
