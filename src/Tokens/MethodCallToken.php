<?php declare(strict_types = 1);

namespace Sbludufunk\Randown\Tokens;

class MethodCallToken implements Token
{
    private $_wsBeforeAmp;

    private $_wsBeforeName;

    private $_name;

    private $_wsAfterName;

    public function __construct(
        String $wsBeforeAmp,
        String $wsBeforeName,
        String $name,
        String $wsAfterName
    ){
        $this->_wsBeforeAmp = $wsBeforeAmp;
        $this->_wsBeforeName = $wsBeforeName;
        $this->_name = $name;
        $this->_wsAfterName = $wsAfterName;
    }

    public function whitespaceBeforeAmpersand(): String{
        return $this->_wsBeforeAmp;
    }

    public function whitespaceBeforeName(): String{
        return $this->_wsBeforeName;
    }

    public function name(): String{
        return $this->_name;
    }

    public function whitespaceAfterName(): String{
        return $this->_wsAfterName;
    }

    public function __toString(): String{
        return
            $this->_wsBeforeAmp .
            "&" .
            $this->_wsBeforeName .
            $this->_name .
            $this->_wsAfterName .
            "{";
    }
}
