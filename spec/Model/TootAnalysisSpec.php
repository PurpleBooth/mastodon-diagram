<?php

namespace spec\PurpleBooth\MastodonDiagram\Model;

use JsonSerializable;
use PhpSpec\ObjectBehavior;
use PurpleBooth\MastodonDiagram\Model\TootAnalysis;

class TootAnalysisSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith('memorable-id', ['testing.com' => 45, 'example.net' => 775]);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(TootAnalysis::class);
    }

    public function its_serialisable()
    {
        $this->shouldHaveType(JsonSerializable::class);
    }

    public function it_has_an_key()
    {
        $this->getKey()->shouldReturn('memorable-id');
    }

    public function it_is_serialisable()
    {
        $this->shouldImplement(JsonSerializable::class);
    }

    public function it_only_serialises_the_array()
    {
        $this->jsonSerialize()->shouldReturn(['testing.com' => 45, 'example.net' => 775]);
    }
}
