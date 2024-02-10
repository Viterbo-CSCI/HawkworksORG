// edit_blocks.js


function editBlock(blockId, type = 'text') {
    var blockContainer = $('#block_' + blockId);
    var contentDiv = blockContainer.find('.content');
    blockContainer.find('button').remove(); // Remove any existing buttons

    if (type === 'image') {
        // Set up for image editing
        $('<input>', { type: 'file', id: 'imageUpload_' + blockId }).appendTo(contentDiv);
        $('<button>').text('Upload Image').click(function() { saveImageBlock(blockId); }).appendTo(contentDiv);
    } else {
        // Set up for text/HTML editing with CKEditor
        var textareaId = 'editor_' + blockId;
        var textarea = $('<textarea>', { id: textareaId }).html(contentDiv.html()).appendTo(contentDiv);
        if (CKEDITOR.instances[textareaId]) {
            CKEDITOR.instances[textareaId].destroy();
        }
        CKEDITOR.replace(textareaId, {
            filebrowserUploadUrl: '/core/upload_handler.php',
            filebrowserUploadMethod: 'form'
        });

        // Append the save button
        $('<button>').text('Save').click(function() { saveBlock(blockId); }).appendTo(contentDiv);
    }
}

// ...rest of your edit_blocks.js file...


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
                // Update the content and replace the CKEditor with the new content
                var contentDiv = $('#block_' + blockId + ' .content');
                contentDiv.html(response.content);
                $('<button>').text('Edit').click(function() {
                    editBlock(blockId, 'text');
                }).appendTo('#block_' + blockId);
            } else {
                alert('Error saving content: ' + response.message);
            }
        },
        error: function(xhr, status, error) {
            alert('Error saving content: ' + error);
        }
    });
}

function saveImageBlock(blockId) {
    var fileInput = $('#imageUpload_' + blockId);
    if (fileInput[0].files.length === 0) {
        alert('Please select an image file to upload.');
        return;
    }
    var formData = new FormData();
    formData.append('image', fileInput[0].files[0]);
    formData.append('block_id', blockId);

    $.ajax({
        url: 'core/save_block_content.php',
        type: 'POST',
        processData: false,
        contentType: false,
        data: formData,
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                // Update the image content
                var contentDiv = $('#block_' + blockId + ' .content');
                contentDiv.html('<img src="' + response.newImagePath + '" alt="Updated Image">');
                $('<button>').text('Edit').click(function() {
                    editBlock(blockId, 'image');
                }).appendTo('#block_' + blockId);
            } else {
                alert('Error uploading image: ' + response.message);
            }
        },
        error: function(xhr, status, error) {
            alert('Error uploading image: ' + error);
        }
    });
}
