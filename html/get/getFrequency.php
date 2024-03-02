<?php
include '../dbconnect.php';

function getQueryParam($paramName)
{
    return isset($_GET[$paramName]) ? $_GET[$paramName] : '';
}

$s_id = getQueryParam('s_id');
$c_id = getQueryParam('c_id');

$data = ['visit' => [], 'contact' => []];



if (isset($_GET['total_statistic'])) {
    $visit = "SELECT DATE_FORMAT(DATE(a.拜訪日期), '%Y-%m') AS 日期, COUNT(*) AS 拜訪次數
            FROM CRM約訪資料 a 
            JOIN CRM客戶資料 ac ON a.客戶系統編號 = ac.客戶系統編號 
            WHERE a.業務員序號 like '%$s_id' AND ac.客戶序號 like '%$c_id' AND a.拜訪日期 >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR)
            GROUP BY 日期";

    $contact = "SELECT  DATE_FORMAT(DATE(c.聯絡日期), '%Y-%m') as 日期, COUNT(*) as 聯絡次數
                FROM CRM聯繫資料 as c
                JOIN CRM客戶資料 as d ON c.客戶系統編號 = d.客戶系統編號
                WHERE c.業務員序號 LIKE '%$s_id' AND d.客戶序號 LIKE '%$c_id' AND c.聯絡日期 >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR)
                GROUP BY 日期";
}else{
    $visit = "SELECT DATE_FORMAT(DATE(a.拜訪日期), '%Y-%m-%d') AS 日期, COUNT(*) AS 拜訪次數
            FROM CRM約訪資料 a 
            JOIN CRM客戶資料 ac ON a.客戶系統編號 = ac.客戶系統編號 
            WHERE a.業務員序號 like '%$s_id' AND ac.客戶序號 like '%$c_id'
            GROUP BY 日期";

    $contact = "SELECT  DATE_FORMAT(DATE(c.聯絡日期), '%Y-%m-%d') as 日期, COUNT(*) as 聯絡次數
                FROM CRM聯繫資料 as c
                JOIN CRM客戶資料 as d ON c.客戶系統編號 = d.客戶系統編號
                WHERE c.業務員序號 LIKE '%$s_id' AND d.客戶序號 LIKE '%$c_id'
                GROUP BY 日期";
}


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