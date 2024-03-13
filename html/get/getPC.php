<?php
include '../dbconnect.php';

$sql = "SELECT DISTINCT 客戶郵遞區號 as postalCode FROM 客戶資料 where 客戶郵遞區號 not like 'null'  ORDER BY postalCode asc";
$result = $conn->query($sql);

$postalCodes = [];
while($row = $result->fetch_assoc()) {
    $postalCodes[] = $row['postalCode'];
}

echo json_encode($postalCodes);
?>