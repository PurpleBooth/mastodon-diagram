<?php

namespace spec\PurpleBooth\MastodonDiagram\Functions;

use PhpSpec\ObjectBehavior;
use PurpleBooth\MastodonDiagram\Domain\Services\AnalysisRepositoryInterface;
use PurpleBooth\MastodonDiagram\Domain\Services\PublicTimelineResponseRepositoryInterface;
use PurpleBooth\MastodonDiagram\Domain\Services\TootAnalyserInterface;
use PurpleBooth\MastodonDiagram\Functions\CountInteractionsFunction;
use PurpleBooth\MastodonDiagram\Model\S3PublicTimelineResponseKey;
use PurpleBooth\MastodonDiagram\Model\StoredPublicTimelineResponse;
use PurpleBooth\MastodonDiagram\Model\TootAnalysis;

class CountInteractionsFunctionSpec extends ObjectBehavior
{
    public function let(
        PublicTimelineResponseRepositoryInterface $timelineRepository,
        TootAnalyserInterface $tootAnalyser,
        AnalysisRepositoryInterface $analysisRepository
    ): void {
        $this->beConstructedWith($timelineRepository, $tootAnalyser, $analysisRepository);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(CountInteractionsFunction::class);
    }

    public function it_counts_the_number_of_interactions_in_a_response(
        StoredPublicTimelineResponse $tootAggregate,
        PublicTimelineResponseRepositoryInterface $timelineRepository,
        TootAnalyserInterface $tootAnalyser,
        AnalysisRepositoryInterface $analysisRepository,
        TootAnalysis $tootAnalysis,
        S3PublicTimelineResponseKey $key
    ): void {
        $timelineRepository->retrieveTootStoredAggregate($key)->willReturn($tootAggregate);
        $tootAnalyser->analyse($tootAggregate)->willReturn($tootAnalysis);
        $analysisRepository->store($tootAnalysis)->shouldBeCalled();

        $this->__invoke($key);
    }
}
