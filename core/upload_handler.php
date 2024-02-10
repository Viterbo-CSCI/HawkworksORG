<?php
if ($_FILES['upload']) {
    $file = $_FILES['upload']['tmp_name'];
    $file_name = $_FILES['upload']['name'];
    // You should add more validation and sanitation here
    $path = "/imgs/" . $file_name;
    move_uploaded_file($file, $_SERVER['DOCUMENT_ROOT'] . $path);

    $function_number = $_GET['CKEditorFuncNum'];
    $message = '';
    $url = $_SERVER['HTTP_ORIGIN'] . $path;
    // Return script for CKEditor
    echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($function_number, '$url', '$message');</script>";
}
?>
