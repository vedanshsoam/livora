<?php
/**
 * Livora file storage adapter.
 * Uses Amazon S3 when AWS_S3_BUCKET is configured; otherwise stores files locally.
 * On EC2, use an instance IAM role instead of hard-coded AWS access keys.
 */
$autoload = __DIR__ . '/../vendor/autoload.php';
if (is_file($autoload)) require_once $autoload;

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

function livora_s3_client(): ?S3Client {
    static $client = null;
    static $checked = false;
    if ($checked) return $client;
    $checked = true;
    $bucket = getenv('AWS_S3_BUCKET') ?: '';
    if ($bucket === '') return null;
    if (!class_exists(S3Client::class)) {
        throw new RuntimeException('AWS SDK is missing. Run composer install before enabling S3.');
    }

    $client = new S3Client([
        'version' => 'latest',
        'region' => getenv('AWS_REGION') ?: 'ap-south-1',
        // SDK uses its default credential chain, including EC2 instance roles.
    ]);
    return $client;
}

function livora_store_uploaded_file(array $file, int $userId): array {
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Please select a file and try again.');
    }
    if (($file['size'] ?? 0) > 5 * 1024 * 1024) {
        throw new RuntimeException('Image size must be 5 MB or less.');
    }
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);
    $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
    if (!isset($allowed[$mime])) {
        throw new RuntimeException('Only JPG, PNG and WEBP images are allowed.');
    }

    $key = 'profiles/' . $userId . '/' . bin2hex(random_bytes(16)) . '.' . $allowed[$mime];
    $s3 = livora_s3_client();
    $bucket = getenv('AWS_S3_BUCKET') ?: '';

    if ($s3 && $bucket !== '') {
        $s3->putObject([
            'Bucket' => $bucket,
            'Key' => $key,
            'Body' => fopen($file['tmp_name'], 'rb'),
            'ContentType' => $mime,
            'ServerSideEncryption' => 'AES256',
        ]);
        return ['driver' => 's3', 'key' => $key];
    }

    $directory = __DIR__ . '/../uploads/' . $userId;
    if (!is_dir($directory) && !mkdir($directory, 0750, true) && !is_dir($directory)) {
        throw new RuntimeException('Could not create the upload directory.');
    }
    $target = $directory . '/' . basename($key);
    if (!move_uploaded_file($file['tmp_name'], $target)) {
        throw new RuntimeException('Could not save the uploaded image.');
    }
    return ['driver' => 'local', 'key' => 'uploads/' . $userId . '/' . basename($key)];
}

function livora_profile_image_url(string $storedPath): string {
    if ($storedPath === '') return '';
    if (str_starts_with($storedPath, 'uploads/')) return $storedPath;
    $s3 = livora_s3_client();
    $bucket = getenv('AWS_S3_BUCKET') ?: '';
    if (!$s3 || $bucket === '') return '';
    $command = $s3->getCommand('GetObject', ['Bucket' => $bucket, 'Key' => $storedPath]);
    $request = $s3->createPresignedRequest($command, '+10 minutes');
    return (string) $request->getUri();
}
