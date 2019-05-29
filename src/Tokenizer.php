<?php declare(strict_types = 1);

namespace Sbludufunk\Randown;

use Sbludufunk\Randown\Tokens\SeparatorToken;
use Sbludufunk\Randown\Tokens\BlockEndToken;
use Sbludufunk\Randown\Tokens\BlockStartToken;
use Sbludufunk\Randown\Tokens\EscapeToken;
use Sbludufunk\Randown\Tokens\FunctionCallToken;
use Sbludufunk\Randown\Tokens\MethodCallToken;
use Sbludufunk\Randown\Tokens\TextToken;
use Sbludufunk\Randown\Tokens\VariableToken;
use function array_column;
use function preg_match;
use const PREG_SPLIT_NO_EMPTY;

class Tokenizer
{
    private $_patterns;

    private $_splitPattern;

    public function __construct(){
        $this->_patterns = [];

        $this->_patterns[] = [
            EscapeToken::CLASS,
            "\\\\    [\\\\{}@*|&]",
            "\\\\   ([\\\\{}@*|&])"
        ];

        $this->_patterns[] = [
            VariableToken::CLASS,
            "\\$    .*?    \\$",
            "\\$   (.*?)   \\$"
        ];

        $this->_patterns[] = [
            FunctionCallToken::CLASS,
            "@    \\s*     [a-zA-Z_][a-zA-Z0-9_]*     \\s*    \\{",
            "@   (\\s*)   ([a-zA-Z_][a-zA-Z0-9_]*)   (\\s*)   \\{"
        ];

        $this->_patterns[] = [
            MethodCallToken::CLASS,
            "\\s*     \\&    \\s*     [a-zA-Z_][a-zA-Z0-9_]*     \\s*    \\{",
            "(\\s*)   \\&   (\\s*)   ([a-zA-Z_][a-zA-Z0-9_]*)   (\\s*)   \\{"
        ];

        $this->_patterns[] = [
            SeparatorToken::CLASS,
            "\\*    [0-9]+    \\|",
            "\\*   ([0-9]+)   \\|"
        ];

        $this->_patterns[] = [
            BlockEndToken::CLASS,
            "\\*    [0-9]+    \\}",
            "\\*   ([0-9]+)   \\}"
        ];

        $this->_patterns[] = [SeparatorToken::CLASS, "\\|", "\\|"];
        $this->_patterns[] = [BlockStartToken::CLASS,   "\\{", "\\{"];
        $this->_patterns[] = [BlockEndToken::CLASS,     "\\}", "\\}"];

        $this->_splitPattern = implode("|", array_column($this->_patterns, 1));
    }

    public function tokenize(String $document){

        $rawTokens = preg_split(
            "/(" . $this->_splitPattern . ")/xsD", $document, 0,
            PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY
        );

        $tokens = [];
        for(
            $i = 0, $countTokens = count($rawTokens);
            $i < $countTokens;
            $i++
        ){
            $rawToken = $rawTokens[$i];
            foreach($this->_patterns as [$class, $splitPattern, $capturePattern]){
                $bits = [];
                if(preg_match("/^" . $capturePattern . "$/xsD", $rawToken, $bits) === 1){
                    $tokens[] = new $class(...array_slice($bits, 1));
                    continue 2;
                }
            }
            $tokens[] = new TextToken($rawToken);
        }

        return $tokens;
    }
}
