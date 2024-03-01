<?php
include 'dbconnect.php';

function getQueryParam($paramName)
{
    return isset($_GET[$paramName]) ? $_GET[$paramName] : '';
}

$s_id = getQueryParam('s_id');

$query = "SELECT RIGHT(客戶序號,5) AS 客戶序號 FROM CRM客戶資料 WHERE 業務員序號 LIKE '%$s_id%' ORDER BY 客戶序號 ASC";
$result = $conn->query($query);
$ids = [];
while ($row = $result->fetch_assoc()) {
    $ids[] = $row['客戶序號'];
}

echo json_encode($ids);
?>
