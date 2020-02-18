<?php

namespace PurpleBooth\MastodonDiagram\Functions;

use Bref\Context\Context;
use Exception;
use Pkerrigan\Xray\Segment;
use Pkerrigan\Xray\Submission\DaemonSegmentSubmitter;
use Pkerrigan\Xray\Trace;

class XRayFunctionDecorator
{
    /**
     * @var callable
     */
    private $function;
    private string $name;
    /**
     * @var Trace
     */
    private Trace $trace;

    /**
     * XRayFunctionDecorator constructor.
     */
    public function __construct(Trace $trace, string $name, callable $function)
    {
        $this->function = $function;
        $this->name = $name;
        $this->trace = $trace;
    }

    /**
     * @param mixed $event
     *
     * @throws Exception
     */
    public function __invoke($event, Context $context): ?string
    {
        $trace = $this->trace
            ->setTraceHeader($context->getTraceId())
        ;

        $segment = new Segment();
        $segment->setName($this->name);

        $segment->begin();

        try {
            $results = ($this->function)($event, $context);
        } catch (Exception $exception) {
            $segment->setFault(true);

            throw new Exception($exception->getMessage(), $exception->getCode(), $exception);
        } finally {
            $segment->end();

            $trace->addSubsegment($segment);
            $trace
                ->submit(new DaemonSegmentSubmitter())
            ;
        }

        return $results;
    }
}
