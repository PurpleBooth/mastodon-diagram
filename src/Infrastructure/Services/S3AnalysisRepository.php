<?php

namespace PurpleBooth\MastodonDiagram\Infrastructure\Services;

use Aws\S3\S3Client;
use PurpleBooth\MastodonDiagram\Domain\Services\AnalysisRepositoryInterface;
use PurpleBooth\MastodonDiagram\Model\TootAnalysis;

class S3AnalysisRepository implements AnalysisRepositoryInterface
{
    /**
     * @var S3Client
     */
    private S3Client $s3Client;
    private string $bucket;

    /**
     * S3AnalysisRepository constructor.
     */
    public function __construct(string $bucket, S3Client $s3Client)
    {
        $this->s3Client = $s3Client;
        $this->bucket = $bucket;
    }

    public function store(TootAnalysis $analysis): void
    {
        $key = $analysis->getKey();

        $this->s3Client->upload($this->bucket, $key, json_encode($analysis));
    }
}
