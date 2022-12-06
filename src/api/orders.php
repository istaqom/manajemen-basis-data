<?php

use App\Models\DB;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/orders', function (Request $request, Response $response, $args) {
    $sql = "CALL show_all_orders()";

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

$app->get('/orders/{id}', function (Request $request, Response $response, $args) {
    $id_store_menu = $request->getAttribute('id');
    $sql = "CALL show_orders($id_store_menu)";

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

$app->post('/orders', function (Request $request, Response $response, array $args) {
    $data = $request->getParsedBody();

    $customer_id = $data['customer_id'];
    $driver_id = $data['driver_id'];
    $store_id = $data['store_id'];
    $shipping = $data['shipping'];

    $sql = "CALL insert_order(:customer_id, :driver_id, :store_id, :shipping)";

    try {
        $db = new DB();
        $conn = $db->connect();

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':customer_id', $customer_id);
        $stmt->bindParam(':driver_id', $driver_id);
        $stmt->bindParam(':store_id', $store_id);
        $stmt->bindParam(':shipping', $shipping);

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

$app->put('/orders/{id}', function (Request $request, Response $response, array $args) {
    $id_order = $request->getAttribute('id');

    $data = $request->getParsedBody();

    $driver_rating = $data['driver_rating'];
    $driver_review = $data['driver_review'];
    $store_rating = $data['store_rating'];
    $store_review = $data['store_review'];
    $tip_amount = $data["tip_amount"];

    $sql = "CALL update_order(:id_order, :driver_rating, :driver_review, :store_rating, :store_review, :tip_amount)";

    try {
        $db = new DB();
        $conn = $db->connect();

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_order', $id_order);
        $stmt->bindParam(':driver_rating', $driver_rating);
        $stmt->bindParam(':driver_review', $driver_review);
        $stmt->bindParam(':store_rating', $store_rating);
        $stmt->bindParam(':store_review', $store_review);
        $stmt->bindParam(':tip_amount', $tip_amount);

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

$app->delete('/orders/{id}', function (Request $request, Response $response, array $args) {
    $id_order = $request->getAttribute('id');

    $sql = "CALL delete_order($id_order)";

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
