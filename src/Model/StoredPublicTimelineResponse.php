<?php

namespace PurpleBooth\MastodonDiagram\Model;

use ArrayIterator;
use function count;
use Countable;
use Iterator;
use IteratorAggregate;
use JsonException;

/**
 * Class StoredPublicTimelineResponse.
 *
 * @implements \IteratorAggregate<int, StoredToot>
 */
class StoredPublicTimelineResponse implements IteratorAggregate, Countable
{
    private const JSON_MAX_DEPTH = 512;
    /**
     * @var array<StoredToot>
     */
    private $storedToots;
    /**
     * @var string
     */
    private $key;

    /**
     * StoredPublicTimelineResponse constructor.
     *
     * @param array<int, array<string, array<string,array<string,int|string>|int|string>|int|string>> $rawData
     */
    public function __construct(string $key, array $rawData)
    {
        $this->storedToots = array_map(
            function ($rawToot) {
                return new StoredToot($rawToot);
            },
            $rawData
        );
        $this->key = $key;
    }

    /**
     * @throws JsonException
     *
     * @return self<StoredToot>
     */
    public static function fromJson(string $key, string $jsonString): self
    {
        return new self($key, json_decode($jsonString, true, self::JSON_MAX_DEPTH, JSON_THROW_ON_ERROR));
    }

    public function count(): int
    {
        return \count($this->storedToots);
    }

    /**
     * @return array<StoredToot>
     */
    public function getStoredToots(): array
    {
        return $this->storedToots;
    }

    /**
     * @return Iterator<StoredToot>
     */
    public function getIterator(): Iterator
    {
        return new ArrayIterator($this->storedToots);
    }

    public function getKey(): string
    {
        return $this->key;
    }
}
