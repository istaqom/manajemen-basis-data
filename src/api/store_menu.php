<?php

use App\Models\DB;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/store_menu', function (Request $request, Response $response, $args) {
    $sql = "CALL show_all_store_menu()";

    try {
        $db = new DB();
        $conn = $db->connect();
        $stmt = $conn->query($sql);
        $customers = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        $response->getBody()->write(json_encode(['data' => $customers]));
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

$app->get('/store_menu/{id}', function (Request $request, Response $response, $args) {
    $id_store_menu = $request->getAttribute('id');
    $sql = "SELECT * FROM store_menu WHERE id = $id_store_menu";

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

$app->post('/store_menu', function (Request $request, Response $response, array $args) {
    $data = $request->getParsedBody();

    $store_id = $data["store_id"];
    $name = $data["name"];
    $unit_price = $data["unit_price"];

    $sql = "CALL insert_store_menu(:store_id, :name, :unit_price)";

    try {
        $db = new DB();
        $conn = $db->connect();

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':store_id', $store_id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':unit_price', $unit_price);

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

$app->put('/store_menu/{id}', function (Request $request, Response $response, array $args) {
    $id_user = $request->getAttribute('id');

    $data = $request->getParsedBody();

    $store_id = $data["store_id"];
    $name = $data["name"];
    $unit_price = $data["unit_price"];

    $sql = "UPDATE store_menu SET 
            name = :name, 
            unit_price = :unit_price
            WHERE id = :store_id";

    try {
        $db = new DB();
        $conn = $db->connect();

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':store_id', $store_id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':unit_price', $unit_price);

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

$app->delete('/store_menu/{id}', function (Request $request, Response $response, array $args) {
    $id_user = $request->getAttribute('id');

    $sql = "CALL delete_store_menu($id_user)";

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
