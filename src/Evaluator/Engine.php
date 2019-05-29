<?php declare(strict_types = 1);

namespace Sbludufunk\Randown\Evaluator;

use Exception;
use Sbludufunk\Randown\DS\Bag;
use Sbludufunk\Randown\DS\OptionBag;
use Sbludufunk\Randown\DS\Text;
use Sbludufunk\Randown\Nodes\ArgumentNode;
use Sbludufunk\Randown\Nodes\ArgumentsNode;
use Sbludufunk\Randown\Nodes\FunctionCallNode;
use Sbludufunk\Randown\Nodes\MethodCallNode;
use Sbludufunk\Randown\Nodes\RandoCallNode;
use Sbludufunk\Randown\Nodes\TextNode;
use Sbludufunk\Randown\Nodes\VariableNode;

class Engine
{
    /** @var Value[] */
    private $_functions;

    private $_variables;

    public function __construct(){
        $this->_functions = [];
        $this->_variables = [];
    }

    public function registerFunction(String $name, FunctionInterface $function){
        $this->_functions[$name] = $function;
    }

    /** @throws Exception */
    public function evaluateConcatenation(Array $nodes){
        $concatenation = [];
        foreach($nodes as $node){
            $item =
                $this->evaluateText($node) ??
                $this->evaluateVariable($node) ??
                $this->evaluateRandoCall($node) ??
                $this->evaluateFunctionCall($node) ??
                NULL;
            assert($item !== NULL);
            $concatenation[] = $item;
        }
        return new Concatenation($concatenation);
    }

    /** @throws Exception */
    public function evaluateText($node){
        if(!$node instanceof TextNode){ return NULL; }
        $text = new Text($node->unescape());
        return $this->evaluateMethodCalls($text, $node->methodCalls());
    }

    /** @throws Exception */
    public function evaluateVariable($node): ?Value{
        if(!$node instanceof VariableNode){ return NULL; }
        $thisValue = $this->_variables[$node->token()->name()] ?? NULL;
        if($thisValue === NULL){ throw new UndefinedVariable($nodes, $node); }
        return $this->evaluateMethodCalls($thisValue, $node->methodCalls());
    }

    /** @throws Exception */
    public function evaluateFunctionCall($node): ?Value{
        if(!$node instanceof FunctionCallNode){ return NULL; }
        $functionName = $node->token()->name();
        $function = $this->_functions[$functionName] ?? NULL;
        if($function === NULL){ throw new UndefinedFunction($nodes, $node); }
        /** @var FunctionInterface $function */
        $arguments = $this->evaluateArguments($node->arguments());
        $result = $function->__invoke(...$arguments);
        return $this->evaluateMethodCalls($result, $node->methodCalls());
    }

    /** @throws Exception */
    public function evaluateRandoCall($node): ?Value{
        if(!$node instanceof RandoCallNode){ return NULL; }
        $arguments = $this->evaluateArguments($node->arguments());
        $opt = new OptionBag(new Bag($arguments));
        return $this->evaluateMethodCalls($opt, $node->methodCalls());
    }

    /** @throws Exception */
    public function evaluateMethodCalls(Value $thisValue, array $methodCalls){
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
    public function evaluateArguments(ArgumentsNode $arguments): array{
        $actualArguments = [];
        foreach($arguments->toArray() as $argument){
            /** @var ArgumentNode $argument */
            $actualArguments[] = $this->evaluateConcatenation($argument->contents());
        }
        return $actualArguments;
    }
}
