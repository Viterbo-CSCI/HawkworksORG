$(document).ready(function() {
    // Event delegation for edit buttons
    $(document).on('click', '.edit-btn', function() {
        var blockId = $(this).data('block-id');
        var type = $(this).data('type') || 'text'; // Default to 'text' if type is undefined
        editBlock(blockId, type);
    });

    // Function to handle editing blocks
    function editBlock(blockId, type = 'text') {
        if (type === 'image') {
            // Open the modal for image upload and set blockId for reference
            $('#imageUploadModal').modal('show');
            $('#modalSaveImage').data('block-id', blockId);
        } else {
            // Handle text/HTML editing with CKEditor
            var contentDiv = $('#block_' + blockId + ' .content');
            var originalContent = contentDiv.html(); // Preserve the original content
            contentDiv.empty(); // Clear the content

            // Append a textarea and replace it with CKEditor
            var textareaId = 'editor_' + blockId;
            $('<textarea>', { id: textareaId }).val(originalContent).appendTo(contentDiv);
            if (CKEDITOR.instances[textareaId]) {
                CKEDITOR.instances[textareaId].destroy();
            }
            CKEDITOR.replace(textareaId, {
                // Your CKEditor config goes here
            });

            // Append the save button for CKEditor content
            $('<button>').addClass('btn btn-success mt-2').text('Save').on('click', function() {
                saveBlock(blockId);
            }).appendTo(contentDiv);
        }
    }

    // Save function for CKEditor content
    function saveBlock(blockId) {
        var editorInstance = CKEDITOR.instances['editor_' + blockId];
        var editorData = editorInstance.getData();

        $.ajax({
            url: '/core/save_block_content.php',
            type: 'POST',
            data: {
                block_id: blockId,
                content: editorData
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Reload the page or update the content dynamically
                    location.reload();
                } else {
                    alert('Error saving content: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                alert('Error saving content: ' + error);
            }
        });
    }

    // Save function for image uploads
    $('#modalSaveImage').click(function() {
        var blockId = $(this).data('block-id');
        saveImageBlock(blockId);
    });

    function saveImageBlock(blockId) {
        var fileInput = $('#modalImageUpload');
        if (fileInput[0].files.length === 0) {
            alert('Please select at least one image file to upload.');
            return;
        }
        var formData = new FormData();
        $.each(fileInput[0].files, function(i, file) {
            formData.append('images[]', file);
        });
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
                    // Update the page content or reload the page
                    location.reload();
                } else {
                    alert('Error uploading images: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                alert('Error uploading images: ' + error);
            }
        });

        // Clear the input and hide the modal after upload
        fileInput.val('');
        $('#imageUploadModal').modal('hide');
    }
});
