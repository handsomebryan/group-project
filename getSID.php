<?php
include '../dbconnect.php';

$sql = "SELECT DISTINCT RIGHT(業務員序號, 5) AS id FROM 服務招攬業務員";
$result = $conn->query($sql);

$data = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

echo json_encode($data);
?>

