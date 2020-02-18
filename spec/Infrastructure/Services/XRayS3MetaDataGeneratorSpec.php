<?php

namespace spec\PurpleBooth\MastodonDiagram\Infrastructure\Services;

use PhpSpec\ObjectBehavior;
use Pkerrigan\Xray\Trace;
use PurpleBooth\MastodonDiagram\Infrastructure\Services\XRayS3MetaDataGenerator;

class XRayS3MetaDataGeneratorSpec extends ObjectBehavior
{
    public function let(Trace $trace)
    {
        $this->beConstructedWith($trace);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(XRayS3MetaDataGenerator::class);
    }

    public function it_genenerates_an_array_with_parent_and_trace_id(Trace $trace)
    {
        $trace->getTraceId()->willReturn('trace');
        $trace->getId()->willReturn('parent');
        $this->__invoke()->shouldReturn([
            'x-amz-meta-trace-id' => 'trace',
            'x-amz-meta-trace-parent-id' => 'parent',
        ]);
    }
}
