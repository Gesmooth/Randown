<?php declare(strict_types = 1);

namespace Sbludufunk\Randown\Nodes;

class ArgumentNode implements Node
{
    private $_contents;

    private $_multiplier;

    public function __construct(Array $contents, ?String $multiplier){
        $this->_contents = $contents;
        $this->_multiplier = $multiplier;
    }

    public function __toString(): String{
        $multiplier = $this->_multiplier === NULL ? "" : "*" . $this->_multiplier;
        return implode("", $this->_contents) . $multiplier;
    }

    public function contents(): array{
        return $this->_contents;
    }

    public function multiplier(): ?String{
        return $this->_multiplier;
    }
}
