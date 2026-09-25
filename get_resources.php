<?php

include "db.php";

$sql = "SELECT * FROM resources ORDER BY upload_date DESC";

$result = $conn->query($sql);

$resources = [];

if ($result) {

    while ($row = $result->fetch_assoc()) {
        $resources[] = $row;
    }

}

header("Content-Type: application/json");

echo json_encode($resources);

$conn->close();

?>