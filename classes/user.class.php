<?php

class User 
{ 
    private $conn;
    private $table_name = "users";
    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function register($username, $email, $password, $role)
    {
        // Check if email already exists in database
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->table_name . " WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            // Email already exists, return false
            return false;
        }

        // Email does not exist, insert new record
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("INSERT INTO " . $this->table_name . " (username, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $email, $hashed_password, $role);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function login($email, $password)
    {
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->table_name . " WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['username'];
                $_SESSION['user_icon'] = $user['icon_url'];
                $_SESSION['role'] = $user['role'];
                return true;
            }
        }
        return false;
    }

    public function logout()
    {
        session_destroy();
        session_unset();
    }

    
    public function selectUserByID($id) 
    {
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $user;
    }

    public function getAllUsers()
    {
        $sql = "SELECT id, username, email, role FROM users";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $users = $result->fetch_all(MYSQLI_ASSOC);
        return $users;
    }

    public function updateUser($email, $role, $password, $id )
    {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("UPDATE users SET email=?, role=?, password=? WHERE id=?");
        $stmt->bind_param("sssi", $email,$role, $hashed_password, $id);
        $stmt->execute();
        $stmt->close();
    }

}

?>