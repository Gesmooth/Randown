<?php declare(strict_types = 1);

namespace Sbludufunk\Randown\Tokenizer\Tokens;

class BlockEndToken implements Token
{
    public function __toString(): String{
        return "}";
    }
}
