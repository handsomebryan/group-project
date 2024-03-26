<?php
include '../dbconnect.php';

function getQueryParam($paramName)
{
    return isset($_GET[$paramName]) ? $_GET[$paramName] : '';
}

$id = getQueryParam('id');

// SQL 查詢，計算服務招攬業務員1和招攬業務員2相同序號的保單資料的保險金額總和
$sql = "SELECT RIGHT(業務員序號, 5) AS 業務員序號, SUM(年化保費) AS 總保費
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

$data = [];

$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
?>
