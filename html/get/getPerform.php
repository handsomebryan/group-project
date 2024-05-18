<?php
include '../dbconnect.php';

function getQueryParam($paramName)
{
    return isset($_GET[$paramName]) ? $_GET[$paramName] : '';
}

$id = getQueryParam('id');

$data = ['user' => [], 'T1' => [], 'T2' => []];

if ($id) {
    $userSql = "SELECT b.商品大分類,c.業務員序號, SUM(a.年化保費) as total_sales
        FROM 保單資料 a
        JOIN 商品資料 b ON a.商品英文代碼 = b.商品英文代碼
        JOIN 業務員保單序號 c ON a.保單序號 = c.保單序號
        WHERE c.業務員序號 like '%$id'
        GROUP BY b.商品大分類;";
}

// Fetch top 1 and top 2 salesperson's data excluding user-specific ID
$topSalesSql = "WITH salesperson_sales AS (
    SELECT 
        c.業務員序號,SUM(a.年化保費) as total_sales,ROW_NUMBER() OVER (ORDER BY SUM(a.年化保費) DESC) as rank
    FROM 
        保單資料 a
    JOIN 
        業務員保單序號 c ON a.保單序號 = c.保單序號
    WHERE 
        c.業務員序號 not like '%$id'
    GROUP BY 
        c.業務員序號
),
top_salesperson AS (
    SELECT 
        業務員序號,rank
    FROM 
        salesperson_sales
    WHERE 
        rank <= 2
)
SELECT 
    b.商品大分類,c.業務員序號,SUM(a.年化保費) as total_sales,ts.rank
FROM 
    保單資料 a
JOIN 
    商品資料 b ON a.商品英文代碼 = b.商品英文代碼
JOIN 
    業務員保單序號 c ON a.保單序號 = c.保單序號
JOIN 
    top_salesperson ts ON c.業務員序號 = ts.業務員序號
GROUP BY 
    b.商品大分類,c.業務員序號,ts.rank";

$userResult = $conn->query($userSql);
while ($row = $userResult->fetch_assoc()) {
    $data['user'][] = $row;
}
$topSalesResult = $conn->query($topSalesSql);
while ($row = $topSalesResult->fetch_assoc()) {
    if ($row['rank'] == 1) {
        $data['T1'][] = $row;
    } else if ($row['rank'] == 2) {
        $data['T2'][] = $row;
    }
}

echo json_encode($data);
?>