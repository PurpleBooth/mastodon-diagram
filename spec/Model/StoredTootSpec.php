<?php

namespace spec\PurpleBooth\MastodonDiagram\Model;

use PhpSpec\ObjectBehavior;
use PurpleBooth\MastodonDiagram\Model\StoredToot;

class StoredTootSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith(
            ['url' => 'https://best-friends.chat/@Yu/103642128168824690']
        );
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(StoredToot::class);
    }

    public function it_can_get_a_host()
    {
        $this->getHost()->shouldReturn('best-friends.chat');
    }

    public function it_gives_an_empty_string_with_a_missing_host()
    {
        $this->beConstructedWith([]);
        $this->getHost()->shouldReturn('');
    }

    public function it_gives_an_empty_string_with_a_array_host()
    {
        $this->beConstructedWith(['url' => []]);
        $this->getHost()->shouldReturn('');
    }
}
