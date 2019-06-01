<?php declare(strict_types = 1);

namespace Sbludufunk\Randown\Tokenizer\Tokens;

class MethodCallToken implements Token
{
    private $_whitespaceBeforeAmpersand;

    private $_whitespaceBeforeName;

    private $_name;

    private $_whitespaceAfterName;

    public function __construct(
        String $whitespaceBeforeAmpersand,
        String $whitespaceBeforeName,
        String $name,
        String $whitespaceAfterName
    ){
        $this->_whitespaceBeforeAmpersand = $whitespaceBeforeAmpersand;
        $this->_whitespaceBeforeName = $whitespaceBeforeName;
        $this->_name = $name;
        $this->_whitespaceAfterName = $whitespaceAfterName;
    }

    public function whitespaceBeforeAmpersand(): String{
        return $this->_whitespaceBeforeAmpersand;
    }

    public function whitespaceBeforeName(): String{
        return $this->_whitespaceBeforeName;
    }

    public function name(): String{
        return $this->_name;
    }

    public function whitespaceAfterName(): String{
        return $this->_whitespaceAfterName;
    }

    public function __toString(): String{
        return
            $this->_whitespaceBeforeAmpersand .
            "&" .
            $this->_whitespaceBeforeName .
            $this->_name .
            $this->_whitespaceAfterName .
            "{";
    }
}
