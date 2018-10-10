<?php
error_reporting(E_ERROR | E_PARSE);

function deleteOldUploads($time = 1200) {
    $ds = DIRECTORY_SEPARATOR;
    $files = glob(dirname( __FILE__ ) . $ds."uploads". $ds."*");
    $now   = time();

    foreach ($files as $file) {
        if (is_file($file)) {
            if ($now - filemtime($file) >= $time) {
                unlink($file);
            }
        }
    }
}

$ds = DIRECTORY_SEPARATOR;
$storeFolder = 'uploads';

if (!empty($_FILES)) {
    reset($_FILES);
    $first = key($_FILES);

    $uid = uniqid('img_'). '_' .$_FILES[$first]['name'];
    $tempFile = $_FILES[$first]['tmp_name'];
    $targetPath = dirname( __FILE__ ) . $ds. $storeFolder . $ds;
    $targetFile =  $targetPath. $uid;

    $response = array();

    try {
        move_uploaded_file($tempFile, $targetFile);
    } catch (Exception $e) {
        $response['error'] = $e->getMessage();
        echo json_encode($response);
        exit();
    }

    deleteOldUploads();

    $response['image'] = $uid;
    echo json_encode($response);
}
