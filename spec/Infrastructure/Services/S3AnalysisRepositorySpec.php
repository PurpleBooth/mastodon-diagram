<?php

namespace spec\PurpleBooth\MastodonDiagram\Infrastructure\Services;

use Aws\S3\S3Client;
use PhpSpec\ObjectBehavior;
use PurpleBooth\MastodonDiagram\Domain\Services\AnalysisRepositoryInterface;
use PurpleBooth\MastodonDiagram\Infrastructure\Services\S3AnalysisRepository;
use PurpleBooth\MastodonDiagram\Model\TootAnalysis;

class S3AnalysisRepositorySpec extends ObjectBehavior
{
    public function let(S3Client $s3Client)
    {
        $this->beConstructedWith('some-bucket', $s3Client);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(S3AnalysisRepository::class);
    }

    public function it_implements_the_interface()
    {
        $this->shouldImplement(AnalysisRepositoryInterface::class);
    }

    public function it_creates_a_nice_unique_slug(S3Client $s3Client): void
    {
        $s3Client->upload('some-bucket', 'public/7161ef05-httpsmastodon.social', '{"testing":2}')
            ->shouldBeCalled()
        ;
        $this->store(new TootAnalysis('7161ef05-httpsmastodon.social', ['testing' => 2]));
    }
}
