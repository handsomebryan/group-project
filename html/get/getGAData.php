<?php
include '../dbconnect.php';

function getQueryParam($paramName)
{
    return isset($_GET[$paramName]) ? $_GET[$paramName] : '';
}

$id = getQueryParam('id');

$data = ['GA' => []];

$sql = "SELECT 客戶性別, 
        CASE 
            WHEN 客戶年齡 BETWEEN 0 AND 10 THEN '0-10'
            WHEN 客戶年齡 BETWEEN 11 AND 20 THEN '11-20'
            WHEN 客戶年齡 BETWEEN 21 AND 30 THEN '21-30'
            WHEN 客戶年齡 BETWEEN 31 AND 40 THEN '31-40'
            WHEN 客戶年齡 BETWEEN 41 AND 50 THEN '41-50'
            WHEN 客戶年齡 BETWEEN 51 AND 60 THEN '51-60'
            WHEN 客戶年齡 BETWEEN 61 AND 70 THEN '61-70'
            WHEN 客戶年齡 BETWEEN 71 AND 80 THEN '71-80'
            WHEN 客戶年齡 BETWEEN 81 AND 90 THEN '81-90'
            ELSE '90+'
        END AS age_cate,
        COUNT(*) AS 客戶數量
        FROM 
            客戶資料 a
        JOIN
            保單要保人 b ON a.客戶序號 = b.要保人序號
        JOIN
            業務員保單序號 c ON b.保單序號 = c.保單序號
        WHERE 
            業務員序號 LIKE '%$id' and 客戶性別 != ''
        GROUP BY 
            客戶性別, 
        CASE 
            WHEN 客戶年齡 BETWEEN 0 AND 10 THEN '0-10'
            WHEN 客戶年齡 BETWEEN 11 AND 20 THEN '11-20'
            WHEN 客戶年齡 BETWEEN 21 AND 30 THEN '21-30'
            WHEN 客戶年齡 BETWEEN 31 AND 40 THEN '31-40'
            WHEN 客戶年齡 BETWEEN 41 AND 50 THEN '41-50'
            WHEN 客戶年齡 BETWEEN 51 AND 60 THEN '51-60'
            WHEN 客戶年齡 BETWEEN 61 AND 70 THEN '61-70'
            WHEN 客戶年齡 BETWEEN 71 AND 80 THEN '71-80'
            WHEN 客戶年齡 BETWEEN 81 AND 90 THEN '81-90'
            ELSE '90+'
        END
        ORDER BY 
            age_cate DESC;
            ";

$gaResult = $conn->query($sql);
while ($row = $gaResult->fetch_assoc()) {
    $data['GA'][] = $row;
}

echo json_encode($data);
?>