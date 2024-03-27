<?php
$folder_path = '../assets/images/1.1/';

// Get a list of all files in the folder
$files = glob($folder_path . '*');

// Iterate over the files and delete each one
foreach($files as $file){
    if(is_file($file)){
        unlink($file);
    }
}
?>
