<?php

namespace PurpleBooth\MastodonDiagram\Functions;

use PurpleBooth\MastodonDiagram\Domain\Services\PublicTimelineResponseRepositoryInterface;
use PurpleBooth\MastodonDiagram\Domain\Services\TootRepositoryInterface;

class PollFunction
{
    const SUCCESS_RESPONSE = 'polled';
    /**
     * @var PublicTimelineResponseRepositoryInterface
     */
    private $storage;
    /**
     * @var TootRepositoryInterface
     */
    private $tootRepository;

    public function __construct(PublicTimelineResponseRepositoryInterface $storage, TootRepositoryInterface $tootRepository)
    {
        $this->storage = $storage;
        $this->tootRepository = $tootRepository;
    }

    public function __invoke(): string
    {
        $response = $this->tootRepository->retrievePublicTimeline();
        $this->storage->store($response);

        return self::SUCCESS_RESPONSE;
    }
}
