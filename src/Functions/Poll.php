<?php

namespace PurpleBooth\MastodonDiagram\Functions;

use PurpleBooth\MastodonDiagram\Domain\Services\StorageInterface;
use PurpleBooth\MastodonDiagram\Domain\Services\TootRepositoryInterface;

class Poll
{
    const SUCCESS_RESPONSE = 'polled';
    /**
     * @var StorageInterface
     */
    private $storage;
    /**
     * @var TootRepositoryInterface
     */
    private $tootRepository;

    public function __construct(StorageInterface $storage, TootRepositoryInterface $tootRepository)
    {
        $this->storage = $storage;
        $this->tootRepository = $tootRepository;
    }

    public function __invoke(): string
    {
        $response = $this->tootRepository->retrievePublicTimeline();
        $this->storage->upload($response);

        return self::SUCCESS_RESPONSE;
    }
}
