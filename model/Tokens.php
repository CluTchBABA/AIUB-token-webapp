<?php
include_once "../db/db_connection.php";

class Tokens
{
    private $conn;

    public function __construct()
    {
        $dbcon = new DBConnection();
        $this->conn = $dbcon->connect();
    }

    public function currently_being_served($room_id): false|array|null
    {
        $stmt = $this->conn->prepare("SELECT token_id FROM token WHERE status = 'Waiting' AND room_id = ?");
        $stmt->bind_param("i", $room_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function update_token_status($token_id, $new_status)
    {
        $stmt = $this->conn->prepare("UPDATE token SET status = ?, updated_at = CURRENT_TIMESTAMP WHERE token_id = ?");
        $stmt->bind_param("si", $new_status, $token_id);
        return $stmt->execute();
    }

    public function generateToken(int $user_id, int $room_id): int|false
    {
        $already_exists = $this->token_already_exists($user_id);
        if (!$already_exists) {
            $stmt = $this->conn->prepare("INSERT INTO token (user_id, room_id) VALUES (?, ?)");
            $stmt->bind_param("ii", $user_id, $room_id);

            if ($stmt->execute()) {
                return (int)$this->conn->insert_id;
            }
        }
        return false;
    }

    public function token_already_exists(int $user_id): bool
    {
        $stmt = $this->conn->prepare("SELECT user_id FROM token WHERE user_id = ? AND status = 'Waiting'");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $token = $stmt->get_result()->fetch_assoc();
        return (bool)$token;
    }

    public function get_waiting_token_for_user(int $user_id): false|array|null
    {
        $stmt = $this->conn->prepare("SELECT token_id, room_id FROM token WHERE user_id = ? AND status = 'Waiting'");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function get_waiting_token_for_uni_id(string $uni_id): false|array|null
    {
        $stmt = $this->conn->prepare("SELECT t.token_id FROM token t, users u WHERE t.user_id = u.id AND u.uni_id = ? AND t.status = 'Waiting'");
        $stmt->bind_param("s", $uni_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function teacher_view_tokens($room_id): false|array|null
    {
        $stmt = $this->conn->prepare("SELECT t.token_id, u.fullname FROM token t, users u WHERE t.room_id = ? AND t.user_id = u.id AND t.status = 'Waiting'");
        $stmt->bind_param("i", $room_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function get_tokens_before(int $token_id): array
    {
        $stmt = $this->conn->prepare("SELECT t1.token_id, u.fullname FROM token t1, token t2, users u WHERE t2.token_id = ? AND t1.room_id = t2.room_id AND t1.user_id = u.id AND t1.status = 'Waiting' AND t1.token_id < t2.token_id ORDER BY t1.token_id ASC");
        $stmt->bind_param("i", $token_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }


    public function get_tokens_after(int $token_id): array
    {
        $stmt = $this->conn->prepare("SELECT t1.token_id, u.fullname FROM token t1, token t2, users u WHERE t2.token_id = ? AND t1.room_id = t2.room_id AND t1.user_id = u.id AND t1.status = 'Waiting' AND t1.token_id > t2.token_id ORDER BY t1.token_id ASC");
        $stmt->bind_param("i", $token_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }


}