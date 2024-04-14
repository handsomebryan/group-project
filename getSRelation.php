<?php
include '../dbconnect.php';

function getQueryParam($paramName)
{
    return isset($_GET[$paramName]) ? $_GET[$paramName] : '';
}



$id = getQueryParam('id');


$sql = "SELECT RIGHT(招攬業務員1序號, 5) AS 業務員序號, SUM(年化保費) AS 總保費
FROM 保單資料 a
JOIN 服務招攬業務員 b ON a.保單序號 = b.保單序號
WHERE RIGHT(b.業務員序號, 5) LIKE '%$id' AND 招攬業務員1序號
GROUP BY 招攬業務員1序號
UNION ALL
SELECT RIGHT(招攬業務員2序號, 5) AS 業務員序號, SUM(年化保費) AS 總保費
FROM 保單資料 a
JOIN 服務招攬業務員 b ON a.保單序號 = b.保單序號
WHERE RIGHT(b.業務員序號, 5) LIKE '%$id' AND 招攬業務員2序號
GROUP BY 招攬業務員2序號
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
