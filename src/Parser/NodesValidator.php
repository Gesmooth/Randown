<?php declare(strict_types = 1);

namespace Sbludufunk\Randown\Parser;

use Sbludufunk\Randown\Parser\Nodes\Node;
use Sbludufunk\Randown\Tokenizer\Tokenizer;

class NodesValidator
{
    private $_tokenizer;

    private $_parser;

    public function __construct(Tokenizer $tokenizer, Parser $parser){
        $this->_tokenizer = $tokenizer;
        $this->_parser = $parser;
    }

    public function isNodeValid(Node $node): Bool{
        $stringifiedNode = (String)$node;
        $compareTokens = $this->_tokenizer->tokenize($stringifiedNode);
        $compareNodes = $this->_parser->parse(new TokenStream($compareTokens));
        return [$node] == $compareNodes;
    }

    public function isNodeSequenceValid(Array $nodes): Bool{
        $stringifiedNodes = implode("", $nodes);
        $compareTokens = $this->_tokenizer->tokenize($stringifiedNodes);
        $compareNodes = $this->_parser->parse(new TokenStream($compareTokens));
        return $nodes == $compareNodes;
    }
}