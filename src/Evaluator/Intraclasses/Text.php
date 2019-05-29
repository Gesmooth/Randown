<?php declare(strict_types = 1);

namespace Sbludufunk\Randown\Evaluator\Intraclasses;

use Error;
use function mb_strtoupper;

class Text implements Objecto
{
    private $_text;

    public function __construct(String $text){
        $this->_text = $text;
    }

    public function __toString(): String{
        return $this->_text;
    }

    public function invoke(String $name, Array $arguments): Objecto{
        if($name === "upperCase"){
            return new Text(mb_strtoupper($this->_text));
        }
        throw new Error("Method $name does not exist");
    }
}
