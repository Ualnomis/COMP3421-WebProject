<?php
include('./classes/user.class.php');
// set_include_path(get_include_path() . PATH_SEPARATOR . '../classes/user.class.php');

use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    private $conn;
    private $user;

    protected function setUp(): void
    {
        $this->conn = new mysqli('localhost', 'root', '', 'giftify');
        $this->user = new User($this->conn);
    }

    public function testRegister()
    {
        // Test registering a new user
        $result = $this->user->register('testuser', 'testuser@example.com', 'password', 'buyer');
        $this->assertTrue($result);

        // Test registering a duplicate email address
        $result = $this->user->register('testuser008', 'testuser@example.com', 'abc123', 'buyer');
        $this->assertFalse($result);
    }

    public function testLogin()
    {
        // Test logging in with correct credentials
        $result = $this->user->login('testuser@example.com', 'password');
        $this->assertTrue($result);

        // Test logging in with incorrect password
        $result = $this->user->login('testuser@example.com', 'wrongpassword');
        $this->assertFalse($result);

        // Test logging in with non-existent email
        $result = $this->user->login('nonexistent@example.com', 'password');
        $this->assertFalse($result);
    }

    public function testSelectUserByID()
    {
        // Test selecting a user by ID
        $user = $this->user->selectUserByID(1);
        $this->assertEquals($user['username'], 'admin');
    }

    public function testGetAllUsers()
    {
        // Test getting all users
        $users = $this->user->getAllUsers();
        $this->assertGreaterThan(0, count($users));
    }

    public function testUpdateUser()
    {
        // Test updating a user's information
        $this->user->updateUser('newemail@example.com', 'seller', 'newpassword', 1);
        $updatedUser = $this->user->selectUserByID(1);
        $this->assertEquals($updatedUser['email'], 'newemail@example.com');
        $this->assertEquals($updatedUser['role'], 'seller');
    }

    protected function tearDown(): void
    {
        $this->user = null;
        $this->conn->close();
    }

}