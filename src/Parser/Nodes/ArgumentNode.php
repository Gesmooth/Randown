<?php declare(strict_types = 1);

namespace Sbludufunk\Randown\Parser\Nodes;

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
        $this->_whitespaceBefore = $whitespaceBefore;
        $this->_contents = $contents;
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
        return
            $this->_whitespaceBefore .
            implode("", $this->_contents) .
            $this->_whitespaceAfter;
    }
}
