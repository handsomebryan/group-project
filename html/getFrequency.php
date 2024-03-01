<?php
include 'dbconnect.php';
function getQueryParam($paramName)
{
    return isset($_GET[$paramName]) ? $_GET[$paramName] : '';
}

$s_id = getQueryParam('s_id');
$c_id = getQueryParam('c_id');

$data = ['visit' => [], 'contact' => []];
// visit query
if ($s_id && $c_id) {
    $visit = "SELECT a.業務員序號, a.拜訪日期 AS 日期, COUNT(*) AS 拜訪次數 FROM CRM約訪資料 a JOIN CRM客戶資料 ac ON a.客戶系統編號 = ac.客戶系統編號 WHERE a.業務員序號 like '5b1445ab7762f5f2254282394ac51dc6' AND ac.客戶序號 like '6d72693668d90c6171fc90e57606f757' GROUP BY a.業務員序號, a.DATE_FORMAT(拜訪日期, '%Y-%m')";
    $contact = "SELECT c.業務員序號, c.聯絡日期 AS 日期, COUNT(*) AS 聯繫次數 FROM CRM聯繫資料 c JOIN CRM客戶資料 cc ON c.客戶系統編號 = cc.客戶系統編號 WHERE c.業務員序號 like '5b1445ab7762f5f2254282394ac51dc6' AND cc.客戶序號 like '6d72693668d90c6171fc90e57606f757' GROUP BY c.業務員序號, c.DATE_FORMAT(聯絡日期, '%Y-%m')";
} elseif ($s_id) {
    $visit = "SELECT a.業務員序號, a.拜訪日期 AS 日期, COUNT(*) AS 拜訪次數 FROM CRM約訪資料 a JOIN CRM客戶資料 ac ON a.客戶系統編號 = ac.客戶系統編號 WHERE a.業務員序號 like '5b1445ab7762f5f2254282394ac51dc6' GROUP BY a.業務員序號, a.DATE_FORMAT(拜訪日期, '%Y-%m')";

    $contact = "SELECT c.業務員序號, c.聯絡日期 AS 日期, COUNT(*) AS 聯繫次數 FROM CRM聯繫資料 c JOIN CRM客戶資料 cc ON c.客戶系統編號 = cc.客戶系統編號 WHERE c.業務員序號 like '5b1445ab7762f5f2254282394ac51dc6' GROUP BY c.業務員序號, c.DATE_FORMAT(聯絡日期, '%Y-%m')";


} elseif ($c_id) {
    $visit = "SELECT a.業務員序號, a.拜訪日期 AS 日期, COUNT(*) AS 拜訪次數 FROM CRM約訪資料 a JOIN CRM客戶資料 ac ON a.客戶系統編號 = ac.客戶系統編號 WHERE ac.客戶序號 like '6d72693668d90c6171fc90e57606f757' GROUP BY a.業務員序號, a.DATE_FORMAT(拜訪日期, '%Y-%m')";


    // contact query
    $contact = "SELECT c.業務員序號, c.聯絡日期 AS 日期, COUNT(*) AS 聯繫次數 FROM CRM聯繫資料 c JOIN CRM客戶資料 cc ON c.客戶系統編號 = cc.客戶系統編號 WHERE cc.客戶序號 like '6d72693668d90c6171fc90e57606f757' GROUP BY c.業務員序號, c.DATE_FORMAT(聯絡日期, '%Y-%m')";

}
// prepare data for chart.js
$data = [
    'labels' => array_column('日期'),
    'datasets' => [
        [
            'label' => '拜訪次數',
            'data' => array_column($data['visit'][], '拜訪次數'),
            'fill' => false,
            'borderColor' => 'rgb(75, 192, 192)',
            'tension' => 0.1
        ],
        [
            'label' => '聯繫次數',
            'data' => array_column($data['contact'][], '聯繫次數'),
            'fill' => false,
            'borderColor' => 'rgb(255, 99, 132)',
            'tension' => 0.1
        ]
    ]
];

$visitResult = $conn->query($visit);
while ($row = $visitResult->fetch_assoc()) {
    $data['visit'][] = $row;
}
$contactResult = $conn->query($contact);
while ($row = $contactResult->fetch_assoc()) {
    $data['contact'][] = $row;
}

echo json_encode($data);

?>