<script>
    function uploadFileInChunks(file, chunkSize, totalChunks, fileExtension, videoServer, url, videoDuration = 0) {
        let start = 0;
        let chunkIndex = 0;
        let fileName = `{{ uniqid() . time() }}`;
        let filePath = `{{ '/' . date('Y') . '/' . date('m') . '/' . date('d') . '/' }}`;
        let totalUploadedPercent = 0;
        let perIndexPercent = 100 / totalChunks;

        function processChunk() {
            const chunk = file.slice(start, start + chunkSize);
            const formData = new FormData();
            totalUploadedPercent = parseFloat(perIndexPercent * chunkIndex).toFixed(2);

            formData.append('chunk', chunk);
            formData.append('start', start);
            formData.append('file_server', videoServer);
            formData.append('file_path', filePath);
            formData.append('file_name', fileName);
            formData.append('file_extension', fileExtension);
            formData.append('video_duration', videoDuration);
            formData.append('_token', `{{ csrf_token() }}`);
            formData.append('is_last_chunk', chunkIndex === totalChunks ? 'YES' : 'NO');

            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if(response.status == 'error'){
                        notify('error', response.message);
                        return false;
                    }
                    if (chunkIndex < totalChunks) {
                        chunkIndex++;
                        start += chunkSize;
                        processChunk();
                    } else {
                        notify('success', response.message);
                        setInterval(function() {
                            location.reload();
                        }, 1000);
                    }

                    $('.customWidth').css('width', `${totalUploadedPercent}%`).text(`${totalUploadedPercent}%`);
                },
                error: function(error) {
                    console.error('Error uploading chunk:', error);
                }
            });
        }
        processChunk();
    }
</script>
