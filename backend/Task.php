<?php
require_once 'Database.php';

class Task {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getAll() {
        $result = $this->db->query("SELECT * FROM tasks");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM tasks WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function create($title, $description, $vencimiento = null, $prioritario = 0) {
        $stmt = $this->db->prepare("INSERT INTO tasks (title, description, vencimiento, prioritario) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $title, $description, $vencimiento, $prioritario);
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM tasks WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function updateStatus($id, $completed) {
        $stmt = $this->db->prepare("UPDATE tasks SET completed = ? WHERE id = ?");
        $completed = (int)$completed;
        $stmt->bind_param("ii", $completed, $id);
        return $stmt->execute();
    }

    public function update($id, $title, $description, $vencimiento, $prioritario, $completed) {
        $stmt = $this->db->prepare("UPDATE tasks SET title = ?, description = ?, vencimiento = ?, prioritario = ?, completed = ? WHERE id = ?");
        $prioritario = (int)$prioritario;
        $completed = (int)$completed;
        $stmt->bind_param("sssiii", $title, $description, $vencimiento, $prioritario, $completed, $id);
        return $stmt->execute();
    }
}
?>