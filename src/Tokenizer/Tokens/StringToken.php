<?php declare(strict_types = 1);

namespace Sbludufunk\Randown\Tokenizer\Tokens;

use Sbludufunk\Randown\Tools\CountNewlines;
use function preg_match;

class StringToken implements Token
{
    private $_string;

    public function __construct(String $string){
        $this->_string = $string;
    }

    public function string(): String{
        return $this->_string;
    }

    public function isWhitespace(): Bool{
        return preg_match("@^[\n\r\f\t ]+$@", $this->_string);
    }

    public function newlines(): Int{
        return CountNewlines::call($this->_string);
    }

    public function __toString(): String{
        return $this->_string;
    }
}
