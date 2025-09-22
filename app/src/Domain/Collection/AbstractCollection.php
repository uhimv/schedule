<?php

declare(strict_types=1);

namespace App\Domain\Collection;

/**
 * @template T of object
 * @implements \IteratorAggregate<int, T>
 */
abstract class AbstractCollection implements \Countable, \IteratorAggregate
{
    protected array $items = [];

    public function __construct(array $items = [])
    {
        foreach ($items as $item) {
            $this->add($item);
        }
    }

    abstract public static function getTargetClass(): string;

    public function count(): int
    {
        return \count($this->items);
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->items);
    }

    public function add($item): void
    {
        $expectedClass = $this->getTargetClass();

        if (!$item instanceof $expectedClass) {
            throw new \InvalidArgumentException(\sprintf('expecting item to be %s, %s given', $expectedClass, $item::class));
        }

        $this->items[] = $item;
    }

    public function toJson()
    {
        return json_encode(
            $this->toArray(),
            JSON_THROW_ON_ERROR,
        );
    }

    public function toArray(): array
    {
        $array = [];
        foreach ($this->items as $item) {
            $array[] = $item->toArray();
        }

        return $array;
    }
}
