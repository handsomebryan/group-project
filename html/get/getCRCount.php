<?php
include '../dbconnect.php';

function getQueryParam($paramName)
{
    return isset($_GET[$paramName]) ? $_GET[$paramName] : '';
}

$id = getQueryParam('id');

$data = ['self' => [], 'nself' => []];

$self = "SELECT count(*), SUM(保單資料.年化保費) AS selfPerform
FROM 保單要被保人
JOIN 業務員保單序號 ON 業務員保單序號.保單序號 = 保單要被保人.保單序號
JOIN 保單資料 ON 保單資料.保單序號 = 保單要被保人.保單序號
WHERE 業務員保單序號.業務員序號 LIKE '%$id' AND 保單要被保人.要保人序號 = 保單要被保人.被保人序號 AND 保單資料.保單生效日 >= DATE_SUB(CURDATE(), INTERVAL 10 YEAR)
";

$nself = "SELECT count(*), SUM(保單資料.年化保費) AS nselfPerform
FROM 保單要被保人
JOIN 業務員保單序號 ON 業務員保單序號.保單序號 = 保單要被保人.保單序號
JOIN 保單資料 ON 保單資料.保單序號 = 保單要被保人.保單序號
WHERE 業務員保單序號.業務員序號 LIKE '%$id' AND 保單要被保人.要保人序號 != 保單要被保人.被保人序號 AND 保單資料.保單生效日 >= DATE_SUB(CURDATE(), INTERVAL 10 YEAR)
";

$selfResult = $conn->query($self);
while ($row = $selfResult->fetch_assoc()) {
    $data['self'][] = $row;
}
$nselfResult = $conn->query($nself);
while ($row = $nselfResult->fetch_assoc()) {
    $data['nself'][] = $row;
}

echo json_encode($data);
?>