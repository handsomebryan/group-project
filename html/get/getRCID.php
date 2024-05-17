<?php
include '../dbconnect.php';

function getQueryParam($paramName)
{
    return isset($_GET[$paramName]) ? $_GET[$paramName] : '';
}

$id = getQueryParam('id');

$query = "SELECT DISTINCT RIGHT(要保人序號, 5) AS 要保人序號 
            FROM 保單要保人
            JOIN 業務員保單序號 ON 業務員保單序號.保單序號=保單要保人.保單序號
            WHERE 業務員序號 LIKE '%$id'
            ORDER BY 要保人序號 ASC;";
$result = $conn->query($query);
$c_id = [];
while ($row = $result->fetch_assoc()) {
    $c_id[] = $row['要保人序號'];
}

echo json_encode($c_id);
?>