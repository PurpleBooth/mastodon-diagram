<?php

namespace spec\PurpleBooth\MastodonDiagram\Functions;

use Bref\Context\Context;
use PhpSpec\ObjectBehavior;
use Pkerrigan\Xray\Segment;
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
        $context = new Context('unused', 1234, 'unused', 'trace');
        $trace->setTraceHeader('trace')->willReturn($trace);
        $trace->addSubsegment(Argument::that(function (Segment $subSegment) {
            $serialised = $subSegment->jsonSerialize();

            if ('name' !== $serialised['name']) {
                return false;
            }

            if (!\array_key_exists('start_time', $serialised)) {
                return false;
            }
            if (!\array_key_exists('end_time', $serialised)) {
                return false;
            }

            return  true;
        }))->shouldBeCalled()->willReturn($trace);

        $trace->submit(Argument::type(DaemonSegmentSubmitter::class))->shouldBeCalled();
        $this->__invoke('anything', $context)->shouldReturn('hello');
    }

    public function it_should_catch_and_rethrow_errors(Trace $trace)
    {
        $context = new Context('unused', 1234, 'unused', 'trace');
        $runTimeException = new \RuntimeException('Test');
        $this->beConstructedWith($trace, 'name', function () use ($runTimeException) {
            throw $runTimeException;
        });

        $trace->setTraceHeader('trace')->willReturn($trace);
        $trace->addSubsegment(Argument::that(function (Segment $subSegment) {
            $serialised = $subSegment->jsonSerialize();

            if ('name' !== $serialised['name']) {
                return false;
            }

            if (!\array_key_exists('fault', $serialised)) {
                return false;
            }
            if (true !== $serialised['fault']) {
                return false;
            }
            if (!\array_key_exists('start_time', $serialised)) {
                return false;
            }
            if (!\array_key_exists('end_time', $serialised)) {
                return false;
            }

            return true;
        }))->shouldBeCalled()->willReturn($trace);
        $trace->end()->willReturn($trace);
        $trace->begin(100)->willReturn($trace);
        $trace->submit(Argument::type(DaemonSegmentSubmitter::class))->shouldBeCalled();

        $this->shouldThrow(new \Exception($runTimeException->getMessage(), $runTimeException->getCode(), $runTimeException))->during__invoke('anything', $context);
    }
}
