<?php declare(strict_types = 1);

namespace Sbludufunk\Randown\Evaluator\Intraclasses;

use Error;
use Iterator;
use IteratorAggregate;

class Bag implements Objecto, IteratorAggregate
{
    /** @var Objecto[] */
    private $_storage;

    public function __construct(Iterable $elements){
        $this->_storage = [];
        foreach($elements as $element){
            assert($element instanceof Objecto);
            $this->_storage[] = $element;
        }
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

    public function invoke(String $name, array $arguments): Objecto{
        throw new Error("Method $name does not exist");
    }

    public function __toString(): String{
        return implode(", ", $this->_storage);
    }
}
