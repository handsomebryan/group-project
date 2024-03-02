<?php
include '../dbconnect.php';

$year = isset($_GET['year']) ? $_GET['year'] : '';

$sql = "SELECT DISTINCT MONTH(保單生效日) as month FROM 保單資料 WHERE YEAR(保單生效日) = '$year' ORDER BY month ASC";
$result = $conn->query($sql);

$months = [];
while($row = $result->fetch_assoc()) {
    $months[] = $row['month'];
}

echo json_encode($months);
?>
