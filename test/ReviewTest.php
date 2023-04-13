<?php
include('./classes/review.class.php');
use PHPUnit\Framework\TestCase;

class ReviewTest extends TestCase
{
    private $conn;
    private $review;

    protected function setUp(): void
    {
        $this->conn = new mysqli('localhost', 'root', '', 'giftify');
        $this->review = new Review($this->conn);
    }

    protected function tearDown(): void
    {
        $this->conn->close();
    }

    public function testSelectByProductId()
    {
        // Test selecting reviews for a product with ID 1
        $result = $this->review->selectByProductId(2);
        $this->assertEquals(3, $result->num_rows);
    }

    public function testInsert()
    {
        // Test inserting a new review
        $this->review->insert(2, 2, 4, "Great product!");
        $result = $this->conn->query("SELECT * FROM reviews WHERE product_id = 2");
        $this->assertEquals(4, $result->num_rows);
    }
}
?>