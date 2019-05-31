<?php declare(strict_types = 1);

namespace Sbludufunk\Randown\Evaluator\Classes\PublicConstructors;

use Iterator;
use IteratorAggregate;
use Sbludufunk\Randown\Evaluator\Classes\Objecto;

class BagClass extends Objecto implements IteratorAggregate
{
    /** @var Objecto[] */
    private $_storage;

    public function __construct(Objecto ...$elements){
        $this->_storage = $elements;
    }

    /** @return Objecto[] */
    public function toArray(): array{
        return $this->_storage;
    }

    public function count(): Int{
        return count($this->_storage);
    }

    public function getIterator(): Iterator{
        foreach($this->_storage as $element){
            yield $element;
        }
    }

    public function __toString(): String{
        return implode(", ", $this->_storage);
    }
}
