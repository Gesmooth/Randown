<?php declare(strict_types = 1);

use Sbludufunk\Randown\DebuggingTokenStream;
use Sbludufunk\Randown\Evaluator\Concatenation;
use Sbludufunk\Randown\Evaluator\Engine;
use Sbludufunk\Randown\Evaluator\FunctionInterface;
use Sbludufunk\Randown\Evaluator\Objecto;
use Sbludufunk\Randown\Parser;
use Sbludufunk\Randown\Tokenizer;

require __DIR__ . "/vendor/autoload.php";

$source = file_get_contents(__DIR__ . "/source.md");
$tokenizer = new Tokenizer();
$tokens = $tokenizer->tokenize($source);
$parser = new Parser();
$nodes = $parser->parse(new DebuggingTokenStream($tokens));

$engine = new Engine();
$engine->registerFunction("var", new class implements FunctionInterface{
    public function invoke(Concatenation ...$arguments): Objecto{
        echo 1;
    }
});
$result = $engine->evaluateConcatenation($nodes);
