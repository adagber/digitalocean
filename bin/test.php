<?php
require __DIR__.'/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__.'/..');
$dotenv->load();

$config = [
    'region'            => 'lon1',
    'version'           => 'latest',
    'signature_version' => 'v4',
    'endpoint'          => getenv('SPACES_ENDPOINT'),
    'credentials' => [
            'key'    => getenv('SPACES_KEY'),
            'secret' => getenv('SPACES_SECRET'),
    ],
];

$client = new \Aws\S3\S3Client($config);

$spaces = $client->listBuckets();
foreach ($spaces['Buckets'] as $space){
    echo $space['Name']."\n";
}

$objects = $client->listObjects([
    'Bucket' => $space['Name'],
]);

foreach ($objects['Contents'] as $obj){
    echo $obj['Key']."\n";

    $result = $client->getObject([
        'Bucket' => $space['Name'],
        'Key' => $obj['Key'],
    ]);

    file_put_contents($obj['Key'], $result['Body']);
}