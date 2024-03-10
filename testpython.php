<?php
$script = __DIR__ . DIRECTORY_SEPARATOR . "../../function1.1.py";
$result = shell_exec("python $script");
//echo "PHP got the result - $result";
echo '<img src="../assets/images/1/image.png" alt="Generated Image">';
?>
