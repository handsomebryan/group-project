<?php
include '../dbconnect.php';

function getQueryParam($paramName)
{
    return isset($_GET[$paramName]) ? $_GET[$paramName] : '';
}

$id = getQueryParam('id');
$c_id = getQueryParam('c_id');


$sql ="SELECT
CONCAT('要保人', RIGHT(要保人序號, 5)) AS 要保人序號_last5,
CONCAT('被保人', RIGHT(被保人序號, 5)) AS 被保人序號_last5
FROM 保單要保人
JOIN 保單被保人 ON 保單被保人.保單序號 = 保單要保人.保單序號
JOIN 業務員保單序號 ON 業務員保單序號.保單序號 = 保單被保人.保單序號
JOIN 保單資料 ON 保單資料.保單序號 = 保單被保人.保單序號
WHERE 業務員保單序號.業務員序號 LIKE '%$id' 
AND RIGHT(要保人序號, 5) = '$c_id'
";

$result = $conn->query($sql);

$graphData = [];

if ($result->num_rows > 0) {

    while ($row = $result->fetch_assoc()) {
        $source = $row["要保人序號_last5"];
        $target = $row["被保人序號_last5"];

        // Add edges to the graph data array
        if (!isset($graphData[$source])) {
            $graphData[$source] = [];
        }
        $graphData[$source][] = $target;
    }
} else {
    echo "0 results";
}

// Function to create the dot representation of the graph
function createDotFile($graphData, $title)
{
    $dotFileContent = "digraph G {\n";
    $dotFileContent .= "graph [fontname=\"Tahoma\"];\n";
    $dotFileContent .= "labelloc=\"t\";\n"; // Position label at the top
    $dotFileContent .= "label=\"$title\";\n"; // Set the label as the title
    $dotFileContent .= "splines=ortho;\n";
    $dotFileContent .= "node [height=0.1,fontname=\"Tahoma\"];\n"; // Set a minimum height for nodes
    
    foreach ($graphData as $node => $edges) {
        foreach ($edges as $edge) {
            // Define the edge without dir attribute
            $dotFileContent .= "\"$node\" -> \"$edge\" [len=2.0];\n";
        }
    }
    
    $dotFileContent .= "}";
    return $dotFileContent;
}

// Generate the dot file content
$dotContent = createDotFile($graphData, "要保人 ".$c_id." 的客戶關係圖");

// Save the dot content to a file in the 'assets/images' directory
file_put_contents("../../assets/images/1.1spec/graph1_{$id}_{$c_id}.dot", $dotContent);

// Run Graphviz to generate the diagram and save it to the 'assets/images' directory
file_put_contents("../../assets/images/1.1spec/graph1_{$id}_{$c_id}.dot", $dotContent);
shell_exec("neato -Tpng -Gnodesep=2 -Granksep=2 ../../assets/images/1.1spec/graph1_{$id}_{$c_id}.dot -o ../../assets/images/1.1spec/graph1_{$id}_{$c_id}.png");

// Close the database connection
$conn->close();
?>
