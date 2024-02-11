$(document).ready(function() {
    // Handle edit button click events
    $(document).on('click', '.edit-btn', function() {
        var blockId = $(this).data('block-id');
        var type = $(this).data('type') || 'text';
        if (type === 'image') {
            editImageBlock(blockId);
        } else {
            editTextBlock(blockId);
        }
    });

    // Function to handle image block edits
    function editImageBlock(blockId) {
        fetchBlockImages(blockId, function(images) {
            populateModalWithImages(images);
            $('#imageUploadModal').modal('show');
            $('#modalSaveImage').data('block-id', blockId);
        });
    }

    // Function to handle text block edits
    function editTextBlock(blockId) {
        var contentDiv = $('#block_' + blockId + ' .content');
        var originalContent = contentDiv.html();

        // Clear existing content and setup textarea for CKEditor
        contentDiv.empty();
        var textareaId = 'editor_' + blockId;
        var textarea = $('<textarea>', { id: textareaId, html: originalContent }).appendTo(contentDiv);

        // Initialize CKEditor
        if (CKEDITOR.instances[textareaId]) {
            CKEDITOR.instances[textareaId].destroy();
        }
        CKEDITOR.replace(textareaId, {
            filebrowserUploadUrl: '/core/upload_handler.php',
            filebrowserUploadMethod: 'form'
        });


        // Setup save button for CKEditor content
        $('<button>', {
            text: 'Save',
            click: function() { saveTextBlock(blockId); }
        }).appendTo(contentDiv);
    }

    // AJAX function to fetch block images
    function fetchBlockImages(blockId, callback) {
        $.ajax({
            url: '/core/fetch_block_images.php',
            type: 'GET',
            data: { block_id: blockId },
            dataType: 'json',
            success: function(response) {
                if (response.success && Array.isArray(response.images)) {
                    callback(response.images);
                } else {
                    alert('No images found or error fetching images.');
                }
            },
            error: function(xhr, status, error) {
                alert('Error fetching images: ' + error);
            }
        });
    }
        function populateModalWithImages(images) {
        var modalBody = $('#imageUploadModal .modal-body');
        modalBody.empty(); // Clear previous contents

        images.forEach(function(image, index) {
            var imageContainer = $('<div>', { class: 'image-container mb-3' }).appendTo(modalBody);
            
            $('<img>', {
                src: image.url,
                class: 'img-thumbnail mb-2',
                alt: 'Image ' + (index + 1)
            }).appendTo(imageContainer);

            $('<input>', {
                type: 'file',
                class: 'form-control',
                'data-image-id': image.id // Or any identifier you have for the image
            }).appendTo(imageContainer);
        });
    }


    // Function to save CKEditor content
    function saveTextBlock(blockId) {
        var editorData = CKEDITOR.instances['editor_' + blockId].getData();
        $.ajax({
            url: '/core/save_block_content.php',
            type: 'POST',
            data: { block_id: blockId, content: editorData },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert('Content updated successfully.');
                    location.reload(); // Optionally, dynamically update the content
                } else {
                    alert('Error saving content: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                alert('Error saving content: ' + error);
            }
        });
    }

    // Save image block function remains unchanged
    $('#modalSaveImage').click(function() {
        saveImageBlock($(this).data('block-id'));
    });

    function saveImageBlock(blockId) {
        var formData = new FormData();
        var hasFilesToUpload = false;
    
        $('#imageUploadModal .image-container input[type="file"]').each(function() {
            if (this.files.length > 0) {
                formData.append('replacement_images[]', this.files[0]); // Append the selected file
                formData.append('content_ids[]', $(this).data('content-id')); // Correctly access the data attribute

                hasFilesToUpload = true;
            }
        });
    
        if (!hasFilesToUpload) {
            alert('Please select at least one image file to upload or replace.');
            return;
        }
    
        formData.append('block_id', blockId);
    
        $.ajax({
            url: '/core/save_block_content_multiple_images.php',
            type: 'POST',
            processData: false,
            contentType: false,
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert('Images uploaded/replaced successfully.');
                    location.reload(); // Or update the UI to reflect the changes
                } else {
                    alert('Error uploading/replacing images: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                alert('Error uploading/replacing images: ' + error);
            }
        });
    
        // Clear the input and hide the modal after upload
        $('#imageUploadModal').modal('hide');
    }
});
