<?php
if (isset($_FILES['image'])) {
    $newFileName = uniqid('', true) . '.png';
    $uploadPath = "uploads/" . $newFileName;
    $tmp = $_FILES['image']['tmp_name'];
    try {
        move_uploaded_file($tmp, $uploadPath);
    } catch (Exception $exception) {
        echo $exception;
    }
    echo $tmp;
    echo "success";
} ?>

<form method="post" enctype="multipart/form-data">
    <input type="file" name="image" /><br>
    <button type="submit" name="form">upload file</button>
</form>
