<?php
include '../dbconnect.php';

function getQueryParam($paramName)
{
    return isset($_GET[$paramName]) ? $_GET[$paramName] : '';
}

$id = getQueryParam('id');

$query = "SELECT DISTINCT RIGHT(要保人序號, 5) AS 要保人序號,
            FROM 保單要保人
            JOIN 業務員保單序號 ON 業務員保單序號.保單序號=保單要保人.保單序號
            JOIN 保單被保人 ON 保單被保人.保單序號=保單要保人.保單序號
            WHERE 業務員序號 LIKE '%$id' AND 被保人序號!=要保人序號
            ORDER BY 要保人序號 ASC";
$result = $conn->query($query);
$cid = [];
while ($row = $result->fetch_assoc()) {
    $cid[] = $row['要保人序號'];
}

echo json_encode($cid);
?>
