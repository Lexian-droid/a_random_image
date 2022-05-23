<?php

// check if php file called "config.php" exists in the same directory as this file
if (file_exists('config.json')) {
    // import config.json
    $config = json_decode(file_get_contents('config.json'), true);

    shuffle($config['images']);
    shuffle($config['images']);
    shuffle($config['images']);
    shuffle($config['images']);
    shuffle($config['images']);
    $image = $config['images'][0];

    // get image info
    $image_info = getimagesize($image);
    $image_width = $image_info[0];
    $image_height = $image_info[1];
    $image_format = $image_info['mime'];
    $iamge_extension = pathinfo($image, PATHINFO_EXTENSION);
    $image_name = pathinfo($image, PATHINFO_FILENAME);

    // if something goes wrong
    if (!$image_info) {
        die("Something went wrong!");
    }

    // respond with image
    header("Content-Type: $image_format");
    header("Content-Disposition: inline; filename=$image_name.$iamge_extension");
    header("Content-Transfer-Encoding: binary");
    header("Content-Length: " . filesize($image));
    header("Accept-Ranges: bytes");
    readfile($image);

    // prevent the browser from caching the image
    header("Cache-Control: no-cache, no-store, must-revalidate");
    header("Pragma: no-cache");
    header("Expires: 0");

    // add image to header
    header("X-Image-Name: $image_name");
    header("X-Image-Width: $image_width");
    header("X-Image-Height: $image_height");
    header("X-Image-Format: $image_format");
    header("X-Image-Extension: $iamge_extension");
    header("X-Image-Path: $image");
    header("X-Config-Timestamp: " . $config["settings"]["timestamp"]);
    header("X-Config-Version: " . $config["settings"]["version"]);

    // additional headers for info
    header("X-Image-Info: " . json_encode($image_info));
    header("X-Config-Info: " . json_encode($config));

    // add favicon to header and make it the default icon
    header("X-Favicon: " . $image);
    header("X-Icon: " . $image);

    // exit
    exit();
} else {
    // if it does not, include "./scripts/ftsu.php"
    include './scripts/ftsu.php';
}

?>