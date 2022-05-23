<?php
// display php errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$config = array();
$config['images'] = array();

$imgdir = "./img/";

foreach(scandir($imgdir) as $file) {
    // check if a file
    if(is_file($imgdir . $file)) {
        // check if file is an image
        if(getimagesize($imgdir . $file)) {
            // add full path to image to $config->images in quotes
            $config['images'][] = $imgdir . $file;
            $images_saved = true;
        } else{
            $trash = "./img_trash/";
            rename($imgdir . $file, $trash . $file);
            $end_with_error = true;
        }
    }
}

if(!isset($images_saved)) {
    die("No images found in $imgdir");
}

// add timestamp to config->settings
$config['settings']['timestamp'] = time();
$config['settings']['version'] = uniqid("", true);

// encode config to json
$config_json = json_encode($config, JSON_UNESCAPED_SLASHES);
file_put_contents('./config.json', $config_json);

if(isset($end_with_error) == true) {
    echo "<h2>Error:</h2>";
    echo "<p>Some files in $imgdir are not images.</p>";
    echo "<p>They have been moved to ./img_trash/</p>";
    echo "<a href='./'>Click here to continue.</a>";
    echo "<hr>";
    // scan the directory for files and display them
    foreach(scandir($trash) as $file) {
        if(is_file($trash . $file)) {
            echo "<p>$file</p>";
        }
    }
    exit();
}

// redirect to index.php
header("Location: ./");
exit();
?>