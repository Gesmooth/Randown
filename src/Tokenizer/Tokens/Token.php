<?php declare(strict_types = 1);

namespace Sbludufunk\Randown\Tokenizer\Tokens;

interface Token
{
    public function newlines(): Int;

    public function __toString(): String;
}
