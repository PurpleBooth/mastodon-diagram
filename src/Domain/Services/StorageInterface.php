<?php

namespace PurpleBooth\MastodonDiagram\Domain\Services;

use PurpleBooth\MastodonDiagram\Domain\Model\TootAggregateInterface;

interface StorageInterface
{
    public function upload(TootAggregateInterface $toots): void;
}
