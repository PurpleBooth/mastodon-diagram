<?php

namespace spec\PurpleBooth\MastodonDiagram\Infrastructure\Services;

use PhpSpec\ObjectBehavior;
use PurpleBooth\MastodonDiagram\Domain\Services\TootRepositoryInterface;
use PurpleBooth\MastodonDiagram\Infrastructure\Services\ApiTootRepository;
use PurpleBooth\MastodonDiagram\Model\PublicTimelineResponse;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class ApiTootRepositorySpec extends ObjectBehavior
{
    public function let(MockHttpClient $client): void
    {
        $this->beConstructedWith('https://mastodon.social', $client);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(ApiTootRepository::class);
    }

    public function it_implements_the_interface(): void
    {
        $this->shouldImplement(TootRepositoryInterface::class);
    }

    public function it_should_get_the_public_timeline(MockHttpClient $client, MockResponse $response): void
    {
        $client->request(
            'GET',
            'https://mastodon.social/api/v1/timelines/public?only_media=false'
        )->willReturn(
            $response
        );
        $response->getContent()->willReturn('source');
        $tootAggregate = new PublicTimelineResponse('https://mastodon.social', 'source');
        $this->retrievePublicTimeline()->shouldBeLike($tootAggregate);
    }
}
