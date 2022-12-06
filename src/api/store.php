<?php

use App\Models\DB;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/store', function (Request $request, Response $response, $args) {
    $sql = "SELECT *  from store";

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

$app->get('/store/{id}', function (Request $request, Response $response, $args) {
    $id_store = $request->getAttribute('id');
    $sql = "SELECT * FROM store WHERE id = $id_store";

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

$app->post('/store', function (Request $request, Response $response, array $args) {
    $data = $request->getParsedBody();
    $merchant = $data['merchant_id'];
    $name = $data["name"];
    $start_time = $data["start_time"];
    $end_time = $data["end_time"];

    $sql = "SET @p3 = ''; CALL insert_store(:merchant_id, :name, :start_time, :end_time, @p3)";

    try {
        $db = new DB();
        $conn = $db->connect();

        $stmt = $conn->prepare($sql);
        $result = $stmt->execute([
            ':merchant_id' => $merchant,
            ':name' => $name,
            ':start_time' => $start_time,
            ':end_time' => $end_time
        ]);

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

$app->put('/store/{id}', function (Request $request, Response $response, array $args) {
    $id_store = $request->getAttribute('id');

    $data = $request->getParsedBody();
    $name = $data['name'];
    $start_time = $data["start_time"];
    $end_time = $data["end_time"];
    $active = $data["active"];

    $sql = "UPDATE store SET
            name = :name, 
            start_time = :start_time, 
            end_time = :end_time, 
            active = :active 
            WHERE id = :id_store";

    try {
        $db = new DB();
        $conn = $db->connect();

        $stmt = $conn->prepare($sql);

        $result = $stmt->execute([
            ':name' => $name,
            ':start_time' => $start_time,
            ':end_time' => $end_time,
            ':active' => $active,
            ':id_store' => $id_store,
        ]);

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

$app->delete('/store/{id}', function (Request $request, Response $response, array $args) {
    $id_store = $request->getAttribute('id');

    $sql = "CALL delete_store(:id_store);";

    try {
        $db = new DB();
        $conn = $db->connect();

        $stmt = $conn->prepare($sql);
        $result = $stmt->execute([':id_store' => $id_store]);

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
