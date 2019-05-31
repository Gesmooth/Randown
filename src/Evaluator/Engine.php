<?php declare(strict_types = 1);

namespace Sbludufunk\Randown\Evaluator;

use Exception;
use Sbludufunk\Randown\Evaluator\Classes\PublicConstructors\BagClass;
use Sbludufunk\Randown\Evaluator\Classes\PrivateConstructors\ConcatClass;
use Sbludufunk\Randown\Evaluator\Classes\Objecto;
use Sbludufunk\Randown\Evaluator\Classes\PrivateConstructors\RandoClass;
use Sbludufunk\Randown\Evaluator\Classes\PrivateConstructors\TextClass;
use Sbludufunk\Randown\Nodes\ArgumentNode;
use Sbludufunk\Randown\Nodes\ArgumentsNode;
use Sbludufunk\Randown\Nodes\FunctionCallNode;
use Sbludufunk\Randown\Nodes\MethodCallNode;
use Sbludufunk\Randown\Nodes\RandoCallNode;
use Sbludufunk\Randown\Nodes\TextNode;
use Sbludufunk\Randown\Nodes\VariableNode;
use TypeError;
use function is_a;

class Engine
{
    /** @var Objecto[] */
    private $_functions;

    private $_variables;

    public function __construct(){
        $this->_functions = [];
        $this->_variables = [];
    }

    public function registerClass(String $variableName, String $className){
        if(!is_a($className, Objecto::CLASS, TRUE)){
            throw new TypeError();
        }

        $this->registerFunction($variableName, new class(
            $className
        ) implements FunctionInterface{
            private $_className;

            public function __construct(String $className){
                $this->_className = $className;
            }

            public function invoke(Objecto ...$arguments): Objecto{
                return new $this->_className(...$arguments);
            }
        });
    }

    public function registerFunction(String $name, FunctionInterface $function){
        $this->_functions[$name] = $function;
    }

    public function registerVariable(String $variableName, Objecto $value){
        // @TODO verify valid var name
        $this->_variables[$variableName] = $value;
    }

    public function evaluate(Array $nodes): String{
        return (String)$this->evaluateConcatenation($nodes);
    }

    /** @throws Exception */
    private function evaluateConcatenation(Array $nodes): ?Objecto{
        $buffer = [];
        foreach($nodes as $node){
            $item =
                $this->evaluateText($node) ??
                $this->evaluateVariable($node) ??
                $this->evaluateRandoCall($node) ??
                $this->evaluateFunctionCall($node) ??
                NULL;
            assert($item !== NULL);
            $buffer[] = $item;
        }
        return count($buffer) === 1 ? $buffer[0] : new ConcatClass($buffer);
    }

    /** @throws Exception */
    private function evaluateText($node): ?Objecto{
        if(!$node instanceof TextNode){ return NULL; }
        $text = new TextClass($node->unescape());
        return $this->evaluateMethodCalls($text, $node->methodCalls());
    }

    /** @throws Exception */
    private function evaluateVariable($node): ?Objecto{
        if(!$node instanceof VariableNode){ return NULL; }
        $thisValue = $this->_variables[$node->token()->name()] ?? NULL;
        if($thisValue === NULL){ throw new UndefinedVariable([], $node); }
        return $this->evaluateMethodCalls($thisValue, $node->methodCalls());
    }

    /** @throws Exception */
    private function evaluateFunctionCall($node): ?Objecto{
        if(!$node instanceof FunctionCallNode){ return NULL; }
        $functionName = $node->token()->name();
        $function = $this->_functions[$functionName] ?? NULL;
        if($function === NULL){ throw new UndefinedFunction([], $node); }
        /** @var FunctionInterface $function */
        $arguments = $this->evaluateArguments($node->arguments());
        $result = $function->invoke(...$arguments);
        return $this->evaluateMethodCalls($result, $node->methodCalls());
    }

    /** @throws Exception */
    private function evaluateRandoCall($node): ?Objecto{
        if(!$node instanceof RandoCallNode){ return NULL; }
        $arguments = $this->evaluateArguments($node->arguments());
        $opt = new RandoClass(new BagClass($arguments));
        return $this->evaluateMethodCalls($opt, $node->methodCalls());
    }

    /** @throws Exception */
    private function evaluateMethodCalls(Objecto $thisValue, array $methodCalls): Objecto{
        /** @var MethodCallNode[] $methodCalls */
        if($methodCalls === []){ return $thisValue; }
        $methodCallNode = array_shift($methodCalls);
        /** @var MethodCallNode $methodCallNode */
        $methodName = $methodCallNode->token()->name();
        $arguments = $this->evaluateArguments($methodCallNode->arguments());
        $newThisValue = $thisValue->invoke($methodName, $arguments);
        return $this->evaluateMethodCalls($newThisValue, $methodCalls);
    }

    /** @throws Exception */
    private function evaluateArguments(ArgumentsNode $arguments): array{
        $actualArguments = [];
        foreach($arguments->toArray() as $argument){
            /** @var ArgumentNode $argument */
            $actualArguments[] = $this->evaluateConcatenation($argument->contents());
        }
        return $actualArguments;
    }
}
