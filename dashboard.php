<?php
require 'vendor/autoload.php';

use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;

require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Now you can access the environment variables
$connectionString = $_ENV['AZURE_STORAGE_ACCOUNT_KEY'];
$containerName = "blobcontainer";

// Create Blob Client
$blobClient = BlobRestProxy::createBlobService($connectionString);

// Get list of blobs
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
    <title>Video Gallery</title>
    <link rel="stylesheet" href="styles2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
    <div class="container">
        <h2>Video Gallery</h2>
        <div class="grid">
            <?php foreach ($blobs as $blob): ?>
                <?php if (strpos($blob->getName(), '.mp4') !== false || strpos($blob->getName(), '.webm') !== false || strpos($blob->getName(), '.ogg') !== false): ?>
                    <div class="grid-item" onclick="playVideo('<?php echo $blob->getName(); ?>')">
                        <!-- Using Font Awesome Icon as Thumbnail -->
                        <div class="thumbnail">
                            <i class="fas fa-video"></i>
                        </div>
                        <p><?php echo basename($blob->getName()); ?></p>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Video Player Modal -->
    <div id="videoModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <video id="videoPlayer" controls>
                <source id="videoSource" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
    </div>

    <script>
        function playVideo(blobName) {
            // Construct the video URL
            let videoUrl = "https://blobhamza.blob.core.windows.net/<?php echo $containerName; ?>/" + blobName;
            document.getElementById("videoSource").src = videoUrl;
            let videoModal = document.getElementById("videoModal");
            videoModal.style.display = "block";
            document.getElementById("videoPlayer").load();
            document.getElementById("videoPlayer").play();
        }

        function closeModal() {
            let videoModal = document.getElementById("videoModal");
            videoModal.style.display = "none";
            document.getElementById("videoPlayer").pause();
        }
    </script>
</body>

</html>