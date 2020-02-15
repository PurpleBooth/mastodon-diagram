<?php

namespace PurpleBooth\MastodonDiagram\Domain\Services;

use PurpleBooth\MastodonDiagram\Model\PublicTimelineResponse;
use PurpleBooth\MastodonDiagram\Model\S3PublicTimelineResponseKey;
use PurpleBooth\MastodonDiagram\Model\StoredPublicTimelineResponse;
use PurpleBooth\MastodonDiagram\Model\StoredToot;

interface PublicTimelineResponseRepositoryInterface
{
    public function store(PublicTimelineResponse $toots): void;

    /**
     * @return StoredPublicTimelineResponse<StoredToot>
     */
    public function retrieveTootStoredAggregate(
        S3PublicTimelineResponseKey $tootAggregateKey
    ): StoredPublicTimelineResponse;
}
