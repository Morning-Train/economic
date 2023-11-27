<?php

namespace MorningTrain\Economic\Classes;

use MorningTrain\Economic\Abstracts\Resource;
use MorningTrain\Economic\Services\EconomicApiService;
use SeekableIterator;
use ArrayAccess;
use Countable;

class EconomicCollectionIterator implements SeekableIterator, ArrayAccess, Countable
{
    protected int $itemCount;
    protected array $items = [];
    protected int $pageSize = 20;

    protected int $currentPosition = 0;

    public function __construct(protected string $self, protected string $resourceClass)
    {
    }

    protected function getItem(int $position): ?Resource
    {
        if(isset($this->items[$position])) {
            return $this->items[$position];
        }

        if(isset($this->itemCount) && $position >= $this->itemCount) {
            return null;
        }

        $this->fetchItems($position);

        if(!isset($this->items[$position])) {
            return null;
        }

        return $this->items[$position];
    }

    protected function fetchItems(int $position): void
    {
        $skipPages = (int) floor($position/$this->pageSize);

        $response = EconomicApiService::get($this->self, [
            'skipPages' => $skipPages,
            'pageSize' => $this->pageSize
        ]);

        if($response->getStatusCode() !== 200) {
            // TODO: log error and throw exception

            return;
        }

        $offset = $this->pageSize * $skipPages;

        foreach($response->getProperty('collection') as $key => $item) {
            $this->items[$key + $offset] = new $this->resourceClass($item);
        }

        ksort($this->items);

        $pagination = $response->getProperty('pagination');

        if(isset($pagination['results'])) {
            $this->itemCount = $pagination['results'];
        } else {
            $this->itemCount = count($this->items);
        }
    }

    public function current(): mixed
    {
        return $this->getItem($this->currentPosition);
    }

    public function next(): void
    {
        $this->currentPosition++;
    }

    public function key(): mixed
    {
        return $this->currentPosition;
    }

    public function valid(): bool
    {
        if(!isset($this->itemCount)) {
            $this->fetchItems($this->currentPosition);
        }

        return $this->currentPosition >= 0 && $this->currentPosition < $this->itemCount;
    }

    public function rewind(): void
    {
        $this->currentPosition = 0;
    }

    public function offsetExists(mixed $offset): bool
    {
        return !is_null($this->getItem($offset));
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->getItem($offset);
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        // TODO: Implement offsetSet() method.
    }

    public function offsetUnset(mixed $offset): void
    {
        // TODO: Implement offsetUnset() method.
    }

    public function count(): int
    {
        return $this->itemCount;
    }

    public function seek(int $offset): void
    {
        $this->currentPosition = $offset;
    }

    public function getSelf(): string
    {
        return $this->self;
    }
}
