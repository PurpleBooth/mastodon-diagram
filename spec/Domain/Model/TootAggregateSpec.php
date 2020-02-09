<?php

namespace spec\PurpleBooth\MastodonDiagram\Domain\Model;

use PhpSpec\ObjectBehavior;
use PurpleBooth\MastodonDiagram\Domain\Model\TootAggregate;
use PurpleBooth\MastodonDiagram\Domain\Model\TootAggregateInterface;

class TootAggregateSpec extends ObjectBehavior
{
    public function let(): void
    {
        $this->beConstructedWith('https://mastodon.social', 'example');
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(TootAggregate::class);
    }

    public function it_implements_the_interface(): void
    {
        $this->shouldImplement(TootAggregateInterface::class);
    }

    public function it_has_an_identifier_that_can_be_retrieved()
    {
        $this->getIdentifier()->shouldReturn('https://mastodon.social');
    }

    public function can_have_the_raw_response_returned()
    {
        $this->getRawResponse()->shouldReturn('example');
    }
}
