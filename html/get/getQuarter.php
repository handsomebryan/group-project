<?php
include '../dbconnect.php';

$year = isset($_GET['year']) ? $_GET['year'] : '';

$sql = "SELECT DISTINCT QUARTER(保單生效日) as quarter FROM 保單資料 WHERE YEAR(保單生效日) = '$year' ORDER BY quarter ASC";
$result = $conn->query($sql);

$quarters = [];
while($row = $result->fetch_assoc()) {
    $quarters[] = $row['quarter'];
}

echo json_encode($quarters);
?>
