<?php

use App\Models\DB;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();
$app->addBodyParsingMiddleware();

$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Hello world!");
    return $response;
});

$app->get('/user', function (Request $request, Response $response, $args) {
    $sql = "CALL show_user()";

    try {
        $db = new DB();
        $conn = $db->connect();
        $stmt = $conn->query($sql);
        $customers = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        $response->getBody()->write(json_encode($customers));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    } catch (PDOException $e) {
        $error = array(
            "message" => $e->getMessage()
        );

        $response->getBody()->write(json_encode($error));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(500);
    }
});

$app->get('/user/{id}', function (Request $request, Response $response, $args) {
    $id_user = $request->getAttribute('id');
    $sql = "SELECT * FROM user WHERE id = $id_user";

    try {
        $db = new DB();
        $conn = $db->connect();
        $stmt = $conn->query($sql);
        $customers = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        $response->getBody()->write(json_encode($customers));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    } catch (PDOException $e) {
        $error = array(
            "message" => $e->getMessage()
        );

        $response->getBody()->write(json_encode($error));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(500);
    }
});

$app->post('/user', function (Request $request, Response $response, array $args) {
    $data = $request->getParsedBody();
    $name = $data["name"];
    $username = $data["username"];
    $password = $data["password"];

    $sql = "SET @p3 = ''; CALL insert_user(:name, :username, :password, @p3)";

    try {
        $db = new DB();
        $conn = $db->connect();

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);

        $result = $stmt->execute();

        $db = null;
        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    } catch (PDOException $e) {
        $error = array(
            "message" => $e->getMessage()
        );

        $response->getBody()->write(json_encode($error));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(500);
    }
});

$app->put('/user/{id}', function (Request $request, Response $response, array $args) {
    $id_user = $request->getAttribute('id');

    $data = $request->getParsedBody();
    $name = $data["name"];
    $username = $data["username"];
    $password = $data["password"];

    $sql = "UPDATE user SET 
            name = :name, 
            username = :username, 
            password = :password 
            WHERE id = $id_user";

    try {
        $db = new DB();
        $conn = $db->connect();

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);

        $result = $stmt->execute();

        $db = null;
        echo "Update successful! ";
        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    } catch (PDOException $e) {
        $error = array(
            "message" => $e->getMessage()
        );

        $response->getBody()->write(json_encode($error));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(500);
    }
});

$app->delete('/user/{id}', function (Request $request, Response $response, array $args) {
    $id_user = $request->getAttribute('id');

    // $sql = "DELETE FROM user WHERE id = $id_user";
    $sql = "CALL delete_user($id_user)";

    try {
        $db = new DB();
        $conn = $db->connect();

        $stmt = $conn->prepare($sql);
        $result = $stmt->execute();

        $db = null;
        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    } catch (PDOException $e) {
        $error = array(
            "message" => $e->getMessage()
        );

        $response->getBody()->write(json_encode($error));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(500);
    }
});

$app->run();
