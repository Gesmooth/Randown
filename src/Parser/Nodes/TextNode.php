<?php declare(strict_types = 1);

namespace Sbludufunk\Randown\Parser\Nodes;

use Sbludufunk\Randown\Tokenizer\Tokens\EscapeToken;
use Sbludufunk\Randown\Tokenizer\Tokens\StringToken;
use function array_reduce;

class TextNode implements ValidRootNode
{
    private $_pieces;

    private $_calls;

    public function __construct(Array $pieces, Array $calls){
        $this->_pieces = $pieces;
        $this->_calls = $calls;
    }

    public function unescape(): String{
        return array_reduce($this->_pieces, function(String $carry, $piece){
            assert($piece instanceof EscapeToken || $piece instanceof StringToken);
            return $carry . $piece->string();
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
