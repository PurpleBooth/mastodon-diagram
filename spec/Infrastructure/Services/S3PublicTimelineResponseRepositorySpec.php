<?php

namespace spec\PurpleBooth\MastodonDiagram\Infrastructure\Services;

use Aws\S3\S3Client;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use PurpleBooth\MastodonDiagram\Domain\Services\PublicTimelineResponseRepositoryInterface;
use PurpleBooth\MastodonDiagram\Infrastructure\Services\S3PublicTimelineResponseRepository;
use PurpleBooth\MastodonDiagram\Model\PublicTimelineResponse;
use PurpleBooth\MastodonDiagram\Model\S3PublicTimelineResponseKey;
use PurpleBooth\MastodonDiagram\Model\StoredPublicTimelineResponse;

class S3PublicTimelineResponseRepositorySpec extends ObjectBehavior
{
    const REAL_JSON = '[
  {
    "account": {
      "acct": "ItsJenNotGabby@jorts.horse",
      "avatar": "https://files.mastodon.social/accounts/avatars/000/557/577/original/41be81b03be1185a.png",
      "avatar_static": "https://files.mastodon.social/accounts/avatars/000/557/577/original/41be81b03be1185a.png",
      "bot": false,
      "created_at": "2018-10-07T16:10:38.766Z",
      "discoverable": false,
      "display_name": "HypoallerJenic",
      "emojis": [],
      "fields": [
        {
          "name": "She/her",
          "value": "I talk about music sometimes. You\'ll probably hate my taste.",
          "verified_at": null
        },
        {
          "name": "Pickle",
          "value": "Lover",
          "verified_at": null
        },
        {
          "name": "Pineapple Pizza",
          "value": "Hater",
          "verified_at": null
        },
        {
          "name": "I would drink only coffee",
          "value": "If you didnt need water to live",
          "verified_at": null
        }
      ],
      "followers_count": 848,
      "following_count": 799,
      "group": false,
      "header": "https://files.mastodon.social/accounts/headers/000/557/577/original/76deb01114254fd2.png",
      "header_static": "https://files.mastodon.social/accounts/headers/000/557/577/original/76deb01114254fd2.png",
      "id": "557577",
      "last_status_at": "2020-02-11",
      "locked": false,
      "note": "<p>I am a goddamn delight ™. This is main now. Lewds at <span class=\"h-card\"><a href=\"https://lewd.website/@IndulJent\" class=\"u-url mention\" rel=\"nofollow noopener noreferrer\" target=\"_blank\">@<span>IndulJent</span></a></span>. An unparalleled scone of charm. I am 37 years old. If you are under 18, gtfo</p>",
      "statuses_count": 29613,
      "url": "https://jorts.horse/@ItsJenNotGabby",
      "username": "ItsJenNotGabby"
    },
    "card": null,
    "content": "<p>Putting Daddy in all my posts now so Soft will boost them lmao</p>",
    "created_at": "2020-02-11T20:36:04.000Z",
    "emojis": [],
    "favourites_count": 0,
    "id": "103642128220864503",
    "in_reply_to_account_id": null,
    "in_reply_to_id": null,
    "language": "en",
    "media_attachments": [],
    "mentions": [],
    "poll": null,
    "reblog": null,
    "reblogs_count": 0,
    "replies_count": 0,
    "sensitive": false,
    "spoiler_text": "",
    "tags": [],
    "uri": "https://jorts.horse/users/ItsJenNotGabby/statuses/103642127676054374",
    "url": "https://jorts.horse/@ItsJenNotGabby/103642127676054374",
    "visibility": "public"
  }]';

    public function let(S3Client $s3Client)
    {
        $this->beConstructedWith('some-bucket', $s3Client);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(S3PublicTimelineResponseRepository::class);
    }

    public function it_implements_the_interface(): void
    {
        $this->shouldImplement(PublicTimelineResponseRepositoryInterface::class);
    }

    public function it_uploads_to_s3(S3Client $s3Client): void
    {
        $s3Client->upload('some-bucket', Argument::type('string'), 'testing')
            ->shouldBeCalled()
        ;
        $this->store(new PublicTimelineResponse('https://mastodon.social', 'testing'));
    }

    public function it_escapes_the_name_of_the_bucket_and_keeps_it_readable(S3Client $s3Client): void
    {
        $s3Client->upload('some-bucket', '7161ef05-httpsmastodon.social', 'testing')
            ->shouldBeCalled()
        ;
        $this->store(new PublicTimelineResponse('https://mastodon.social', 'testing'));
    }

    public function it_can_retrieve_a_stored_toot_aggregate(S3Client $s3Client): void
    {
        $key = new S3PublicTimelineResponseKey('7161ef05-httpsmastodon.social');
        $s3Client->getObject(
            ['Bucket' => 'some-bucket', 'Key' => '7161ef05-httpsmastodon.social']
        )
            ->willReturn(
                ['Body' => '[{ "testing": true }]']
            )
        ;

        $this->retrieveTootStoredAggregate($key)
            ->shouldBeLike(
                new StoredPublicTimelineResponse(
                    '7161ef05-httpsmastodon.social',
                    [['testing' => true]]
                )
            )
        ;
    }
}
