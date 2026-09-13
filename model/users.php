<?php
require_once "../db/db_connection.php";

class Users
{
    private $conn;

    public function __construct()
    {
        $dbcon = new DBConnection();
        $this->conn = $dbcon->connect();
    }

    public function create_user($fullname, $uni_id, $password, $role): bool
    {
        $already_exists = $this->user_already_exists($uni_id);
        if (!$already_exists) {
            $stmt = $this->conn->prepare("INSERT INTO users (uni_id, fullname, password, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $uni_id, $fullname, $password, $role);
            return $stmt->execute();
        }
        return false;
    }

    public function verify_login($uni_id, $password): bool
    {
        $stmt = $this->conn->prepare("SELECT password FROM users WHERE uni_id = ?");
        $stmt->bind_param("s", $uni_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return ($password == $row['password']);
        }
        return false;
    }

    public function verify_login_admin($uni_id, $password): bool
    {
        $stmt = $this->conn->prepare("SELECT password FROM users WHERE uni_id = ? AND `role` = ?");
        $role = "admin";
        $stmt->bind_param("ss", $uni_id, $role);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return ($password == $row['password']);
        }
        return false;
    }

    public function verify_login_supervisor($uni_id, $password): bool
    {
        $stmt = $this->conn->prepare("SELECT password FROM users WHERE uni_id = ? AND `role` = ?");
        $role = "supervisor";
        $stmt->bind_param("ss", $uni_id, $role);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return ($password == $row['password']);
        }
        return false;
    }

    public function verify_login_teacher($uni_id, $password): bool
    {
        $stmt = $this->conn->prepare("SELECT password FROM users WHERE uni_id = ? AND `role` = ?");
        $role = "teacher";
        $stmt->bind_param("ss", $uni_id, $role);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return ($password == $row['password']);
        }
        return false;
    }

    public function verify_login_student($uni_id, $password): bool
    {
        $stmt = $this->conn->prepare("SELECT password FROM users WHERE uni_id = ? AND `role` = ?");
        $role = "student";
        $stmt->bind_param("ss", $uni_id, $role);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return ($password == $row['password']);
        }
        return false;
    }

    public function get_user($uni_id): false|array|null
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE uni_id = ?");
        $stmt->bind_param("s", $uni_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function user_already_exists($uni_id): bool
    {
        $stmt = $this->conn->prepare("SELECT uni_id FROM users WHERE uni_id = ?");
        $stmt->bind_param("s", $uni_id);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        if ($user) return true;
        else return false;
    }

    public function get_unassigned_teacher(): false|array|null
    {
        $stmt = $this->conn->prepare("
        SELECT u.id,u.uni_id,u.fullname
        FROM users u
        LEFT JOIN teacher_assignment ta ON u.id=ta.user_id
        WHERE u.role='teacher'AND ta.user_id IS NULL
        ");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
