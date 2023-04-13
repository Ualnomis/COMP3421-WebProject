<?php
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    private $conn;
    private $user;

    protected function setUp(): void
    {
        $this->conn =  new mysqli("localhost", "root", "", "giftify");
        $this->user = new User($this->conn);
    }

    protected function tearDown(): void
    {
        $this->conn->close();
    }

    public function testLoginWithValidCredentialsReturnsTrue()
    {
        // Arrange
        $email = "admin@gmail.com";
        $password = "admin";
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert a new user with valid credentials
        $stmt = $this->conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", "John", $email, $hashed_password, "user");
        $stmt->execute();

        // Act
        $result = $this->user->login($email, $password);

        // Assert
        $this->assertTrue($result);
    }

    public function testLoginWithInvalidCredentialsReturnsFalse()
    {
        // Arrange
        $email = "jane@example.com";
        $password = "password123";
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert a new user with different email and password
        $stmt = $this->conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", "Jane", "jane@example.com", $hashed_password, "user");
        $stmt->execute();

        // Act
        $result = $this->user->login($email, $password);

        // Assert
        $this->assertFalse($result);
    }
}