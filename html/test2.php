<?php
include 'dbconnect.php';
$s_id = $_GET['s_id'];
$c_id = $_GET['c_id'];

// visit query
$query1 = "SELECT DATE_FORMAT(DATE(a.拜訪日期), '%Y-%m-%d') AS 日期, COUNT(*) AS 拜訪次數
 FROM CRM約訪資料 a 
 JOIN CRM客戶資料 ac ON a.客戶系統編號 = ac.客戶系統編號 
 WHERE a.業務員序號 like '%$s_id' AND ac.客戶序號 like '%$c_id' 
 GROUP BY 日期";
$result1 = mysqli_query($conn, $query1);
$data1 = mysqli_fetch_all($result1, MYSQLI_ASSOC);

// contact query
$query2 = "SELECT  DATE_FORMAT(DATE(c.聯絡日期), '%Y-%m-%d') as 日期, COUNT(*) as 聯絡次數
FROM CRM聯繫資料 as c
JOIN CRM客戶資料 as d ON c.客戶系統編號 = d.客戶系統編號
WHERE c.業務員序號 LIKE '%$s_id' AND d.客戶序號 LIKE '%$c_id'
GROUP BY 日期;
";         
$result2 = mysqli_query($conn, $query2);
$data2 = mysqli_fetch_all($result2, MYSQLI_ASSOC);

// merge and sort labels
$labels1 = array_column($data1, '日期');
$labels2 = array_column($data2, '日期');
$labels = array_unique(array_merge($labels1, $labels2));
sort($labels);

// prepare data for chart.js
$data = [
    'labels' => $labels,
    'datasets' => [
        [
            'label' => '拜訪次數',
            'data' => array_column($data1, '拜訪次數'),
            'fill' => false,
            'borderColor' => 'rgb(75, 192, 192)',
            'tension' => 0.1
        ],
        [
            'label' => '聯繫次數',
            'data' => array_column($data2, '聯絡次數'),
            'fill' => false,
            'borderColor' => 'rgb(255, 99, 132)',
            'tension' => 0.1
        ]
    ],
    'options' => [
        'scales' => [
            'x' => [
                'type' => 'time',
                'time' => [
                    'parser' => 'yyyy-mm-dd',
                    'tooltipFormat' => 'dd/mmm/yyyy'
                ],
                'ticks' => [
                    'autoSkip' => true,
                    'maxTicksLimit' => 20
                ]
            ],
            'y' => [
                'beginAtZero' => true,
                'ticks' => [
                    'stepSize' => 1,
                    'precision' => 0
                ]
            ]
        ]
    ]
];
echo json_encode($data);
?>