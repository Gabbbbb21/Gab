<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../src/vendor/autoload.php';

$app = new \Slim\App;

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "demo";

// POST endpoint for inserting data
$app->post('/postName', function (Request $request, Response $response, array $args) use ($servername, $username, $password, $dbname) {
    // Parse the JSON data from the request body
    $data = json_decode($request->getBody());
    $fname = $data->fname;
    $lname = $data->lname;

    try {
        // Create a new PDO database connection
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // SQL statement to insert data into the 'names' table
        $sql = "INSERT INTO names (fname, lname) VALUES (:fname, :lname)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':fname', $fname);
        $stmt->bindParam(':lname', $lname);
        $stmt->execute();

        // Respond with a success message and the inserted data
        $response->getBody()->write(json_encode(array("status" => "success", "data" => $data)));
    } catch (PDOException $e) {
        // Handle database errors and respond with an error message
        $response->getBody()->write(json_encode(array("status" => "error", "message" => $e->getMessage())));
    }

    $conn = null;
});

// GET endpoint for retrieving data
$app->get('/getName', function (Request $request, Response $response, array $args) use ($servername, $username, $password, $dbname) {
    try {
        // Create a new PDO database connection
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // SQL statement to select all data from the 'names' table
        $stmt = $conn->prepare("SELECT * FROM names");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($data)) {
            // Respond with a success message and the retrieved data
            $response->getBody()->write(json_encode(array("status" => "success", "data" => $data)));
        } else {
            // Respond with a success message and null data (no records found)
            $response->getBody()->write(json_encode(array("status" => "success", "data" => null)));
        }
    } catch (PDOException $e) {
        // Handle database errors and respond with an error message
        $response->getBody()->write(json_encode(array("status" => "error", "message" => $e->getMessage())));
    }

    $conn = null;
});

// DELETE endpoint for deleting data by ID
$app->delete('/deleteName/{id}', function (Request $request, Response $response, array $args) use ($servername, $username, $password, $dbname) {
    // Get the 'id' parameter from the URL
    $id = $args['id'];

    try {
        // Create a new PDO database connection
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // SQL statement to delete a record from the 'names' table based on 'id'
        $stmt = $conn->prepare("DELETE FROM names WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        // Respond with a success message
        $response->getBody()->write(json_encode(array("status" => "success", "message" => "Data Successfully deleted")));
    } catch (PDOException $e) {
        // Handle database errors and respond with an error message
        $response->getBody()->write(json_encode(array("status" => "error", "message" => $e->getMessage())));
    }

    $conn = null;
});

// PUT endpoint for updating data by ID
$app->put('/updateName/{id}', function (Request $request, Response $response, array $args) use ($servername, $username, $password, $dbname) {
    // Get the 'id' parameter from the URL
    $id = $args['id'];

    // Parse the JSON data from the request body
    $data = json_decode($request->getBody());
    $fname = $data->fname;
    $lname = $data->lname;

    try {
        // Create a new PDO database connection
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // SQL statement to update a record in the 'names' table based on 'id'
        $stmt = $conn->prepare("UPDATE names SET fname = :fname, lname = :lname WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':fname', $fname);
        $stmt->bindParam(':lname', $lname);
        $stmt->execute();

        // Respond with a success message
        $response->getBody()->write(
            json_encode(
                array
                (
                    "status" => "success",
                    "message" => "Data updated",
                    "data" => [
                        "fname" => $fname,
                        "lname" => $lname
                    ]
                )
            )
        );
    } catch (PDOException $e) {
        // Handle database errors and respond with an error message
        $response->getBody()->write(json_encode(array("status" => "error", "message" => $e->getMessage())));
    }

    $conn = null;
});

$app->run();