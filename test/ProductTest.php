<?php
include('./classes/product.class.php');

class ProductTest extends \PHPUnit\Framework\TestCase
{
    private static $conn;

    public static function setUpBeforeClass(): void
    {
        // Set up a database connection
        self::$conn = new mysqli('localhost', 'root', '', 'giftify');
    }

    public function testSelectAll()
    {
        $product = new Product(self::$conn);
        $result = $product->select_all();

        $this->assertInstanceOf(mysqli_result::class, $result);
        $this->assertGreaterThan(0, $result->num_rows);
    }

    public function testSelectOne()
    {
        $product = new Product(self::$conn);
        $result = $product->select_one(2);

        $this->assertInstanceOf(mysqli_result::class, $result);
        $this->assertEquals(1, $result->num_rows);
    }

    public function testInsert()
    {
        $product = new Product(self::$conn);
        $seller_id = 1;
        $name = "Test Product";
        $description = "This is a test product";
        $price = 9.99;
        $quantity = 10;
        $image_url = "http://example.com/image.jpg";
        $status = "show";
        $result = $product->insert($seller_id, $name, $description, $price, $quantity, $image_url, $status);

        $this->assertEquals(null, $result->num_rows);
    }

    public function testUpdate()
    {
        $product = new Product(self::$conn);
        $id = 2;
        $seller_id = 1;
        $name = "Updated Test Product";
        $description = "This is an updated test product";
        $price = 19.99;
        $quantity = 5;
        $image_url = "http://example.com/new_image.jpg";
        $status = "hidden";
        $result = $product->update($id, $seller_id, $name, $description, $price, $quantity, $image_url, $status);

        $this->assertTrue($result);
    }

    public function testDelete()
    {
        $product = new Product(self::$conn);
        $id = 3;
        $result = $product->delete($id);

        $this->assertTrue($result);
    }

    public function testDecreaseProductQuantity()
    {
        $product = new Product(self::$conn);
        $product_id = 1;
        $quantity_to_decrease = 2;
        $result = $product->decrease_product_quantity($product_id, $quantity_to_decrease);

        $this->assertTrue($result);
    }

    public function testSelectByNameOrDescription()
    {
        $product = new Product(self::$conn);
        $search_term = "test";
        $result = $product->select_by_name_or_description($search_term);

        $this->assertInstanceOf(mysqli_result::class, $result);
    }

    public static function tearDownAfterClass(): void
    {
        // Close the database connection
        self::$conn->close();
    }
}