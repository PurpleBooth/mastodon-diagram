<?php

namespace PurpleBooth\MastodonDiagram\Model;

/**
 * The response from a request to a public timeline.
 */
class PublicTimelineResponse
{
    /**
     * @var string
     */
    private $key;
    /**
     * @var string
     */
    private $rawResponse;

    /**
     * Construct the class.
     */
    public function __construct(string $identifier, string $rawResponse)
    {
        $this->key = $identifier;
        $this->rawResponse = $rawResponse;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getRawResponse(): string
    {
        return $this->rawResponse;
    }
}
