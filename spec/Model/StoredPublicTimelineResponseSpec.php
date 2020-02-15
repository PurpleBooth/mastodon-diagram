<?php

namespace spec\PurpleBooth\MastodonDiagram\Model;

use IteratorAggregate;
use PhpSpec\ObjectBehavior;
use PurpleBooth\MastodonDiagram\Model\StoredPublicTimelineResponse;
use PurpleBooth\MastodonDiagram\Model\StoredToot;

class StoredPublicTimelineResponseSpec extends ObjectBehavior
{
    private const ARRAY_OF_TOOT = '[
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
  },
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
    private const TOOT = '{
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
  }';

    public function let()
    {
        $this->beConstructedThrough(
            'fromJson',
            [
                'key',
                self::ARRAY_OF_TOOT,
            ]
        );
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(StoredPublicTimelineResponse::class);
    }

    public function is_iterable()
    {
        $this->shouldImplement(IteratorAggregate::class);
    }

    public function it_is_countable()
    {
        $this->shouldImplement(\Countable::class);
    }

    public function it_is_traversable()
    {
        $this->shouldImplement(\Traversable::class);
    }

    public function it_returns_count_of_contained_items()
    {
        $this->shouldHaveCount(2);
    }

    public function it_can_tell_me_how_many_times_each_server_was_a_source()
    {
        $this->getStoredToots()->shouldBeLike(
            [
                new StoredToot(
                    json_decode(
                        self::TOOT,
                        true
                    )
                ),
                new StoredToot(
                    json_decode(
                        self::TOOT,
                        true
                    )
                ),
            ]
        );
    }

    public function it_can_tell_me_its_key(): void
    {
        $this->getKey()->shouldReturn('key');
    }
}
