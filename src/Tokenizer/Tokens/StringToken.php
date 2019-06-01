<?php declare(strict_types = 1);

namespace Sbludufunk\Randown\Tokenizer\Tokens;

class StringToken implements Token
{
    private $_string;

    public function __construct(String $string){
        $this->_string = $string;
    }

    public function string(): String{
        return $this->_string;
    }

    public function __toString(): String{
        return $this->_string;
    }
}
