<?php
include_once "../db/db_connection.php";

class Rooms
{
    private $conn;

    public function __construct()
    {
        $dbcon = new DBConnection();
        $this->conn = $dbcon->connect();
    }

    public function get_all_rooms(): false|array|null
    {
        $stmt = $this->conn->prepare("SELECT * FROM rooms");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function get_first_empty_room(): false|array|null
    {
        $stmt = $this->conn->prepare("SELECT id, name FROM rooms WHERE capacity > current_load LIMIT 1");
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function get_number_of_tokens_in_each_room(): false|array|null
    {
        $stmt = $this->conn->prepare("
           SELECT
           r.name AS room_name,
           u.fullname AS supervisor_name,
           COUNT(t.token_id) AS token_count
           FROM rooms r
            LEFT JOIN users u ON r.supervisor_id = u.id
            LEFT JOIN token t ON r.id = t.room_id AND t.status = 'Waiting'
           GROUP BY r.id, r.name, u.fullname;
        ");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function get_room_associated_with_teacher(int $teacher_id): false|array|null
    {
        $stmt = $this->conn->prepare("SELECT * FROM teacher_assignment WHERE user_id = ?");
        $stmt->bind_param("i", $teacher_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function get_room_by_supervisor(int $supervisor_id): false|array|null
    {
        $stmt = $this->conn->prepare("SELECT * FROM rooms WHERE supervisor_id = ?");
        $stmt->bind_param("i", $supervisor_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function get_teachers_in_room(int $room_id): false|array|null
    {
        $stmt = $this->conn->prepare("
            SELECT u.fullname, u.uni_id
            FROM teacher_assignment ta
            JOIN users u ON ta.user_id = u.id
            WHERE ta.room_id = ?
        ");
        $stmt->bind_param("i", $room_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function assign_teacher_to_room(int $teacher_id, int $room_id): bool
    {
        $check = $this->conn->prepare("SELECT user_id FROM teacher_assignment WHERE user_id = ?");
        $check->bind_param("i", $teacher_id);
        $check->execute();
        if ($check->get_result()->fetch_assoc()) {
            return false;
        }

        $stmt = $this->conn->prepare("INSERT INTO teacher_assignment (user_id, room_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $teacher_id, $room_id);
        return $stmt->execute();
    }
}
