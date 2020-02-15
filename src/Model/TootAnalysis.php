<?php

namespace PurpleBooth\MastodonDiagram\Model;

use JsonSerializable;

class TootAnalysis implements JsonSerializable
{
    /**
     * @var array<string,int>
     */
    private array $hosts;
    /**
     * @var string
     */
    private string $key;

    /**
     * TootAnalysis constructor.
     *
     * @param array<string,int> $hosts
     */
    public function __construct(string $key, array $hosts)
    {
        $this->hosts = $hosts;
        $this->key = $key;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return array<string, int>
     */
    public function jsonSerialize(): array
    {
        return $this->hosts;
    }
}
