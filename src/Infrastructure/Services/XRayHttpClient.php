<?php

namespace PurpleBooth\MastodonDiagram\Infrastructure\Services;

use Pkerrigan\Xray\HttpSegment;
use Pkerrigan\Xray\Trace;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\HttpClient\ResponseStreamInterface;

class XRayHttpClient implements HttpClientInterface
{
    /**
     * @var HttpClientInterface
     */
    private HttpClientInterface $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Requests an HTTP resource.
     *
     * Responses MUST be lazy, but their status code MUST be
     * checked even if none of their public methods are called.
     *
     * Implementations are not required to support all options described above; they can also
     * support more custom options; but in any case, they MUST throw a TransportExceptionInterface
     * when an unsupported option is passed.
     *
     * @param array<mixed> $options Type depends on decorated class
     *
     * @throws TransportExceptionInterface When an unsupported option is passed
     */
    public function request(string $method, string $url, array $options = []): ResponseInterface
    {
        $segment = (new HttpSegment())
            ->setUrl($url)
            ->setMethod($method)
            ->begin();

        Trace::getInstance()
            ->getCurrentSegment()
            ->addSubsegment(
                $segment
            );

        $response = $this->httpClient->request($method, $url, $options);

        $segment->setResponseCode($response->getStatusCode())
            ->end();

        return $response;
    }

    /**
     * Yields responses chunk by chunk as they complete.
     *
     * @param iterable|ResponseInterface|ResponseInterface[] $responses One or more responses created by the current HTTP client
     * @param null|float $timeout The idle timeout before yielding timeout chunks
     */
    public function stream($responses, float $timeout = null): ResponseStreamInterface
    {
        return $this->httpClient->stream($responses, $timeout);
    }
}
