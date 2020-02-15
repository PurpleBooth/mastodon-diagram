<?php

namespace PurpleBooth\MastodonDiagram\Model;

class StoredToot
{
    /**
     * @var array<string, array<string,array<string,int|string>|int|string>|int|string>
     */
    private $toot;

    /**
     * StoredToot constructor.
     *
     * @param array<string, array<string,array<string,int|string>|int|string>|int|string> $toot
     */
    public function __construct(array $toot)
    {
        $this->toot = $toot;
    }

    public function getHost(): string
    {
        if (!\array_key_exists('url', $this->toot)) {
            return '';
        }

        if (!\is_string($this->toot['url'])) {
            return '';
        }

        return sprintf('%s', parse_url($this->toot['url'], PHP_URL_HOST));
    }
}
