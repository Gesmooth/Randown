<?php declare(strict_types = 1);

namespace Sbludufunk\Randown\Tokenizer\Tokens;

class FunctionCallToken implements Token
{
    private $_whitespaceBeforeAmpersand;

    private $_whitespaceAfterAmpersand;

    public function __construct(
        String $whitespaceBeforeAmpersand,
        String $whitespaceAfterAmpersand
    ){
        $this->_whitespaceBeforeAmpersand = $whitespaceBeforeAmpersand;
        $this->_whitespaceAfterAmpersand = $whitespaceAfterAmpersand;
    }

    public function whitespaceBeforeAmpersand(): String{
        return $this->_whitespaceBeforeAmpersand;
    }

    public function whitespaceAfterAmpersand(): String{
        return $this->_whitespaceAfterAmpersand;
    }

    public function __toString(): String{
        return
            $this->_whitespaceBeforeAmpersand . "&" .
            $this->_whitespaceAfterAmpersand . "{";
    }
}
