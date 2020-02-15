<?php

namespace PurpleBooth\MastodonDiagram\Domain\Services;

use PurpleBooth\MastodonDiagram\Model\TootAnalysis;

interface AnalysisRepositoryInterface
{
    public function store(TootAnalysis $argument1): void;
}
