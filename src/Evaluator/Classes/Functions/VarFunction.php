<?php declare(strict_types = 1);

namespace Sbludufunk\Randown\Evaluator\Classes\Functions;

use Sbludufunk\Randown\Evaluator\Classes\Objecto;
use Sbludufunk\Randown\Evaluator\Engine;
use Sbludufunk\Randown\Tokenizer\Tokens\ReferenceToken;

class VarFunction extends FunctionBase
{
    private $_engine;

    public function __construct(Engine $engine){
        $this->_engine = $engine;
    }

    public function invokeSelf(Objecto ...$arguments): Objecto{
        $text = new ReferenceToken((String)$arguments[0]);
        $this->_engine->registerReference(TRUE, $text, $arguments[1]);
    }
}
