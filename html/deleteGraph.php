<?php
$file = '../assets/images/graph.png';

if (file_exists($file)) {
    unlink($file);
    echo "File deleted successfully";
} else {
    echo "File does not exist";
}
?>
