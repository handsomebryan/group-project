<?php
include '../dbconnect.php';

function getQueryParam($paramName)
{
  return isset($_GET[$paramName]) ? $_GET[$paramName] : '';
}
$gender = getQueryParam('gender');
$postalCode = getQueryParam('postalCode');
$age = getQueryParam('age');

// Determine the SQL query based on input
if ($gender != '' && $postalCode != '' && $age != '') {
  // Search by gender, postal code, and age
  $sql = "SELECT `商品資料`.`商品中文名稱` ,`保單資料`.`商品英文代碼`  , COUNT(*) as `購買次數`
    FROM `客戶資料`
    JOIN `保單要保人` ON `客戶資料`.`客戶序號` = `保單要保人`.`要保人序號`
    JOIN `保單資料` ON `保單要保人`.`保單序號` = `保單資料`.`保單序號`
    JOIN `商品資料` ON `保單資料`.`商品英文代碼` = `商品資料`.`商品英文代碼`
    WHERE `客戶性別` = '$gender' 
      AND `客戶郵遞區號` = '$postalCode' 
      AND `客戶年齡` BETWEEN '$age' - 5 AND '$age' + 5
    GROUP BY `商品資料`.`商品中文名稱`
    ORDER BY `購買次數` DESC
    LIMIT 5;
    ";
} elseif ($gender != '' && $postalCode != '') {
  // Search by gender and postal code
  $sql = "SELECT `商品資料`.`商品中文名稱` ,`保單資料`.`商品英文代碼`  , COUNT(*) as `購買次數`
    FROM `客戶資料`
    JOIN `保單要保人` ON `客戶資料`.`客戶序號` = `保單要保人`.`要保人序號`
    JOIN `保單資料` ON `保單要保人`.`保單序號` = `保單資料`.`保單序號`
    JOIN `商品資料` ON `保單資料`.`商品英文代碼` = `商品資料`.`商品英文代碼`
    WHERE `客戶性別` = '$gender' 
      AND `客戶郵遞區號` = '$postalCode' 
    GROUP BY `商品資料`.`商品中文名稱`
    ORDER BY `購買次數` DESC
    LIMIT 5;"; // SQL for gender and postal code
} elseif ($gender != '' && $age != '') {
  // Search by gender and age
  $sql = "SELECT `商品資料`.`商品中文名稱` ,`保單資料`.`商品英文代碼`  , COUNT(*) as `購買次數`
    FROM `客戶資料`
    JOIN `保單要保人` ON `客戶資料`.`客戶序號` = `保單要保人`.`要保人序號`
    JOIN `保單資料` ON `保單要保人`.`保單序號` = `保單資料`.`保單序號`
    JOIN `商品資料` ON `保單資料`.`商品英文代碼` = `商品資料`.`商品英文代碼`
    WHERE `客戶性別` = '$gender' 
      AND `客戶年齡` BETWEEN '$age' - 5 AND '$age' + 5
    GROUP BY `商品資料`.`商品中文名稱`
    ORDER BY `購買次數` DESC
    LIMIT 5;"; // SQL for gender and age
} elseif ($postalCode != '' && $age != '') {
  // Search by postal code and age
  $sql = "SELECT `商品資料`.`商品中文名稱` ,`保單資料`.`商品英文代碼`  , COUNT(*) as `購買次數`
    FROM `客戶資料`
    JOIN `保單要保人` ON `客戶資料`.`客戶序號` = `保單要保人`.`要保人序號`
    JOIN `保單資料` ON `保單要保人`.`保單序號` = `保單資料`.`保單序號`
    JOIN `商品資料` ON `保單資料`.`商品英文代碼` = `商品資料`.`商品英文代碼`
    WHERE  `客戶郵遞區號` = '$postalCode' 
      AND `客戶年齡` BETWEEN '$age' - 5 AND '$age' + 5
    GROUP BY `商品資料`.`商品中文名稱`
    ORDER BY `購買次數` DESC
    LIMIT 5;"; // SQL for postal code and age
}

$result = $conn->query($sql);
$data = [];
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $data[] = [$row['商品中文名稱'], $row['商品英文代碼'], $row['購買次數']];
  }
}
echo json_encode($data);
?>