<?php

namespace PurpleBooth\MastodonDiagram\Functions;

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
     * XRayFunctionDecorator constructor.
     */
    public function __construct(string $name, callable $function)
    {
        $this->function = $function;
        $this->name = $name;
    }

    /**
     * @param array<mixed> ...$args
     */
    public function __invoke(...$args): ?string
    {
        $this->startTrace();
        $errorState = null;
        $rethrownException = null;

        try {
            $results = ($this->function)(...$args);
        } catch (Exception $exception) {
            $this->endTrace(true);

            throw new Exception($exception->getMessage(), $exception->getCode(), $exception);
        }

        $this->endTrace(false);

        return $results;
    }

    private function startTrace(): void
    {
        $trace = Trace::getInstance()
            ->setTraceHeader($this->getTraceId())
            ->setName($this->name)
        ;

        if ($this->hasRequestUri()) {
            $trace = $this->addRequestUri($trace);
        }

        if ($this->hasRequestMethod()) {
            $trace = $this->addRequestMethod($trace);
        }

        $trace->begin();
    }

    private function getTraceId(): ?string
    {
        $traceId = $_SERVER['HTTP_X_AMZN_TRACE_ID'] ?? null;

        return $traceId;
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
        $trace = Trace::getInstance()
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
