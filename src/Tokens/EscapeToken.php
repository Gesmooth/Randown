<?php declare(strict_types = 1);

namespace Sbludufunk\Randown\Tokens;

class EscapeToken implements Token
{
    private $_character;

    public function __construct(String $character){
        $this->_character = $character;
    }

    public function text(): String{
        return $this->_character;
    }

    public function __toString(): String{
        return "\\" . $this->_character;
    }
}
