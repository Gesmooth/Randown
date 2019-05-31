<?php declare(strict_types = 1);

namespace Sbludufunk\Randown\Evaluator\Classes\PrivateConstructors;

use Sbludufunk\Randown\Evaluator\Classes\Objecto;
use function mb_strtoupper;

class TextClass extends Objecto
{
    private $_text;

    public function __construct(String $text){
        $this->_text = $text;
    }

    public function invoke(String $name, Objecto ...$arguments): Objecto{
        if($name === "upperCase"){
            return new TextClass(mb_strtoupper($this->_text));
        }

        return parent::invoke($name, $arguments);
    }

    public function __toString(): String{
        return $this->_text;
    }
}
