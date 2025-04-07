<?php

/**
 * Aplicación To-Do similar a Microsoft to Do:
 * Campos tabla: id, title, description, vencimiento, prioritario, completed
 * Agregar tarea y eliminar tarea.
 * Si vence la tarea sin completarla, marcarla con rojo
 * Al eliminar la tarea, dar un alert de confirmacion. Igual en el edit
 */
class Database {
    private $host = 'localhost';
    private $user = 'root';
    private $password = 'naruto11';
    private $database = 'task_manager';
    private $conn;

    public function __construct() {
        $this->conn = new mysqli($this->host, $this->user, $this->password, $this->database);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function getConnection() {
        return $this->conn;
    }
}
?>