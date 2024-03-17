<?php
include '../dbconnect.php';

function getQueryParam($paramName)
{
    return isset($_GET[$paramName]) ? $_GET[$paramName] : '';
}

$id = getQueryParam('id');

// Query to select data from your database
$sql = "SELECT DISTINCT
RIGHT(業務員序號, 5) AS 業務員序號_last5,
RIGHT(招攬業務員1序號, 5) AS 招攬業務員1序號_last5
FROM 服務招攬業務員
JOIN 保單資料 ON 保單資料.保單序號 = 服務招攬業務員.保單序號
WHERE 服務招攬業務員.業務員序號 LIKE '%$id' AND 保單生效日 >= DATE_SUB(CURDATE(), INTERVAL 10 YEAR)";

$result = $conn->query($sql);


$graphData = [];



if ($result->num_rows > 0) {
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        $source = $row["業務員序號_last5"];
        $target = $row["招攬業務員1序號_last5"];
        

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
function createDotFile($graphData)
{
    $dotFileContent = "digraph G {\n";
    $dotFileContent .= "splines=ortho\n"; // Add this line
    $dotFileContent .= "node [height=0.1];\n"; // Set a minimum height for nodes
    foreach ($graphData as $node => $edges) {
        foreach ($edges as $edge) {
            $dotFileContent .= "\"$node\" -> \"$edge\" [len=1.6];\n"; // Set len to 2.0 for longer lines
        }
    }
    $dotFileContent .= "}";
    return $dotFileContent;
}

// Generate the dot file content
$dotContent = createDotFile($graphData);

// Save the dot content to a file in the 'assets/images' directory
file_put_contents('../../assets/images/graph.dot', $dotContent);

// Run Graphviz to generate the diagram and save it to the 'assets/images' directory
shell_exec('neato -Tpng -Gnodesep=2 -Granksep=2 ../../assets/images/graph.dot -o ../../assets/images/graph.png');

// Output the image to the browser from the 'assets/images' directory
echo '<div class="graphContainer"><img src="../../assets/images/graph.png" alt="Graph Image"></div>';

// Close the database connection
$conn->close();
?>