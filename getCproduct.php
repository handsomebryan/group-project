<?php
include '../dbconnect.php';

function getQueryParam($paramName)
{
    return isset($_GET[$paramName]) ? $_GET[$paramName] : '';
}

$id = getQueryParam('id');

$data = ['user' => [], 'T1' => []];

if ($id) {
    $userSql = "SELECT b.商品英文代碼,c.要保人序號, SUM(a.年化保費) AS total_sales
        FROM 保單資料 a
        JOIN 商品資料 b ON a.商品英文代碼 = b.商品英文代碼
        JOIN 保單要保人 c ON a.保單序號 = c.保單序號
        WHERE c.要保人序號 like '%$id'
        AND a.年化保費 <> 0
        GROUP BY b.商品英文代碼;";
}


$top1SalesSql = "SELECT b.商品英文代碼, 
AVG(a.年化保費) AS total_sales
FROM 保單資料 a
JOIN 商品資料 b ON a.商品英文代碼 = b.商品英文代碼
GROUP BY b.商品英文代碼;";





$userResult = $conn->query($userSql);
while ($row = $userResult->fetch_assoc()) {
    $data['user'][] = $row;
}
$top1Result = $conn->query($top1SalesSql);
while ($row = $top1Result->fetch_assoc()) {
    $data['T1'][] = $row;
}


echo json_encode($data);
?>
