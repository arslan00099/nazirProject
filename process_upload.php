<?php
require 'vendor/autoload.php';

use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use MicrosoftAzure\Storage\Blob\Models\CreateBlockBlobOptions;

require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Now you can access the environment variables
$connectionString = $_ENV['AZURE_STORAGE_ACCOUNT_KEY'];
$containerName = "nazircontianer";

// Create Blob Client
$blobClient = BlobRestProxy::createBlobService($connectionString);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["video"])) {
    $fileName = $_FILES["video"]["name"];
    $fileTmpName = $_FILES["video"]["tmp_name"];

    try {
        // Read File Content
        $content = fopen($fileTmpName, "r");

        // Upload Video to Azure Blob Storage
        $blobClient->createBlockBlob($containerName, $fileName, $content);

        // Generate Video URL
        $videoUrl = "https://blobhamza.blob.core.windows.net/$containerName/$fileName";

        echo json_encode(["status" => "success", "message" => "✅ Video uploaded successfully!", "url" => $videoUrl]);
    } catch (ServiceException $e) {
        echo json_encode(["status" => "error", "message" => "❌ Error: " . $e->getMessage()]);
    }
}
