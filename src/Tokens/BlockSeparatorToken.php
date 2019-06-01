<?php declare(strict_types = 1);

namespace Sbludufunk\Randown\Tokens;

class BlockSeparatorToken implements Token
{
    private $_multiplier;

    public function __construct(?String $multiplier = NULL){
        $this->_multiplier = $multiplier;
    }

    public function multiplier(): ?String{
        return $this->_multiplier;
    }

    public function __toString(): String{
        return $this->_multiplier === NULL ? "|" : "*" . $this->_multiplier . "|";
    }
}
