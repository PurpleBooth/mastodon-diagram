<?php

namespace PurpleBooth\MastodonDiagram\Domain\Services;

use PurpleBooth\MastodonDiagram\Model\PublicTimelineResponse;

interface TootRepositoryInterface
{
    public function retrievePublicTimeline(): PublicTimelineResponse;
}
