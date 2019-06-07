<?php declare(strict_types = 1);

namespace Sbludufunk\Randown\Tokenizer;

use Sbludufunk\Randown\Tokenizer\Tokens\BlockEndToken;
use Sbludufunk\Randown\Tokenizer\Tokens\BlockSeparatorToken;
use Sbludufunk\Randown\Tokenizer\Tokens\BlockStartToken;
use Sbludufunk\Randown\Tokenizer\Tokens\EscapeToken;
use Sbludufunk\Randown\Tokenizer\Tokens\FunctionCallToken;
use Sbludufunk\Randown\Tokenizer\Tokens\MethodCallToken;
use Sbludufunk\Randown\Tokenizer\Tokens\ReferenceToken;
use Sbludufunk\Randown\Tokenizer\Tokens\WhitespaceToken;
use Sbludufunk\Randown\Tokenizer\Tokens\WordToken;
use Sbludufunk\Randown\Tokenizer\Tokens\Token;
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
            "\\\\    [\\\\{}@*|&$]",
            "\\\\   ([\\\\{}@*|&$])"
        ];

        $this->_patterns[] = [
            ReferenceToken::CLASS,
            "\\$    .*?    \\$",
            "\\$   (.*?)   \\$"
        ];

        $this->_patterns[] = [
            FunctionCallToken::CLASS,
            " \\s*    \\&    \\s*    \\{",
            "(\\s*)   \\&   (\\s*)   \\{"
        ];

        $this->_patterns[] = [
            MethodCallToken::CLASS,
            " \\s*    \\&    \\s*     ..*?     \\s*    \\{",
            "(\\s*)   \\&   (\\s*)   (..*?)   (\\s*)   \\{"
        ];

        $this->_patterns[] = [
            WhitespaceToken::CLASS,
            " \\s+",
            "(\\s+)"
        ];

        $this->_patterns[] = [BlockSeparatorToken::CLASS, "\\|", "\\|"];
        $this->_patterns[] = [BlockStartToken::CLASS,     "\\{", "\\{"];
        $this->_patterns[] = [BlockEndToken::CLASS,       "\\}", "\\}"];

        $this->_splitPattern = implode("|", array_column($this->_patterns, 1));
    }

    /** @return Token[] */
    public function tokenize(String $document): array{

        $rawTokens = preg_split(
            "/(" . $this->_splitPattern . ")/xsD", $document, 0,
            PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY
        );

        $tokens = [];
        for($i = 0, $countTokens = count($rawTokens); $i < $countTokens; $i++){
            $rawToken = $rawTokens[$i];
            foreach($this->_patterns as [$class, $splitPattern, $capturePattern]){
                $bits = [];
                if(preg_match("/^" . $capturePattern . "$/xsD", $rawToken, $bits) === 1){
                    $tokens[] = new $class(...array_slice($bits, 1));
                    continue 2;
                }
            }
            $tokens[] = new WordToken($rawToken);
        }

        return $tokens;
    }
}
