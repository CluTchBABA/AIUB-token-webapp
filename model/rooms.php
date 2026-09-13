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
