<?php declare(strict_types = 1);

namespace Sbludufunk\Randown\Tokens;

class FunctionCallToken implements Token
{
    private $_wsBefore;

    private $_name;

    private $_wsAfter;

    public function __construct(String $wsBefore, String $name, String $wsAfter){
        $this->_wsBefore = $wsBefore;
        $this->_name = $name;
        $this->_wsAfter = $wsAfter;
    }

    public function whitespaceBefore(): String{
        return $this->_wsBefore;
    }

    public function name(): String{
        return $this->_name;
    }

    public function whitespaceAfter(): String{
        return $this->_wsAfter;
    }

    public function __toString(): String{
        return
            "@" .
            $this->_wsBefore .
            $this->_name .
            $this->_wsAfter .
            "{";
    }
}
