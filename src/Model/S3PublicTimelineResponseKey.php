<?php

namespace PurpleBooth\MastodonDiagram\Model;

use Bref\Event\S3\BucketObject;

class S3PublicTimelineResponseKey
{
    /**
     * @var string
     */
    private $key;

    public function __construct(string $key)
    {
        $this->key = $key;
    }

    public static function fromS3Record(BucketObject $bucketObject): self
    {
        return new self($bucketObject->getKey());
    }

    public function getKey(): string
    {
        return $this->key;
    }
}
