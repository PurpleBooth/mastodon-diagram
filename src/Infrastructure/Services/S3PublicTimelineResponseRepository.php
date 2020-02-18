<?php

namespace PurpleBooth\MastodonDiagram\Infrastructure\Services;

use Aws\S3\S3Client;
use JsonException;
use PurpleBooth\MastodonDiagram\Domain\Services\PublicTimelineResponseRepositoryInterface;
use PurpleBooth\MastodonDiagram\Model\PublicTimelineResponse;
use PurpleBooth\MastodonDiagram\Model\S3PublicTimelineResponseKey;
use PurpleBooth\MastodonDiagram\Model\StoredPublicTimelineResponse;
use PurpleBooth\MastodonDiagram\Model\StoredToot;

class S3PublicTimelineResponseRepository implements PublicTimelineResponseRepositoryInterface
{
    private const S3_BODY = 'Body';
    private const S3_KEY = 'Key';
    private const S3_BUCKET = 'Bucket';
    /**
     * @var string
     */
    private $bucket;
    /**
     * @var S3Client
     */
    private $s3Client;
    /**
     * @var S3MetaDataGenerator
     */
    private S3MetaDataGenerator $metaDataHelper;
    /**
     * @var PostRetrieveS3MetadataHook
     */
    private PostRetrieveS3MetadataHook $s3MetadataHook;

    public function __construct(string $bucket, S3Client $s3Client, S3MetaDataGenerator $metaDataGenerator, PostRetrieveS3MetadataHook $s3MetadataHook)
    {
        $this->bucket = $bucket;
        $this->s3Client = $s3Client;
        $this->metaDataHelper = $metaDataGenerator;
        $this->s3MetadataHook = $s3MetadataHook;
    }

    public function store(PublicTimelineResponse $toots): void
    {
        $key = $toots->getKey();

        $this->s3Client->upload(
            $this->bucket,
            $this->generateKey($key),
            $toots->getRawResponse(),
            'private',
            ['Metadata' => ($this->metaDataHelper)()]
        );
    }

    /**
     * @throws JsonException
     *
     * @return StoredPublicTimelineResponse<StoredToot>
     */
    public function retrieveTootStoredAggregate(
        S3PublicTimelineResponseKey $tootAggregateKey
    ): StoredPublicTimelineResponse {
        $response = $this->s3Client->getObject(
            [
                self::S3_BUCKET => $this->bucket,
                self::S3_KEY => $tootAggregateKey->getKey(),
            ]
        );
        ($this->s3MetadataHook)($response['Metadata']);

        $storedResponse = $response[self::S3_BODY];

        return StoredPublicTimelineResponse::fromJson($tootAggregateKey->getKey(), $storedResponse);
    }

    private function generateKey(string $key): string
    {
        $filteredKey = preg_replace('/[^a-z0-9.]/i', '', $key);

        $keyHash = crc32($key);

        return sprintf('%x-%s', $keyHash, $filteredKey);
    }
}
