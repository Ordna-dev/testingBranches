<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Manejar petición OPTIONS (preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require_once 'Task.php';

$task = new Task();
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Si viene un ID, devolver solo esa tarea
        if (isset($_GET['id'])) {
            echo json_encode($task->getById($_GET['id']));
        } else {
            echo json_encode($task->getAll());
        }
        break;
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        $success = $task->create(
            $data['title'],
            $data['description'],
            $data['vencimiento'] ?? null,
            $data['prioritario'] ?? 0
        );
        echo json_encode(['success' => $success]);
        break;
    case 'DELETE':
        $id = $_GET['id'];
        $success = $task->delete($id);
        echo json_encode(['success' => $success]);
        break;
    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['completed']) && !isset($data['title'])) {
            // Actualización solo de estado
            $success = $task->updateStatus($data['id'], $data['completed']);
        } else {
            // Actualización completa de la tarea
            $success = $task->update(
                $data['id'],
                $data['title'],
                $data['description'],
                $data['vencimiento'] ?? null,
                $data['prioritario'] ?? 0,
                $data['completed'] ?? 0
            );
        }
        echo json_encode(['success' => $success]);
        break;
}
?>