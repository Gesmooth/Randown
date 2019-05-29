<?php declare(strict_types = 1);

namespace Sbludufunk\Randown\DS;

use Error;
use Sbludufunk\Randown\Evaluator\Value;

class Text implements Value
{
    private $_text;

    public function __construct(String $text){
        $this->_text = $text;
    }

    public function __toString(): String{
        return $this->_text;
    }

    public function invoke(String $name, Array $arguments): Value{
        throw new Error();
    }
}
