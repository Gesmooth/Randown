<?php declare(strict_types = 1);

namespace Sbludufunk\Randown;

use Sbludufunk\Randown\Tokens\Token;

class DebuggingTokenStream extends TokenStream
{
    public $readableTokens = [];

    public function __construct($tokens){
        parent::__construct($tokens);
        $this->updatePreview();
    }

    private function updatePreview(){
        $this->readableTokens = [];
        for($i = $this->index; $i < count($this->tokens); $i++){
            $this->readableTokens[] = (String)$this->tokens[$i];
        }
    }

    public function consume(): ?Token{
        $return = parent::consume();
        $this->updatePreview();
        return $return;
    }

    public function restore($savePoint){
        $this->index = $savePoint;
        $this->updatePreview();
    }

    public function merge(TokenStream $stream){
        parent::merge($stream);
        $this->updatePreview();
    }
}
