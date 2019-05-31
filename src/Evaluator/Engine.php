<?php declare(strict_types = 1);

namespace Sbludufunk\Randown\Evaluator;

use Error;
use Exception;
use Sbludufunk\Randown\Evaluator\Classes\Objecto;
use Sbludufunk\Randown\Evaluator\Classes\PrivateConstructors\ConcatClass;
use Sbludufunk\Randown\Evaluator\Classes\PrivateConstructors\RandoClass;
use Sbludufunk\Randown\Evaluator\Classes\PrivateConstructors\TextClass;
use Sbludufunk\Randown\Evaluator\Classes\PublicConstructors\BagClass;
use Sbludufunk\Randown\Nodes\ArgumentNode;
use Sbludufunk\Randown\Nodes\ArgumentsNode;
use Sbludufunk\Randown\Nodes\FunctionCallNode;
use Sbludufunk\Randown\Nodes\MethodCallNode;
use Sbludufunk\Randown\Nodes\RandoCallNode;
use Sbludufunk\Randown\Nodes\TextNode;
use Sbludufunk\Randown\Nodes\ReferenceNode;

class Engine
{
    private $_variables;

    public function __construct(){
        $this->_variables = [];
    }

    public function registerReference(Bool $constant, String $name, Objecto $value){
        // @TODO verify valid var name
        if($this->_variables[$name]["constant"] ?? FALSE){
            throw new Error("Cannot override constant reference $name");
        }
        $this->_variables[$name] = ["value" => $value, "constant" => $constant];
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
        return $this->evaluateMethodCalls($text, $node->calls());
    }

    /** @throws Exception */
    private function evaluateVariable($node): ?Objecto{
        if(!$node instanceof ReferenceNode){ return NULL; }
        $thisValue = $this->_variables[$node->token()->name()] ?? NULL;
        if($thisValue === NULL){ throw new UndefinedVariable([], $node); }
        return $this->evaluateMethodCalls($thisValue, $node->calls());
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
        return $this->evaluateMethodCalls($opt, $node->calls());
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
        foreach($arguments->arguments() as $argument){
            /** @var ArgumentNode $argument */
            $actualArguments[] = $this->evaluateConcatenation($argument->contents());
        }
        return $actualArguments;
    }
}
