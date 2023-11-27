<?php

namespace MorningTrain\Economic\Classes;

use Illuminate\Support\LazyCollection;

/**
 * @template TKey of array-key
 * @template TValue
 *
 * @implements LazyCollection<TKey, TValue>
 */
class EconomicCollection extends LazyCollection
{
    /**
     * Create a new lazy collection instance.
     *
     * @param  \Illuminate\Contracts\Support\Arrayable<TKey, TValue>|iterable<TKey, TValue>|(Closure(): \Generator<TKey, TValue, mixed, void>)|self<TKey, TValue>|array<TKey, TValue>|null  $source
     * @return void
     */
    public function __construct($source = null)
    {
        if(is_a($source, EconomicCollectionIterator::class)) {
            $this->source = $source;

            return;
        }

        parent::__construct($source);
    }

    protected function makeIterator($source)
    {
        if(is_a($source, EconomicCollectionIterator::class)) {
            return $source;
        }

        return parent::makeIterator($source);
    }


    public function getResourceClass(): string
    {
        return $this->resourceClass;
    }

    public function getSelf(): ?string
    {
        if(is_a($this->source, EconomicCollectionIterator::class)) {
            return $this->source->getSelf();
        }

        return null;
    }
}
