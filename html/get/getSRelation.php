<?php
include '../dbconnect.php';

function getQueryParam($paramName)
{
    return isset($_GET[$paramName]) ? $_GET[$paramName] : '';
}



$id = getQueryParam('id');


$sql = "SELECT a.保單序號, RIGHT(b.業務員序號,5) AS 招待業務員序號, SUM(c.年化保費) AS 總保費
FROM 業務員保單序號 a
JOIN 業務員保單序號 b ON a.保單序號 = b.保單序號
JOIN 保單資料 c ON a.保單序號 = c.保單序號
WHERE a.是否服務業務員 = 0 AND b.是否服務業務員 = 1 AND a.業務員序號 LIKE '%$id'
GROUP BY 招待業務員序號
ORDER BY 總保費 DESC
LIMIT 10;
";

$data = [];

$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
?>