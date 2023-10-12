
<?php

// Include necessary headers to handle CORS
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");

// Check the HTTP request method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle POST request to insert data
    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->fname) && !empty($data->lname)) {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "demo";

        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $fname = $data->fname;
            $lname = $data->lname;

            $sql = "INSERT INTO names (fname, lname) VALUES (:fname, :lname)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':fname', $fname);
            $stmt->bindParam(':lname', $lname);
            $stmt->execute();

            $response = [
                "status" => "success",
                "message" => "Data inserted successfully",
                "data" => [
                    "fname" => $fname,
                    "lname" => $lname
                ]
            ];

            echo json_encode($response);
        } catch (PDOException $e) {
            $response = [
                "status" => "error",
                "message" => $e->getMessage(),
            ];

            echo json_encode($response);
        }
        $conn = null;
    } else {
        $response = [
            "status" => "error",
            "message" => "Missing data in the request body",
        ];

        echo json_encode($response);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Handle GET request to extract data
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "demo";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT fname, lname FROM names";
        $stmt = $conn->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $response = [
            "status" => "success",
            "data" => $result,
        ];

        echo json_encode($response);
    } catch (PDOException $e) {
        $response = [
            "status" => "error",
            "message" => $e->getMessage(),
        ];

        echo json_encode($response);
    }
    $conn = null;
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->id)) {
        // Assuming 'id' is the unique identifier field in your database
        $id = $data->id;

        // Your database connection code
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "demo";

        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // SQL statement to delete a record based on ID
            $sql = "DELETE FROM names WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                // Record was deleted successfully
                $response = [
                    "status" => "success",
                    "message" => "Record with ID $id deleted successfully",
                ];
            } else {
                // No records were deleted (ID not found)
                $response = [
                    "status" => "error",
                    "message" => "Record with ID $id not found",
                ];
            }

            echo json_encode($response);
        } catch (PDOException $e) {
            $response = [
                "status" => "error",
                "message" => $e->getMessage(),
            ];

            echo json_encode($response);
        }
        $conn = null;
    } else {
        $response = [
            "status" => "error",
            "message" => "Missing 'id' in the request body",
        ];
    echo json_encode($response);
} 
?>
