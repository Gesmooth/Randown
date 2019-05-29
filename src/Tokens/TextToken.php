<?php declare(strict_types = 1);

namespace Sbludufunk\Randown\Tokens;

class TextToken implements Token
{
    private $_text;

    public function __construct(String $text){
        $this->_text = $text;
    }

    public function text(): String{
        return $this->_text;
    }

    public function __toString(): String{
        return $this->_text;
    }
}
