<?php
$ds = DIRECTORY_SEPARATOR;
$storeFolder = 'uploads';

if (!empty($_FILES)) {
    $uid = uniqid('img_'). '_' .$_FILES['file']['name'];
    $tempFile = $_FILES['file']['tmp_name'];
    $targetPath = dirname( __FILE__ ) . $ds. $storeFolder . $ds;
    $targetFile =  $targetPath. $uid;
    move_uploaded_file($tempFile,$targetFile);

    echo $uid;
}
