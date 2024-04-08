<?php
include '../dbconnect.php';

function getQueryParam($paramName)
{
    return isset($_GET[$paramName]) ? $_GET[$paramName] : '';
}

$id = getQueryParam('id');

// SQL 查詢，計算服務招攬業務員1和招攬業務員2相同序號的保單資料的保險金額總和
$sqlTop = "SELECT RIGHT(業務員序號, 5) AS 業務員序號, SUM(年化保費) AS 總保費
        FROM (
            SELECT 業務員序號, 年化保費
            FROM 保單資料 a
            JOIN 服務招攬業務員 b ON a.保單序號 = b.保單序號
            WHERE RIGHT(b.招攬業務員1序號, 5) LIKE '%$id'
            UNION ALL
            SELECT 業務員序號, 年化保費
            FROM 保單資料 a
            JOIN 服務招攬業務員 b ON a.保單序號 = b.保單序號
            WHERE RIGHT(b.招攬業務員2序號, 5) LIKE '%$id'
        ) AS sales
        GROUP BY RIGHT(業務員序號, 5)
        ORDER BY 總保費 DESC
        LIMIT 5";

$sqlBottom = "SELECT RIGHT(業務員序號, 5) AS 業務員序號, SUM(年化保費) AS 總保費
        FROM (
        SELECT 業務員序號, 年化保費
        FROM 保單資料 a
        JOIN 服務招攬業務員 b ON a.保單序號 = b.保單序號
        WHERE RIGHT(b.招攬業務員1序號, 5) LIKE '%$id'
        UNION ALL
        SELECT 業務員序號, 年化保費
        FROM 保單資料 a
        JOIN 服務招攬業務員 b ON a.保單序號 = b.保單序號
        WHERE RIGHT(b.招攬業務員2序號, 5) LIKE '%$id'
    ) AS sales
    GROUP BY RIGHT(業務員序號, 5)
    ORDER BY 總保費 ASC
    LIMIT 5;
    ";



$dataTop = [];
$dataBottom = [];

$resultTop = $conn->query($sqlTop);
while ($row = $resultTop->fetch_assoc()) {
    $dataTop[] = $row;
}

$resultBottom = $conn->query($sqlBottom);
while ($row = $resultBottom->fetch_assoc()) {
    $dataBottom[] = $row;
}


// 將資料返回為 JSON 格式
$response = [
    'data' => $dataTop, // 銷售量前五名的資料
    'data1' => $dataBottom // 銷售量倒數前五名的資料
];

echo json_encode($response);
?>
