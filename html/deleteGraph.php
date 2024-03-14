<?php
$file1 = '../assets/images/graph.png';
$file2 = '../assets/images/graph.dot';

if (file_exists($file1)) {
    unlink($file1);
    echo "File deleted successfully";
} else {
    echo "File does not exist";
}
if (file_exists($file2)) {
    unlink($file2);
    echo "File deleted successfully";
} else {
    echo "File does not exist";
}
?>
