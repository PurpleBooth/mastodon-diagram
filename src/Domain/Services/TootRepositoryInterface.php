<?php

namespace PurpleBooth\MastodonDiagram\Domain\Services;

use PurpleBooth\MastodonDiagram\Domain\Model\TootAggregateInterface;

interface TootRepositoryInterface
{
    public function retrievePublicTimeline(): TootAggregateInterface;
}
