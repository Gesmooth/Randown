<?php declare(strict_types = 1);

namespace Sbludufunk\Randown\Nodes;

use Sbludufunk\Randown\Tokens\EscapeToken;
use Sbludufunk\Randown\Tokens\TextToken;
use function array_reduce;

class TextNode implements ObjectNode
{
    private $_pieces;

    private $_calls;

    public function __construct(Array $pieces, Array $calls){
        assert(count($pieces) >= 1);

        // @TODO check that $pieces does not contain adjacent TextTokens

        foreach($pieces as $piece){
            assert($piece instanceof TextToken || $piece instanceof EscapeToken);
        }

        foreach($calls as $call){
            assert($call instanceof MethodCallNode || $call instanceof FunctionCallNode);
        }

        $this->_pieces = $pieces;
        $this->_calls = $calls;
    }

    public function unescape(): String{
        return array_reduce($this->_pieces, function(String $carry, $piece){
            assert($piece instanceof EscapeToken || $piece instanceof TextToken);
            return $carry . $piece->intendedText();
        }, "");
    }

    public function pieces(): array{
        return $this->_pieces;
    }

    public function calls(): array{
        return $this->_calls;
    }

    public function __toString(): String{
        return implode("", $this->_pieces) . implode("", $this->_calls);
    }
}
