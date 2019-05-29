<?php declare(strict_types = 1);

namespace Sbludufunk\Randown\Tokens;

class VariableToken implements Token
{
    private $_name;

    public function __construct(String $name){
        $this->_name = $name;
    }

    public function name(): String{
        return $this->_name;
    }

    public function __toString(): String{
        return "$" . $this->_name . "$";
    }
}
