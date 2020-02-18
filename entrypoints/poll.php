<?php

declare(strict_types=1);

use Aws\S3\S3Client;
use Pkerrigan\Xray\Trace;
use PurpleBooth\MastodonDiagram\Functions\PollFunction;
use PurpleBooth\MastodonDiagram\Functions\XRayFunctionDecorator;
use PurpleBooth\MastodonDiagram\Infrastructure\Services\ApiTootRepository;
use PurpleBooth\MastodonDiagram\Infrastructure\Services\S3PublicTimelineResponseRepository;
use PurpleBooth\MastodonDiagram\Infrastructure\Services\XRayHttpClient;
use PurpleBooth\MastodonDiagram\Infrastructure\Services\XRayS3MetaDataGenerator;
use PurpleBooth\MastodonDiagram\Infrastructure\Services\XRayS3MetaDataHook;
use Symfony\Component\HttpClient\HttpClient;

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

$bucket = $getEnvOrExcept('RESPONSE_BUCKET');

$region = $getEnvOrExcept('AWS_REGION');

$initialHost = $getEnvOrExcept('INITIAL_URL');

return new XRayFunctionDecorator(
    Trace::getInstance(),
    'poll',
    new PollFunction(
        new S3PublicTimelineResponseRepository(
            $bucket,
            new S3Client(
                [
                    'region' => $region,
                    'version' => '2006-03-01',
                ]
            ),
            new XRayS3MetaDataGenerator(Trace::getInstance()),
            new XRayS3MetaDataHook(Trace::getInstance())
        ),
        new ApiTootRepository(
            $initialHost,
            new XRayHttpClient(
                Trace::getInstance(),
                HttpClient::create()
            )
        )
    )
);
