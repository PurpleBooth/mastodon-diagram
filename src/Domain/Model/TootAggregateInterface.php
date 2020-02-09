<?php

namespace PurpleBooth\MastodonDiagram\Domain\Model;

interface TootAggregateInterface
{
    public function getRawResponse(): string;

    public function getIdentifier(): string;
}
