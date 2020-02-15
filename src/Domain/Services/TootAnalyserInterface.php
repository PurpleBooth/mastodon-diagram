<?php

namespace PurpleBooth\MastodonDiagram\Domain\Services;

use PurpleBooth\MastodonDiagram\Model\StoredPublicTimelineResponse;
use PurpleBooth\MastodonDiagram\Model\StoredToot;
use PurpleBooth\MastodonDiagram\Model\TootAnalysis;

interface TootAnalyserInterface
{
    /**
     * @param StoredPublicTimelineResponse<StoredToot> $tootAggregate
     */
    public function analyse(StoredPublicTimelineResponse $tootAggregate): TootAnalysis;
}
