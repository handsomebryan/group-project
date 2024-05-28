<?php
include '../dbconnect.php';

function getQueryParam($paramName)
{
    return isset($_GET[$paramName]) ? $_GET[$paramName] : '';
}

$sid = getQueryParam('sid');


$query = "SELECT DISTINCT RIGHT(要保人序號, 5) AS id
          FROM 保單要保人
          JOIN 保單資料 ON 保單資料.保單序號 = 保單要保人.保單序號
          JOIN 業務員保單序號 ON 保單要保人.保單序號 = 業務員保單序號.保單序號
          WHERE 保單資料.年化保費 IS NOT NULL
          AND 保單資料.年化保費 <> 0           
          AND 業務員序號 LIKE '%$sid'
          order by id ASC";



$result = $conn->query($query);
$data = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

echo json_encode($data);
?>