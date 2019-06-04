<?php declare(strict_types = 1);

namespace Sbludufunk\Randown\Tokenizer\Tokens;

class BlockSeparatorToken implements Token
{
    public function newlines(): Int{
        return 0;
    }

    public function __toString(): String{
        return "|";
    }
}
