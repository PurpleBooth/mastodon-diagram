<?php

namespace PurpleBooth\MastodonDiagram\Infrastructure\Services;

use Aws\S3\S3Client;
use PurpleBooth\MastodonDiagram\Domain\Services\PublicTimelineResponseRepositoryInterface;
use PurpleBooth\MastodonDiagram\Model\PublicTimelineResponse;
use PurpleBooth\MastodonDiagram\Model\S3PublicTimelineResponseKey;
use PurpleBooth\MastodonDiagram\Model\StoredPublicTimelineResponse;
use PurpleBooth\MastodonDiagram\Model\StoredToot;

class S3PublicTimelineResponseRepository implements PublicTimelineResponseRepositoryInterface
{
    /**
     * @var string
     */
    private $bucket;
    /**
     * @var S3Client
     */
    private $s3Client;

    public function __construct(string $bucket, S3Client $s3Client)
    {
        $this->bucket = $bucket;
        $this->s3Client = $s3Client;
    }

    public function store(PublicTimelineResponse $toots): void
    {
        $key = $toots->getKey();

        $this->s3Client->upload($this->bucket, $this->generateKey($key), $toots->getRawResponse());
    }

    /**
     * @throws \JsonException
     *
     * @return StoredPublicTimelineResponse<StoredToot>
     */
    public function retrieveTootStoredAggregate(S3PublicTimelineResponseKey $tootAggregateKey): StoredPublicTimelineResponse
    {
        $response = $this->s3Client->getObject(['Bucket' => $this->bucket, 'Key' => $tootAggregateKey->getKey()]);

        return StoredPublicTimelineResponse::fromJson($tootAggregateKey->getKey(), $response['body']);
    }

    private function generateKey(string $key): string
    {
        $filteredKey = preg_replace('/[^a-z0-9.]/i', '', $key);

        $keyHash = crc32($key);

        return sprintf('%x-%s', $keyHash, $filteredKey);
    }
}
