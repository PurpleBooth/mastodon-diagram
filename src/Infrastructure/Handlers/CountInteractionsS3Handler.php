<?php

namespace PurpleBooth\MastodonDiagram\Infrastructure\Handlers;

use Bref\Context\Context;
use Bref\Event\S3\S3Event;
use Bref\Event\S3\S3Handler;
use PurpleBooth\MastodonDiagram\Model\S3PublicTimelineResponseKey;

class CountInteractionsS3Handler extends S3Handler
{
    /**
     * @var callable
     */
    private $countInteractionsFunction;

    public function __construct(callable $countInteractionsFunction)
    {
        $this->countInteractionsFunction = $countInteractionsFunction;
    }

    public function handleS3(S3Event $event, Context $context): void
    {
        foreach ($event->getRecords() as $record) {
            ($this->countInteractionsFunction)(
                new S3PublicTimelineResponseKey(
                    $record->getObject()->getKey()
                ),
                $context
            );
        }
    }
}
