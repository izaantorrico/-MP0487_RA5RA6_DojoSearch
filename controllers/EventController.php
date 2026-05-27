<?php
require_once 'db_connection.php';

class EventController
{

private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getAllEvents()
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM events ORDER BY date ASC");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function handleForm()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['createEvent'])) {
                $this->createEvent();
            } else if (isset($_POST['deleteEvent'])) {
                $this->deleteEvent();
            } else if (isset($_POST['editEvent'])) {
                $this->updateEvent();
            }
        }
    }

    private function createEvent()
    {
        $name = $_POST['name'] ?? '';
        $description = $_POST['description'] ?? '';
        $date = $_POST['date'] ?? '';
        $location = $_POST['location'] ?? '';

        try {
            $stmt = $this->conn->prepare("INSERT INTO events (name, description, date, location) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $description, $date, $location]);
            header("Location: ../views/php/manageEvents.php");
        } catch (PDOException $e) {
            echo "Error al crear evento: " . $e->getMessage();
        }
    }




    private function deleteEvent()
    {
        $id = $_POST['id'] ?? '';
        try {
            $stmt = $this->conn->prepare("DELETE FROM events WHERE id = ?");
            $stmt->execute([$id]);
            header("Location: ../views/php/events.php");
            exit;
        } catch (PDOException $e) {
            echo "Error al eliminar evento: " . $e->getMessage();
        }
    }

    private function updateEvent()
    {
        $id = $_POST['id'] ?? '';
        $name = $_POST['name'] ?? '';
        $description = $_POST['description'] ?? '';
        $date = $_POST['date'] ?? '';
        $location = $_POST['location'] ?? '';

        try {
            $stmt = $this->conn->prepare("UPDATE events SET name = ?, description = ?, date = ?, location = ? WHERE id = ?");
            $stmt->execute([$name, $description, $date, $location, $id]);
            header("Location: ../views/php/events.php");
            exit;
        } catch (PDOException $e) {
            echo "Error al actualizar evento: " . $e->getMessage();
        }
    }
}

$controller = new EventController($conn);
$controller->handleForm();
