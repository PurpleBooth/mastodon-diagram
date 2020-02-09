<?php

namespace PurpleBooth\MastodonDiagram\Infrastructure\Services;

use PurpleBooth\MastodonDiagram\Domain\Model\TootAggregate;
use PurpleBooth\MastodonDiagram\Domain\Model\TootAggregateInterface;
use PurpleBooth\MastodonDiagram\Domain\Services\TootRepositoryInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiTootRepository implements TootRepositoryInterface
{
    const API_V_1_TIMELINES_PUBLIC_ONLY = '/api/v1/timelines/public?only_media=false';
    /**
     * @var HttpClientInterface
     */
    private $httpClient;
    /**
     * @var string
     */
    private $host;

    public function __construct(string $host, HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
        $this->host = $host;
    }

    public function retrievePublicTimeline(): TootAggregateInterface
    {
        return new TootAggregate(
            $this->host,
            $this
                ->httpClient
                ->request('GET', sprintf('%s%s', $this->host, static::API_V_1_TIMELINES_PUBLIC_ONLY))
                ->getContent()
        );
    }
}
