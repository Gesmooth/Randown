<?php declare(strict_types = 1);

use ReflectionClass as RC;
use Sbludufunk\Randown\Evaluator\Classes\Functions\IncFunction;
use Sbludufunk\Randown\Evaluator\Classes\Functions\IntFunction;
use Sbludufunk\Randown\Evaluator\Classes\Functions\RandoIntFunction;
use Sbludufunk\Randown\Evaluator\Classes\Functions\VarFunction;
use Sbludufunk\Randown\Evaluator\Classes\PrivateConstructors\TextClass;
use Sbludufunk\Randown\Evaluator\Classes\PublicConstructors\SeqClass;
use Sbludufunk\Randown\Evaluator\Engine;
use Sbludufunk\Randown\Parser\DebuggingTokenStream;
use Sbludufunk\Randown\Parser\NodesValidator;
use Sbludufunk\Randown\Parser\Parser;
use Sbludufunk\Randown\Tokenizer\Tokenizer;
use Sbludufunk\Randown\Tokenizer\TokenizerFunctions;

require __DIR__ . "/vendor/autoload.php";

$source = file_get_contents(__DIR__ . "/source.md");

$tokens = (new Tokenizer())->tokenize($source);

foreach($tokens as $index => $token){
    $cn = (new RC($token))->getShortName();
    echo str_pad($cn, 30) . $index . " - ";
    echo str_replace(["\r\n", "\r", "\n"], "\\n", (String)$token);
    echo "\n";
}

var_dump((new TokenizerFunctions())->findTokenLine($tokens, $tokens[12]));

exit();

$parser = new Parser();
$nodes = $parser->parse(new DebuggingTokenStream($tokens));

$nodesValidator = new NodesValidator($tokenizer, $parser);
var_dump($nodesValidator->isNodeSequenceValid($nodes));

exit();

$engine = new Engine();
$engine->registerReference(TRUE, "seconda settimana di Maggio 2019", new TextClass("LOLLO"));
$engine->registerReference(TRUE, "int", new IntFunction());
$engine->registerReference(TRUE, "rint", new RandoIntFunction());
$engine->registerReference(TRUE, "var", new VarFunction($engine));
$engine->registerReference(TRUE, "const", new VarFunction($engine));
$engine->registerReference(TRUE, "inc", new IncFunction());
$engine->registerClass("Seq", SeqClass::CLASS);
echo $engine->evaluate($nodes);



