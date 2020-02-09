<?php

namespace spec\PurpleBooth\MastodonDiagram\Infrastructure\Services;

use Aws\S3\S3Client;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use PurpleBooth\MastodonDiagram\Domain\Model\TootAggregate;
use PurpleBooth\MastodonDiagram\Domain\Services\StorageInterface;
use PurpleBooth\MastodonDiagram\Infrastructure\Services\S3Storage;

class S3StorageSpec extends ObjectBehavior
{
    public function let(S3Client $s3Client)
    {
        $this->beConstructedWith('some-bucket', $s3Client);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(S3Storage::class);
    }

    public function it_implements_the_interface(): void
    {
        $this->shouldImplement(StorageInterface::class);
    }

    public function it_uploads_to_s3(S3Client $s3Client): void
    {
        $s3Client->upload('some-bucket', Argument::type('string'), 'testing')
            ->shouldBeCalled()
        ;
        $this->upload(new TootAggregate('https://mastodon.social', 'testing'));
    }

    public function it_escapes_the_name_of_the_bucket_and_keeps_it_readable(S3Client $s3Client): void
    {
        $s3Client->upload('some-bucket', '7161ef05-httpsmastodon.social', 'testing')
            ->shouldBeCalled()
        ;
        $this->upload(new TootAggregate('https://mastodon.social', 'testing'));
    }
}
