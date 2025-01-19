<?php
require 'vendor/autoload.php';

use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;

require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$connectionString = $_ENV['AZURE_STORAGE_ACCOUNT_KEY'];
$containerName = "nazircontianer";

$blobClient = BlobRestProxy::createBlobService($connectionString);

try {
    $blobList = $blobClient->listBlobs($containerName);
    $blobs = $blobList->getBlobs();
} catch (ServiceException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Videos from blob</title>
    <link rel="stylesheet" href="styles2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
    <div class="container">
        <h2>Videos from blob</h2>
        <div class="grid">
            <?php foreach ($blobs as $blob): ?>
                <?php if (strpos($blob->getName(), '.mp4') !== false || strpos($blob->getName(), '.webm') !== false || strpos($blob->getName(), '.ogg') !== false): ?>
                    <div class="grid-item">
                        <video class="small-video" controls>
                            <source src="https://nazirblob.blob.core.windows.net/<?php echo $containerName; ?>/<?php echo $blob->getName(); ?>" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                        <p><?php echo basename($blob->getName()); ?></p>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>

    <style>
        .small-video {
            width: 200px;
            height: 120px;
            object-fit: cover;
        }
    </style>
</body>

</html>