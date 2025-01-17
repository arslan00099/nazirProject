<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Video to Azure</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="container">
        <h2>Upload Video to Azure Blob Storage</h2>
        <form id="uploadForm" enctype="multipart/form-data">
            <input type="file" name="video" id="video" required>
            <button type="submit">Upload</button>
        </form>

        <!-- Progress Bar -->
        <div id="progress-container" style="display:none;">
            <div id="progress-bar"></div>
        </div>

        <!-- Messages -->
        <div id="message"></div>
    </div>

    <script>
        // Define allowed video file types
        const allowedFileTypes = ["video/mp4", "video/webm", "video/ogg"];

        document.getElementById("uploadForm").addEventListener("submit", function(event) {
            event.preventDefault(); // Prevent form from reloading

            let formData = new FormData();
            let fileInput = document.getElementById("video").files[0];

            if (!fileInput) {
                document.getElementById("message").innerHTML = "<div class='error'>❌ Please select a file.</div>";
                return;
            }

            // Check if the file type is allowed
            if (!allowedFileTypes.includes(fileInput.type)) {
                document.getElementById("message").innerHTML = "<div class='error'>❌ Only video files (mp4, webm, ogg) are allowed.</div>";
                return;
            }

            formData.append("video", fileInput);

            // Show progress bar
            document.getElementById("progress-container").style.display = "block";
            document.getElementById("progress-bar").style.width = "0%";

            // Create an XMLHttpRequest object to handle the upload and track progress
            let xhr = new XMLHttpRequest();

            xhr.open("POST", "process_upload.php", true);

            // Update progress bar during file upload
            xhr.upload.addEventListener("progress", function(event) {
                if (event.lengthComputable) {
                    let percent = (event.loaded / event.total) * 100;
                    document.getElementById("progress-bar").style.width = percent + "%";
                }
            });

            // Handle upload completion
            xhr.onload = function() {
                if (xhr.status == 200) {
                    let data = JSON.parse(xhr.responseText);
                    let messageDiv = document.getElementById("message");

                    if (data.status === "success") {
                        messageDiv.innerHTML = `<div class='success'>${data.message} <br> <a href="${data.url}" target="_blank">📹 View Video</a></div>`;
                    } else {
                        messageDiv.innerHTML = `<div class='error'>${data.message}</div>`;
                    }
                } else {
                    document.getElementById("message").innerHTML = "<div class='error'>❌ Upload failed. Please try again.</div>";
                }
                // Hide progress bar once the upload is finished
                document.getElementById("progress-container").style.display = "none";
            };

            // Handle upload error
            xhr.onerror = function() {
                document.getElementById("message").innerHTML = "<div class='error'>❌ Upload failed. Please try again.</div>";
                document.getElementById("progress-container").style.display = "none";
            };

            // Send the form data
            xhr.send(formData);
        });
    </script>
</body>

</html>