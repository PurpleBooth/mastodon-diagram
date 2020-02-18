<?php

declare(strict_types=1);

use Aws\S3\S3Client;
use Pkerrigan\Xray\Trace;
use PurpleBooth\MastodonDiagram\Domain\Services\TootAnalyser;
use PurpleBooth\MastodonDiagram\Functions\CountInteractionsFunction;
use PurpleBooth\MastodonDiagram\Functions\XRayFunctionDecorator;
use PurpleBooth\MastodonDiagram\Infrastructure\Handlers\CountInteractionsS3Handler;
use PurpleBooth\MastodonDiagram\Infrastructure\Services\S3AnalysisRepository;
use PurpleBooth\MastodonDiagram\Infrastructure\Services\S3PublicTimelineResponseRepository;
use PurpleBooth\MastodonDiagram\Infrastructure\Services\XRayS3MetaDataGenerator;
use PurpleBooth\MastodonDiagram\Infrastructure\Services\XRayS3MetaDataHook;

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
        Trace::getInstance(),
        'countInteractions',
        new CountInteractionsFunction(
            new S3PublicTimelineResponseRepository(
                $responseBucket,
                new S3Client(
                    [
                        'region' => $region,
                        'version' => '2006-03-01',
                    ]
                ),
                new XRayS3MetaDataGenerator(
                    Trace::getInstance()
                ),
                new XRayS3MetaDataHook(
                    Trace::getInstance()
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
