<?php declare(strict_types = 1);

namespace Sbludufunk\Randown\Nodes;

class ArgumentNode implements Node
{
    private $_whitespaceBefore;
    private $_contents;
    private $_whitespaceAfter;

    public function __construct(
        ?TextNode $whitespaceBefore,
        Array $contents,
        ?TextNode $whitespaceAfter
    ){
        // @TODO check that if $contents is empty, the whitespace must be set in before and never in after
        $this->_whitespaceBefore = $whitespaceBefore;
        $this->_contents = $contents; // @TODO check must not begin or end with a whitespace TextNode
        $this->_whitespaceAfter = $whitespaceAfter;
    }

    public function whitespaceBefore(): TextNode{
        return $this->_whitespaceBefore;
    }

    public function contents(): array{
        return $this->_contents;
    }

    public function whitespaceAfter(): TextNode{
        return $this->_whitespaceAfter;
    }

    public function __toString(): String{
        return implode("", $this->_contents);
    }
}
