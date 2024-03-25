<?php
$file1 = '../assets/images/graph.png';
$file2 = '../assets/images/graph.dot';

if (file_exists($file1)) {
    unlink($file1);
}
if (file_exists($file2)) {
    unlink($file2);
}
?>