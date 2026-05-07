<?php

function zipCreate($n, $f) {
    $zip = new ZipArchive();

    if ($zip->open($n, ZipArchive::CREATE) !== true) return false;

    for ($i = 0; $i < count($f['name']); $i++) {
        $slash = strpos("/", $f['full_path'][$i]);
        $fileName = substr($f['full_path'][$i], $slash + 1);
        $zip->addFile($f['tmp_name'][$i], $fileName);
    }

    $zip->close();
    return true;
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $files = $_FILES['file'];
    $zipName = explode("/", $files['full_path'][0])[0].".zip";
    if (zipCreate($zipName, $files)) {
        if (file_exists($zipName)) {
            header("Content-Type: application/zip");
            header("Content-Disposition: attachment; filename='".$zipName."'");
            header("Content-Length: ".filesize($zipName));
            readfile($zipName);

            unlink($zipName);
        }
    }
}