<?php

namespace PurpleBooth\MastodonDiagram\Domain\Services;

use PurpleBooth\MastodonDiagram\Model\StoredPublicTimelineResponse;
use PurpleBooth\MastodonDiagram\Model\TootAnalysis;

class TootAnalyser implements TootAnalyserInterface
{
    public function analyse(StoredPublicTimelineResponse $tootAggregate): TootAnalysis
    {
        $analysed = [];

        foreach ($tootAggregate as $item) {
            $host = $item->getHost();
            if (!\array_key_exists($host, $analysed)) {
                $analysed[$host] = 0;
            }

            ++$analysed[$host];
        }

        return new TootAnalysis($tootAggregate->getKey(), $analysed);
    }
}
