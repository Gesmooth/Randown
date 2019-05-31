<?php declare(strict_types = 1);

namespace Sbludufunk\Randown\Tokens;

class FunctionCallToken implements Token
{
    private $_wsBeforeAmp;

    private $_wsAfterAmp;

    public function __construct(String $wsBeforeAmp, String $wsAfterAmp){
        $this->_wsBeforeAmp = $wsBeforeAmp;
        $this->_wsAfterAmp = $wsAfterAmp;
    }

    public function whitespaceBeforeAmpersand(): String{
        return $this->_wsBeforeAmp;
    }

    public function whitespaceAfterAmpersand(): String{
        return $this->_wsAfterAmp;
    }

    public function __toString(): String{
        return $this->_wsBeforeAmp . "&" . $this->_wsAfterAmp . "{";
    }
}
