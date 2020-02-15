<?php

declare(strict_types=1);

use Aws\S3\S3Client;
use PurpleBooth\MastodonDiagram\Domain\Services\TootAnalyser;
use PurpleBooth\MastodonDiagram\Functions\CountInteractionsFunction;
use PurpleBooth\MastodonDiagram\Functions\XRayFunctionDecorator;
use PurpleBooth\MastodonDiagram\Infrastructure\Handlers\CountInteractionsS3Handler;
use PurpleBooth\MastodonDiagram\Infrastructure\Services\S3AnalysisRepository;
use PurpleBooth\MastodonDiagram\Infrastructure\Services\S3PublicTimelineResponseRepository;

require __DIR__.'/../vendor/autoload.php';

/**
 * @throws RuntimeException on failure to read
 */
$getEnvOrExcept = function (string $variable): string {
    $value = getenv($variable);

    if (!$value) {
        throw new RuntimeException(sprintf('%s required environment variable not found', $variable));
    }

    return $value;
};

$responseBucket = $getEnvOrExcept('RESPONSE_BUCKET');
$analysisBucket = $getEnvOrExcept('ANALYSIS_BUCKET');

$region = $getEnvOrExcept('AWS_REGION');

return new CountInteractionsS3Handler(
    new XRayFunctionDecorator(
        'countInteractions',
        new CountInteractionsFunction(
            new S3PublicTimelineResponseRepository(
                $responseBucket,
                new S3Client(
                    [
                        'region' => $region,
                        'version' => '2006-03-01',
                    ]
                )
            ),
            new TootAnalyser(),
            new S3AnalysisRepository(
                $analysisBucket,
                new S3Client(
                    [
                        'region' => $region,
                        'version' => '2006-03-01',
                    ]
                )
            )
        )
    )
);
