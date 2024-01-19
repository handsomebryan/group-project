<?php
include 'dbconnect.php';

$sql = "SELECT DISTINCT YEAR(保單生效日) as year FROM 保單資料 ORDER BY year DESC";
$result = $conn->query($sql);

$years = [];
while($row = $result->fetch_assoc()) {
    $years[] = $row['year'];
}

echo json_encode($years);
?>
