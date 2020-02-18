<?php

namespace spec\PurpleBooth\MastodonDiagram\Infrastructure\Handlers;

use Bref\Context\Context;
use Bref\Event\S3\S3Event;
use Bref\Event\S3\S3Handler;
use PhpSpec\ObjectBehavior;
use PurpleBooth\MastodonDiagram\Functions\CountInteractionsFunction;
use PurpleBooth\MastodonDiagram\Infrastructure\Handlers\CountInteractionsS3Handler;
use PurpleBooth\MastodonDiagram\Model\S3PublicTimelineResponseKey;

class CountInteractionsS3HandlerSpec extends ObjectBehavior
{
    public function let(CountInteractionsFunction $countInteractionsFunction)
    {
        $this->beConstructedWith($countInteractionsFunction);
    }

    public function it_is_an_s3_handler()
    {
        $this->beAnInstanceOf(S3Handler::class);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(CountInteractionsS3Handler::class);
    }

    public function it_delegates_for_every_record(CountInteractionsFunction $countInteractionsFunction)
    {
        $handler = new S3Event(
            [
                'Records' => [
                    [
                        'eventSource' => 'aws:s3',
                        's3' => [
                            'object' => [
                                'key' => 'Record 1',
                                'size' => 99,
                            ],
                        ],
                    ],
                    [
                        'eventSource' => 'aws:s3',
                        's3' => [
                            'object' => [
                                'key' => 'Record 2',
                                'size' => 99,
                            ],
                        ],
                    ],
                ],
            ]
        );
        $context = new Context('id', 10000, 'unused', 'unused');
        $countInteractionsFunction->__invoke(new S3PublicTimelineResponseKey('Record 1'), $context)->shouldBeCalled();
        $countInteractionsFunction->__invoke(new S3PublicTimelineResponseKey('Record 2'), $context)->shouldBeCalled();
        $this->handleS3($handler, $context);
    }
}
