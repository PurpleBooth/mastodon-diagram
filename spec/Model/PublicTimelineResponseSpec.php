<?php

namespace spec\PurpleBooth\MastodonDiagram\Model;

use PhpSpec\ObjectBehavior;
use PurpleBooth\MastodonDiagram\Model\PublicTimelineResponse;

class PublicTimelineResponseSpec extends ObjectBehavior
{
    public function let(): void
    {
        $this->beConstructedWith('https://mastodon.social', 'example');
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(PublicTimelineResponse::class);
    }

    public function it_has_an_identifier_that_can_be_retrieved()
    {
        $this->getKey()->shouldReturn('https://mastodon.social');
    }

    public function can_have_the_raw_response_returned()
    {
        $this->getRawResponse()->shouldReturn('example');
    }
}
