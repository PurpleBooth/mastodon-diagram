<?php

namespace PurpleBooth\MastodonDiagram\Functions;

use Bref\Context\Context;
use Exception;
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
        $this->startTrace($context->getTraceId());
        $errorState = null;
        $rethrownException = null;

        try {
            $results = ($this->function)($event, $context);
        } catch (Exception $exception) {
            $this->endTrace(true);

            throw new Exception($exception->getMessage(), $exception->getCode(), $exception);
        }

        $this->endTrace(false);

        return $results;
    }

    private function startTrace(string $getTraceId): void
    {
        $trace = $this->trace
            ->setTraceHeader($getTraceId)
            ->setName($this->name)
        ;

        if ($this->hasRequestUri()) {
            $trace = $this->addRequestUri($trace);
        }

        if ($this->hasRequestMethod()) {
            $trace = $this->addRequestMethod($trace);
        }

        $trace->begin(100);
    }

    private function hasRequestUri(): bool
    {
        return \array_key_exists('REQUEST_URI', $_SERVER);
    }

    private function addRequestUri(Trace $trace): Trace
    {
        $trace = $trace
            ->setUrl($_SERVER['REQUEST_URI'])
        ;

        return $trace;
    }

    private function hasRequestMethod(): bool
    {
        return \array_key_exists('REQUEST_METHOD', $_SERVER);
    }

    private function addRequestMethod(Trace $trace): Trace
    {
        $trace = $trace
            ->setMethod($_SERVER['REQUEST_METHOD'])
        ;

        return $trace;
    }

    private function endTrace(bool $error): void
    {
        $trace = $this->trace
            ->end()
            ->setFault($error)
        ;

        $statusCode = $this->getStatusCode();

        if ($statusCode) {
            $trace = $trace
                ->setResponseCode($statusCode)
            ;
        }

        $trace
            ->submit(new DaemonSegmentSubmitter())
        ;
    }

    private function getStatusCode(): ?int
    {
        $statusCode = http_response_code();
        if (\is_bool($statusCode)) {
            return null;
        }

        return $statusCode;
    }
}
