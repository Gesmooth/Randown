<?php declare(strict_types = 1);

namespace Sbludufunk\Randown\Evaluator;

class Bag
{
    private $_storage;

    public function __construct(Iterable $elements){
        $this->_storage = [];
        foreach($elements as $element){
            $this->_storage[] = $element;
        }
    }
}
