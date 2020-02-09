<?php

namespace spec\PurpleBooth\MastodonDiagram\Functions;

use PhpSpec\ObjectBehavior;
use PurpleBooth\MastodonDiagram\Domain\Model\TootAggregate;
use PurpleBooth\MastodonDiagram\Functions\Poll;
use PurpleBooth\MastodonDiagram\Infrastructure\Services\ApiTootRepository;
use PurpleBooth\MastodonDiagram\Infrastructure\Services\S3Storage;

class PollSpec extends ObjectBehavior
{
    public function let(S3Storage $storage, ApiTootRepository $tootRepository, TootAggregate $tootAggregate): void
    {
        $this->beConstructedWith($storage, $tootRepository);
        $tootRepository->retrievePublicTimeline()->willReturn($tootAggregate);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(Poll::class);
    }

    public function it_trigger_a_poll_by_invoking(): void
    {
        $this->__invoke()->shouldReturn('polled');
    }

    public function it_writes_the_response_to_s3(S3Storage $storage, ApiTootRepository $tootRepository, TootAggregate $tootAggregate): void
    {
        $tootRepository->retrievePublicTimeline()->willReturn($tootAggregate);

        $storage->upload($tootAggregate)->shouldBeCalled();
        $this->__invoke()->shouldReturn('polled');
    }
}
