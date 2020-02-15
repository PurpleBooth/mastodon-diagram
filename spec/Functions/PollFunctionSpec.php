<?php

namespace spec\PurpleBooth\MastodonDiagram\Functions;

use PhpSpec\ObjectBehavior;
use PurpleBooth\MastodonDiagram\Functions\PollFunction;
use PurpleBooth\MastodonDiagram\Infrastructure\Services\ApiTootRepository;
use PurpleBooth\MastodonDiagram\Infrastructure\Services\S3PublicTimelineResponseRepository;
use PurpleBooth\MastodonDiagram\Model\PublicTimelineResponse;

class PollFunctionSpec extends ObjectBehavior
{
    public function let(S3PublicTimelineResponseRepository $storage, ApiTootRepository $tootRepository, PublicTimelineResponse $tootAggregate): void
    {
        $this->beConstructedWith($storage, $tootRepository);
        $tootRepository->retrievePublicTimeline()->willReturn($tootAggregate);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(PollFunction::class);
    }

    public function it_trigger_a_poll_by_invoking(): void
    {
        $this->__invoke()->shouldReturn('polled');
    }

    public function it_writes_the_response_to_s3(S3PublicTimelineResponseRepository $storage, ApiTootRepository $tootRepository, PublicTimelineResponse $tootAggregate): void
    {
        $tootRepository->retrievePublicTimeline()->willReturn($tootAggregate);

        $storage->store($tootAggregate)->shouldBeCalled();
        $this->__invoke()->shouldReturn('polled');
    }
}
