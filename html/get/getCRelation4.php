<?php
include '../dbconnect.php';

function getQueryParam($paramName)
{
    return isset($_GET[$paramName]) ? $_GET[$paramName] : '';
}

$id = getQueryParam('id');

// Query to select data from your database
$sql ="SELECT
CONCAT('要保人', RIGHT(a.要保人序號, 5)) AS 要保人序號_last5,
CONCAT('被保人', RIGHT(b.被保人序號, 5)) AS 被保人序號_last5
FROM 保單要保人 a
JOIN 保單被保人 b ON b.保單序號 = a.保單序號
JOIN 業務員保單序號 c ON c.保單序號 = b.保單序號
JOIN 保單資料 d ON d.保單序號 = b.保單序號
WHERE c.業務員序號 LIKE '%$id' AND a.要保人序號 != b.被保人序號
AND a.要保人序號 IN (
    SELECT 要保人序號
    FROM 保單要保人
    JOIN 保單被保人  ON 保單被保人.保單序號 = 保單要保人.保單序號
    where 要保人序號 != 被保人序號
    GROUP BY 要保人序號
    HAVING COUNT(*) > 3
)
";

$result = $conn->query($sql);

$graphData = [];

if ($result->num_rows > 0) {
    // Output data of each row
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
$dotContent = createDotFile($graphData, "業務員 ".$id." 的客戶關係圖（3個以上被保人）");

// Save the dot content to a file in the 'assets/images' directory
file_put_contents("../../assets/images/1.1/graph1_4_$id.dot", $dotContent);

// Run Graphviz to generate the diagram and save it to the 'assets/images' directory
shell_exec("neato -Tpng -Gnodesep=2 -Granksep=2 ../../assets/images/1.1/graph1_4_$id.dot -o ../../assets/images/1.1/graph1_4_$id.png");

// Close the database connection
$conn->close();
?>
