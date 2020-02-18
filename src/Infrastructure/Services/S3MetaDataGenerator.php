<?php

namespace PurpleBooth\MastodonDiagram\Infrastructure\Services;

interface S3MetaDataGenerator
{
    /**
     * @return array<string,string>
     */
    public function __invoke(): array;
}
