<?php declare(strict_types = 1);

use ReflectionClass as RC;
use Sbludufunk\Randown\DebuggingTokenStream;
use Sbludufunk\Randown\Evaluator\Engine;
use Sbludufunk\Randown\Evaluator\FunctionInterface;
use Sbludufunk\Randown\Evaluator\Classes\PublicConstructors\IntRandomClass;
use Sbludufunk\Randown\Evaluator\Classes\PublicConstructors\IntSingleClass;
use Sbludufunk\Randown\Evaluator\Classes\Objecto;
use Sbludufunk\Randown\Evaluator\Classes\PublicConstructors\SeqClass;
use Sbludufunk\Randown\Evaluator\Classes\PrivateConstructors\TextClass;
use Sbludufunk\Randown\Parser;
use Sbludufunk\Randown\Tokenizer;

require __DIR__ . "/vendor/autoload.php";

$source = file_get_contents(__DIR__ . "/source.md");
$tokenizer = new Tokenizer();
$tokens = $tokenizer->tokenize($source);

foreach($tokens as $token){
    $cn = (new RC($token))->getShortName();
    echo str_pad($cn, 30);
    var_dump((String)$token);
}


exit();

$parser = new Parser();
$nodes = $parser->parse(new DebuggingTokenStream($tokens));

$engine = new Engine();
$engine->registerReference("seconda settimana di Maggio 2019", new TextClass("LOLLO"));
$engine->registerClass("int", IntSingleClass::CLASS);
$engine->registerClass("rint", IntRandomClass::CLASS);
$engine->registerClass("Seq", SeqClass::CLASS);
$engine->registerFunction("inc", new class() implements FunctionInterface{
    public function invoke(Objecto ...$arguments): Objecto{
        $file = $arguments[0] ?? NULL;
        if($file === NULL){ throw new \Error(); }
        $path = (String)$file;
        $path = str_replace(["\\", "/"], "/", $path);
        $pieces = explode("/", $path);
        foreach($pieces as $piece){
            if(trim($piece) === ".."){
                throw new \Error();
            }
        }
        $result = (function($__PATH__){
            return require(__DIR__ . "/basedir/" . $__PATH__);
        })($path);

    }
});

$engine->registerFunction("var", new class($engine) implements FunctionInterface{
    private $_engine;

    public function __construct(Engine $engine){
        $this->_engine = $engine;
    }

    public function invoke(Objecto ...$arguments): Objecto{
        $variableName = $arguments[0];
        assert($variableName instanceof TextClass);
        $normalizedVariableName = preg_replace("/\s+/", " ", (String)$variableName);
        $normalizedVariableName = trim($normalizedVariableName);
        $this->_engine->registerReference($normalizedVariableName, $arguments[1]);
        return new TextClass("");
    }
});

echo $engine->evaluate($nodes);



