<?php

namespace spec\PurpleBooth\MastodonDiagram\Infrastructure\Services;

use PhpSpec\ObjectBehavior;
use Pkerrigan\Xray\HttpSegment;
use Pkerrigan\Xray\Segment;
use Pkerrigan\Xray\Trace;
use Prophecy\Argument;
use PurpleBooth\MastodonDiagram\Infrastructure\Services\XRayHttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\HttpClient\ResponseStreamInterface;

class XRayHttpClientSpec extends ObjectBehavior
{
    public function let(Trace $trace, HttpClientInterface $httpClient, HttpSegment $segment)
    {
        $trace->getCurrentSegment()->willReturn($segment);
        $this->beConstructedWith($trace, $httpClient);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(XRayHttpClient::class);
    }

    public function it_proxies_through_to_the_http_client_for_requests(Trace $trace, Segment $segment, HttpClientInterface $httpClient, ResponseInterface $response)
    {
        $response->getStatusCode()->willReturn(407);
        $segment->addSubsegment(Argument::that(function (HttpSegment $actual) {
            $actualJson = $actual->jsonSerialize();

            if ('http://example.com' !== $actualJson['http']['request']['url']) {
                return false;
            }

            if ('GET' !== $actualJson['http']['request']['method']) {
                return false;
            }

            if (407 !== $actualJson['http']['response']['status']) {
                return false;
            }

            if (!\array_key_exists('start_time', $actualJson)) {
                return false;
            }
            if (!\array_key_exists('end_time', $actualJson)) {
                return false;
            }

            return true;
        }))->shouldBeCalled();
        $httpClient->request('GET', 'http://example.com', ['options' => 1])->willReturn($response);
        $this->request('GET', 'http://example.com', ['options' => 1])->shouldReturn($response);
    }

    public function it_proxies_through_to_the_http_client_for_streams(Trace $trace, HttpClientInterface $httpClient, ResponseInterface $response, ResponseStreamInterface $responseStream)
    {
        $httpClient->stream([$response], 10)->willReturn($responseStream);
        $this->stream([$response], 10)->shouldReturn($responseStream);
    }
}
