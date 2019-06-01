<?php declare(strict_types = 1);

namespace Sbludufunk\Randown\Parser\Nodes;

class RandoNode implements ValidRootNode
{
    private $_arguments;

    private $_calls;

    public function __construct(ArgumentsNode $arguments, Array $calls){
        $this->_arguments = $arguments;
        $this->_calls = $calls;
    }

    public function arguments(): ArgumentsNode{
        return $this->_arguments;
    }

    public function calls(): array{
        return $this->_calls;
    }

    public function __toString(): String{
        return "{" . $this->_arguments . implode("", $this->_calls);
    }
}
