<?php declare(strict_types = 1);

namespace Sbludufunk\Randown\Tokenizer\Tokens;

class ReferenceToken implements Token
{
    private $_name;

    public function __construct(String $name){
        $this->_name = $name;
    }

    public function name(): String{
        return $this->_name;
    }

    public function normalize(): ReferenceToken{
        return trim(preg_replace("/\s+/", " ", $this->_name));
    }

    public function __toString(): String{
        return "$" . $this->_name . "$";
    }
}
