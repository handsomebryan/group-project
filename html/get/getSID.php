<?php
include '../dbconnect.php';

$sql = "SELECT DISTINCT RIGHT(業務員序號, 5) AS id FROM 業務員保單序號 WHERE 是否服務業務員=0 ORDER BY id";
$result = $conn->query($sql);

$data = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

echo json_encode($data);
?>
