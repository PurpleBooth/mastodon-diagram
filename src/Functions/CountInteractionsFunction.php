<?php

namespace PurpleBooth\MastodonDiagram\Functions;

use PurpleBooth\MastodonDiagram\Domain\Services\AnalysisRepositoryInterface;
use PurpleBooth\MastodonDiagram\Domain\Services\PublicTimelineResponseRepositoryInterface;
use PurpleBooth\MastodonDiagram\Domain\Services\TootAnalyserInterface;
use PurpleBooth\MastodonDiagram\Model\S3PublicTimelineResponseKey;

class CountInteractionsFunction
{
    /**
     * @var PublicTimelineResponseRepositoryInterface
     */
    private $timelineRepository;
    /**
     * @var TootAnalyserInterface
     */
    private $tootAnalyser;
    /**
     * @var AnalysisRepositoryInterface
     */
    private $analysisRepository;

    public function __construct(
        PublicTimelineResponseRepositoryInterface $timelineRepository,
        TootAnalyserInterface $tootAnalyser,
        AnalysisRepositoryInterface $analysisRepository
    ) {
        $this->timelineRepository = $timelineRepository;
        $this->tootAnalyser = $tootAnalyser;
        $this->analysisRepository = $analysisRepository;
    }

    public function __invoke(S3PublicTimelineResponseKey $key): void
    {
        $aggregate = $this->timelineRepository->retrieveTootStoredAggregate($key);
        $analysis = $this->tootAnalyser->analyse($aggregate);
        $this->analysisRepository->store($analysis);
    }
}
