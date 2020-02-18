<?php

namespace PurpleBooth\MastodonDiagram\Infrastructure\Services;

interface PostRetrieveS3MetadataHook
{
    /**
     * @param array<string,string> $metadata
     */
    public function __invoke(array $metadata): void;
}
