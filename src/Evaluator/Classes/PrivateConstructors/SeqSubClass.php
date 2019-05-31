<?php declare(strict_types = 1);

namespace Sbludufunk\Randown\Evaluator\Classes\PrivateConstructors;

use Sbludufunk\Randown\Evaluator\Classes\Objecto;
use Sbludufunk\Randown\Evaluator\Classes\PublicConstructors\SeqClass;
use function array_slice;

class SeqSubClass extends Objecto
{
    private $_index;

    private $_count;

    private $_storage;

    public function __construct(IntClass $index, IntClass $count, Objecto ...$objects){
        $this->_index = $index;
        $this->_count = $count;
        $this->_storage = [];
        foreach($objects as $object){
            $this->_storage[] = $object;
        }
    }

    public function __toString(): String{
        return (String)$this->finalize();
    }

    public function staticize(): Objecto{
        $startIndex = (Int)(String)$this->_index;
        $count = (Int)(String)$this->_count;
        return new SeqClass(...array_slice($this->_storage, $startIndex, $count));
    }

    public function invoke(String $name, Objecto ...$arguments): Objecto{
        return $this->finalize()->invoke($name, $arguments);
    }
}
