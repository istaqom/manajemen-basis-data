<?php

use App\Models\DB;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/orders_menu', function (Request $request, Response $response, $args) {
    $sql = "CALL show_all_orders_menu()";

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

$app->get('/orders_menu/{id}', function (Request $request, Response $response, $args) {
    $id_order_menu = $request->getAttribute('id');
    $sql = "CALL show_orders_menu($id_order_menu)";

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

$app->post('/orders_menu', function (Request $request, Response $response, array $args) {
    $data = $request->getParsedBody();

    $order_id_p = $data['order_id_p'];
    $store_menu_id_p = $data['store_menu_id_p'];
    $amount = $data['amount'];

    $sql = "CALL insert_order_menu_default_price(:order_id_p, :store_menu_id_p, :amount)";

    try {
        $db = new DB();
        $conn = $db->connect();

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':order_id_p', $order_id_p);
        $stmt->bindParam(':store_menu_id_p', $store_menu_id_p);
        $stmt->bindParam(':amount', $amount);

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

$app->put('/orders_menu/{id}', function (Request $request, Response $response, array $args) {
    $id_order_menu = $request->getAttribute('id');

    $data = $request->getParsedBody();

    $store_menu_id_p = $data['store_menu_id_p'];
    $amount = $data['amount'];

    $sql = "CALL update_order_menu(:id_orders, :store_menu_id_p, :amount)";

    try {
        $db = new DB();
        $conn = $db->connect();

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_orders', $id_order_menu);
        $stmt->bindParam(':store_menu_id_p', $store_menu_id_p);
        $stmt->bindParam(':amount', $amount);

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

$app->delete('/orders_menu/{id}', function (Request $request, Response $response, array $args) {
    $id_order_menu = $request->getAttribute('id');

    $sql = "CALL delete_order_menu($id_order_menu)";

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
