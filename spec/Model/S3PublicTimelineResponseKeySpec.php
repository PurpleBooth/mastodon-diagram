<?php

namespace spec\PurpleBooth\MastodonDiagram\Model;

use Bref\Event\S3\BucketObject;
use PhpSpec\ObjectBehavior;
use PurpleBooth\MastodonDiagram\Model\S3PublicTimelineResponseKey;

class S3PublicTimelineResponseKeySpec extends ObjectBehavior
{
    const UNUSED = 244;

    public function let()
    {
        $this->beConstructedThrough('fromS3Record', [new BucketObject('file', self::UNUSED)]);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(S3PublicTimelineResponseKey::class);
    }

    public function it_has_a_key()
    {
        $this->getKey()->shouldReturn('file');
    }
}
