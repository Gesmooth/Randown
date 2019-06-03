<?php declare(strict_types = 1);

namespace Sbludufunk\Randown\Evaluator\Classes\Functions;

use Closure;
use Error;
use Sbludufunk\Randown\Evaluator\Classes\Objecto;

class IncFunction extends FunctionBase
{
    private $_evaluateFunction;

    public function __construct(Closure $evaluateFunction){
        $this->_evaluateFunction = $evaluateFunction;
    }

    public function invokeSelf(Objecto ...$arguments): Objecto{
        $file = $arguments[0] ?? NULL;

        if($file === NULL){
            throw new Error();
        }

        $path = (String)$file;
        $path = str_replace(["\\", "/"], "/", $path);
        $pieces = explode("/", $path);
        foreach($pieces as $piece){
            if(trim($piece) === ".."){
                throw new Error();
            }
        }

        $source = file_get_contents(__DIR__ . "/basedir/" . $path);

        return ($this->_evaluateFunction)($source);
    }
}
