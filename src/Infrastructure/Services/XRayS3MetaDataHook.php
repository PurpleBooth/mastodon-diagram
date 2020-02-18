<?php

namespace PurpleBooth\MastodonDiagram\Infrastructure\Services;

use Pkerrigan\Xray\Trace;

class XRayS3MetaDataHook implements PostRetrieveS3MetadataHook
{
    private Trace $trace;

    public function __construct(Trace $trace)
    {
        $this->trace = $trace;
    }

    /**
     * @param array<string,string> $metadata
     */
    public function __invoke(array $metadata): void
    {
        if (!\array_key_exists('x-amz-meta-trace-id', $metadata)) {
            return;
        }

        if (\array_key_exists('x-amz-meta-trace-parent-id', $metadata)) {
            $this->trace->setParentId($metadata['x-amz-meta-trace-parent-id']);
        }

        $this->trace->setTraceId($metadata['x-amz-meta-trace-id']);
    }
}
