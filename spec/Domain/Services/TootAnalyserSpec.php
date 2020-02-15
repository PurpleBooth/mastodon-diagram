<?php

namespace spec\PurpleBooth\MastodonDiagram\Domain\Services;

use ArrayIterator;
use PhpSpec\ObjectBehavior;
use PurpleBooth\MastodonDiagram\Domain\Services\TootAnalyser;
use PurpleBooth\MastodonDiagram\Domain\Services\TootAnalyserInterface;
use PurpleBooth\MastodonDiagram\Model\StoredPublicTimelineResponse;
use PurpleBooth\MastodonDiagram\Model\StoredToot;
use PurpleBooth\MastodonDiagram\Model\TootAnalysis;

class TootAnalyserSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(TootAnalyser::class);
    }

    public function it_implements_the_toot_analyser_interface()
    {
        $this->shouldImplement(TootAnalyserInterface::class);
    }

    public function it_gives_an_analysis_when_requested(StoredPublicTimelineResponse $tootAggregate)
    {
        $tootAggregate->getKey()->willReturn('key');
        $storedToot = new StoredToot(['url' => 'https://example.net']);
        $tootAggregate->getIterator()->willReturn(new ArrayIterator([$storedToot]));
        $this->analyse($tootAggregate)
            ->shouldReturnAnInstanceOf(TootAnalysis::class)
        ;
    }

    public function it_counts_the_number_of_toots_from_each_host(StoredPublicTimelineResponse $tootAggregate)
    {
        $tootAggregate->getKey()->willReturn('key');
        $storedToot1 = new StoredToot(['url' => 'https://best-friends.chat']);
        $storedToot2 = new StoredToot(['url' => 'https://quey.org']);
        $tootAggregate->getIterator()->willReturn(new ArrayIterator([$storedToot1, $storedToot2]));

        $this->analyse(
            $tootAggregate
        )->shouldBeLike(new TootAnalysis('key', [
            'best-friends.chat' => 1,
            'quey.org' => 1,
        ]));
    }
}
