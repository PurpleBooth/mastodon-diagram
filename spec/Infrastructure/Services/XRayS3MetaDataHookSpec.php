<?php

namespace spec\PurpleBooth\MastodonDiagram\Infrastructure\Services;

use PhpSpec\ObjectBehavior;
use Pkerrigan\Xray\Trace;
use PurpleBooth\MastodonDiagram\Infrastructure\Services\XRayS3MetaDataHook;

class XRayS3MetaDataHookSpec extends ObjectBehavior
{
    public function let(Trace $trace)
    {
        $this->beConstructedWith($trace);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(XRayS3MetaDataHook::class);
    }

    public function it_should_set_the_trace_id_from_the_meta_data(Trace $trace)
    {
        $trace->setTraceId('test')->shouldBeCalled();
        $this->__invoke(['x-amz-meta-trace-id' => 'test']);
    }

    public function it_should_set_the_trace_id_and_parent_id_from_the_meta_data(Trace $trace)
    {
        $trace->setTraceId('test')->shouldBeCalled();
        $trace->setParentId('parent')->shouldBeCalled();
        $this->__invoke([
            'x-amz-meta-trace-id' => 'test',
            'x-amz-meta-trace-parent-id' => 'parent',
        ]);
    }
}
