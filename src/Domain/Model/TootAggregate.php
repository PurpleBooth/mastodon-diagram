<?php

namespace PurpleBooth\MastodonDiagram\Domain\Model;

class TootAggregate implements TootAggregateInterface
{
    /**
     * @var string
     */
    private $identifier;
    /**
     * @var string
     */
    private $rawResponse;

    public function __construct(string $identifier, string $rawResponse)
    {
        $this->identifier = $identifier;
        $this->rawResponse = $rawResponse;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function getRawResponse(): string
    {
        return $this->rawResponse;
    }
}
