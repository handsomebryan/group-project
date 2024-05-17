<?php
include '../dbconnect.php';

function getQueryParam($paramName)
{
    return isset($_GET[$paramName]) ? $_GET[$paramName] : '';
}

$id = getQueryParam('id');
$c_id = getQueryParam('c_id');

$data = ['SSR' => [],'NSSR' => []];
$SR = "SELECT count(*) as count,SUM(保單資料.年化保費) AS SRPerform
FROM 保單要保人
JOIN 保單被保人 ON 保單被保人.保單序號 = 保單要保人.保單序號
JOIN 業務員保單序號 ON 業務員保單序號.保單序號 = 保單被保人.保單序號
JOIN 保單資料 ON 保單資料.保單序號 = 保單被保人.保單序號
WHERE 業務員保單序號.業務員序號 LIKE '%$id' AND 要保人序號 = 被保人序號 AND 要保人序號 like '%$c_id'";

$NSR = "SELECT count(*) as count,SUM(保單資料.年化保費) AS NSRPerform
FROM 保單要保人
JOIN 保單被保人 ON 保單被保人.保單序號 = 保單要保人.保單序號
JOIN 業務員保單序號 ON 業務員保單序號.保單序號 = 保單被保人.保單序號
JOIN 保單資料 ON 保單資料.保單序號 = 保單被保人.保單序號
WHERE 業務員保單序號.業務員序號 LIKE '%$id' AND 要保人序號 != 被保人序號 AND 要保人序號 like '%$c_id'";

$SRResult = $conn->query($SR);
while ($row = $SRResult->fetch_assoc()) {
    $data['SR'][] = $row;
}
$NSRResult = $conn->query($NSR);
while ($row = $NSRResult->fetch_assoc()) {
    $data['NSR'][] = $row;
}

echo json_encode($data);
?>