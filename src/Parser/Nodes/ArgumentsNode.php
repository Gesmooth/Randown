<?php declare(strict_types = 1);

namespace Sbludufunk\Randown\Parser\Nodes;

class ArgumentsNode implements Node
{
    private $_arguments;

    private $_isTerminated;

    public function __construct(Array $arguments, Bool $isTerminated){
        $this->_arguments = $arguments;
        $this->_isTerminated = $isTerminated;
    }

    public function toArray(): array{
        return $this->_arguments;
    }

    public function isTerminated(): Bool{
        return $this->_isTerminated;
    }

    public function __toString(): String{
        $terminator = $this->_isTerminated ? "}" : "";
        return implode("|", $this->_arguments) . $terminator;
    }
}
