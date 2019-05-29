<?php declare(strict_types = 1);

use Sbludufunk\Randown\DebuggingTokenStream;
use Sbludufunk\Randown\Evaluator\Engine;
use Sbludufunk\Randown\Evaluator\FunctionInterface;
use Sbludufunk\Randown\Evaluator\Intraclasses\Objecto;
use Sbludufunk\Randown\Evaluator\Intraclasses\Text;
use Sbludufunk\Randown\Parser;
use Sbludufunk\Randown\Tokenizer;

require __DIR__ . "/vendor/autoload.php";

$source = file_get_contents(__DIR__ . "/source.md");
$tokenizer = new Tokenizer();
$tokens = $tokenizer->tokenize($source);
$parser = new Parser();
$nodes = $parser->parse(new DebuggingTokenStream($tokens));

$engine = new Engine();
$engine->registerFunction("var", new class($engine) implements FunctionInterface{
    private $_engine;

    public function __construct(Engine $engine){
        $this->_engine = $engine;
    }

    public function invoke(Objecto ...$arguments): Objecto{
        $variableName = $arguments[0];
        assert($variableName instanceof Text);
        $normalizedVariableName = preg_replace("/\s+/", " ", (String)$variableName);
        $normalizedVariableName = trim($normalizedVariableName);
        $this->_engine->registerVariable($normalizedVariableName, $arguments[1]);
        return new Text("");
    }
});

echo $engine->evaluate($nodes);