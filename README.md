# My API

WeatherInfo API

Our API, named 'WeatherInfo,' is a real-time weather data service that allows developers to access up-to-date weather information for any location worldwide. With simple RESTful endpoints, you can retrieve current weather conditions, forecasts, and historical weather data. Use WeatherInfo to enhance your applications with accurate weather information and provide users with essential weather updates.

## API Description

An API, or Application Programming Interface, is a set of rules and protocols that allows one software application to interact with and request data or services from another application, service, or system. It serves as an intermediary that enables different software components to communicate and exchange information in a standardized way. APIs are commonly used to access and integrate data or functionality from external sources, such as web services, databases, or hardware devices, into an application. They play a crucial role in modern software development, enabling interoperability and the building of more robust and feature-rich applications.

APIs come in various forms and serve different purposes. Here are some key points about APIs and their functions:

Purpose: APIs exist to allow different software systems to communicate with each other and exchange data or services. They are like bridges that enable one application to access the capabilities of another, without needing to understand the underlying complexity.

Key Features:

a. Standardized Communication: APIs define a set of rules, requests, and responses that both the requesting application (client) and the providing application (server) must follow. This standardization ensures consistent and predictable interactions.

b. Data Exchange: APIs can be used to retrieve data from a remote server or to send data to it. For example, social media platforms provide APIs that allow developers to retrieve user profiles, post updates, or interact with the platform's features.

c. Functionality Access: APIs can grant access to specific functions or features of an application. For instance, payment gateways offer APIs that developers can use to integrate payment processing into their applications.

## API Endpoints

Endpoint: An endpoint is a specific URL or URI (Uniform Resource Identifier) where you can make requests to interact with the API. Each endpoint typically corresponds to a specific function or resource provided by the API.

Function: The function of an endpoint describes what it allows you to do or retrieve. Some common functions include:

GET: Retrieve data from the API, such as reading information, fetching records, or querying resources.
POST: Create new data on the API server, such as submitting a form, adding a new record, or making a reservation.
PUT: Update existing data on the server, often identified by a unique identifier like an ID.
DELETE: Remove data or resources from the server.

## Request Payload

JSON request payload for postName: Structure:
```
{ "lname":"necida", "fname":"justine" }
```
Required Fields: 
    lname (string): The last name of the person.
    fname (string): The first name of the person.

JSON request payload updateName: Structure:
```
{ "id":1, "lname":"necida", "fname":"justine" }
```
Required Fields: 
    id (integer): Identifier for the person.
    lname (string): The last name of the person.
    fname (string): The first name of the person.

JSON request paylod deleteName: Structure:
```
{ "id": 1 }
```
Required Fields: 
    id (integer): Identifier for the person.

## Response

Status Code: The HTTP status code indicates the outcome of the API request. Common status codes include:

200 OK: The request was successful, and the response contains the requested data.
201 Created: The request successfully created a new resource.
204 No Content: The request was successful, but there is no data to return.
400 Bad Request: The request is invalid or missing required parameters.
401 Unauthorized: Authentication is required, or the provided credentials are invalid.
403 Forbidden: The request is understood, but the server refuses to fulfill it.
404 Not Found: The requested resource does not exist.
500 Internal Server Error: An unexpected server error occurred.

Response Body: The response body contains the data returned by the API. The structure and content of the response body depend on the specific API and endpoint. Responses are often formatted as JSON, but XML or other formats can be used as well.

Successful Response (200 OK) - JSON Example:
```
{
  "id": 123,
  "name": "John Doe",
  "email": "john.doe@example.com"
}
```

Resource Created Response (201 Created) - JSON Example:
```
{
  "id": 456,
  "message": "Resource created successfully."
}
```

Bad Request Response (400 Bad Request) - JSON Example:
```
{
  "error": "Invalid input data. Please check the provided parameters."
}
```

Not Found Response (404 Not Found) - JSON Example:
```
{
  "error": "The requested resource was not found."
}
```

## Usage

```
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

$app->run();
```

## License

MIT License

## Contributors

Justine Raphael Necida

## Contact Information
