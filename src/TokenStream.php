<?php declare(strict_types = 1);

namespace Sbludufunk\Randown;

use Sbludufunk\Randown\Tokens\Token;

class TokenStream
{
    /** @var TokenStream */
    private $originator;

    /** @var Token[] */
    public $tokens;

    /** @var Int */
    public $index;

    public function __construct($tokens){
        $this->originator = $this;
        $this->tokens = $tokens;
        $this->index = 0;
    }

    public function EOF(): Bool{
        return ($this->tokens[$this->index] ?? NULL) === NULL;
    }

    public function hasMore(): Bool{
        return !$this->EOF();
    }

    public function peek(): ?Token{
        return $this->tokens[$this->index] ?? NULL;
    }

    public function consume(): ?Token{
        $token = $this->tokens[$this->index] ?? NULL;
        if($token === NULL){ return NULL; }
        $this->index++;
        return $token;
    }

    public function save(){
        return $this->index;
    }

    public function restore($savePoint){
        $this->index = $savePoint;
    }

    public function branch(): TokenStream{
        return clone $this;
    }

    public function merge(TokenStream $stream){
        assert($this->originator === $stream->originator);
        $this->index = $stream->index;
    }
}
