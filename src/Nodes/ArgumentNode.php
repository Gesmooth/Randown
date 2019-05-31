<?php declare(strict_types = 1);

namespace Sbludufunk\Randown\Nodes;

class ArgumentNode implements Node
{
    private $_contents;

    public function __construct(
        TextNode $whitespaceBefore,
        Array $contents,
        TextNode $whitespaceAfter
    ){
        $this->_contents = $contents; // @TODO check must not begin or end with a whitespace TextNode
    }

    public function contents(): array{
        return $this->_contents;
    }

    public function __toString(): String{
        return implode("", $this->_contents);
    }
}
