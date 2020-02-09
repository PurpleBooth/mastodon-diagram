<?php

namespace PurpleBooth\MastodonDiagram\Infrastructure\Services;

use Aws\S3\S3ClientInterface;
use PurpleBooth\MastodonDiagram\Domain\Model\TootAggregateInterface;
use PurpleBooth\MastodonDiagram\Domain\Services\StorageInterface;

class S3Storage implements StorageInterface
{
    /**
     * @var string
     */
    private $bucket;
    /**
     * @var S3ClientInterface
     */
    private $s3Client;

    public function __construct(string $bucket, S3ClientInterface $s3Client)
    {
        $this->bucket = $bucket;
        $this->s3Client = $s3Client;
    }

    public function upload(TootAggregateInterface $toots): void
    {
        $key = $toots->getIdentifier();

        $this->s3Client->upload($this->bucket, $this->generateKey($key), $toots->getRawResponse());
    }

    private function generateKey(string $key): string
    {
        $filteredKey = preg_replace('/[^a-z0-9\\.]/i', '', $key);

        $keyHash = crc32($key);

        return sprintf('%x-%s', $keyHash, $filteredKey);
    }
}
