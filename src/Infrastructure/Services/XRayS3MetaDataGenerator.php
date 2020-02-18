<?php

namespace PurpleBooth\MastodonDiagram\Infrastructure\Services;

use Pkerrigan\Xray\Trace;

class XRayS3MetaDataGenerator implements S3MetaDataGenerator
{
    /**
     * @var Trace
     */
    private Trace $trace;

    public function __construct(Trace $trace)
    {
        $this->trace = $trace;
    }

    /**
     * @return array<string,string>
     */
    public function __invoke(): array
    {
        return [
            'x-amz-meta-trace-id' => $this->trace->getTraceId(),
            'x-amz-meta-trace-parent-id' => $this->trace->getId(),
        ];
    }
}
