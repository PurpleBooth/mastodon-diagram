<?php

namespace spec\PurpleBooth\MastodonDiagram\Functions;

use PhpSpec\ObjectBehavior;
use Pkerrigan\Xray\Submission\DaemonSegmentSubmitter;
use Pkerrigan\Xray\Trace;
use Prophecy\Argument;
use PurpleBooth\MastodonDiagram\Functions\XRayFunctionDecorator;

class XRayFunctionDecoratorSpec extends ObjectBehavior
{
    public function let(Trace $trace)
    {
        $this->beConstructedWith($trace, 'name', function () {
            return 'hello';
        });
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(XRayFunctionDecorator::class);
    }

    public function it_should_proxy_through_to_the_contained_function(Trace $trace)
    {
        $trace->setTraceHeader(null)->willReturn($trace);
        $trace->setName('name')->willReturn($trace);
        $trace->setFault(false)
            ->willReturn($trace)
        ;
        $trace->end()->willReturn($trace);
        $trace->begin()->willReturn($trace);
        $trace->submit(Argument::type(DaemonSegmentSubmitter::class))->shouldBeCalled();
        $trace->begin()->shouldBeCalled();
        $this->__invoke('anything', 'at', 'all')->shouldReturn('hello');
    }

    public function it_should_should_copy_the_trace_id_if_set(Trace $trace)
    {
        $_SERVER['HTTP_X_AMZN_TRACE_ID'] = 'testing';
        $trace->setTraceId('testing')->willReturn($trace);
        $trace->setTraceHeader('testing')->willReturn($trace);
        $trace->setName('name')->willReturn($trace);
        $trace->setFault(false)
            ->willReturn($trace)
        ;
        $trace->end()->willReturn($trace);
        $trace->begin()->willReturn($trace);
        $trace->submit(Argument::type(DaemonSegmentSubmitter::class))->shouldBeCalled();
        $this->__invoke('anything', 'at', 'all')->shouldReturn('hello');
        unset($_SERVER['HTTP_X_AMZN_TRACE_ID']);
    }

    public function it_should_catch_and_rethrow_errors(Trace $trace)
    {
        $runTimeException = new \RuntimeException('Test');
        $this->beConstructedWith($trace, 'name', function () use ($runTimeException) {
            throw $runTimeException;
        });

        $trace->setTraceHeader(null)->willReturn($trace);
        $trace->setName('name')->willReturn($trace);
        $trace->setFault(true)
            ->willReturn($trace)
        ;
        $trace->end()->willReturn($trace);
        $trace->begin()->willReturn($trace);
        $trace->submit(Argument::type(DaemonSegmentSubmitter::class))->shouldBeCalled();

        $this->shouldThrow(new \Exception($runTimeException->getMessage(), $runTimeException->getCode(), $runTimeException))->during__invoke();
    }
}
