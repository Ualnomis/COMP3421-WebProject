<?php
include('./classes/cart.class.php');
use PHPUnit\Framework\TestCase;

class CartTest extends TestCase
{
    private $conn;
    private $cart;

    protected function setUp(): void
    {
        $this->conn = new mysqli('localhost', 'root', '', 'giftify');
        $this->cart = new Cart($this->conn);
    }

    protected function tearDown(): void
    {
        $this->conn->close();
    }

    public function testInsert()
    {
        // Insert a new cart for a given user ID
        $user_id = 2;
        $cart_id = $this->cart->insert($user_id);
        $this->assertIsInt($cart_id);
    }

    public function testSelect()
    {
        // Select cart data for a given user ID
        $user_id = 2;
        $cart = $this->cart->select($user_id);
        $this->assertIsArray($cart);
    }

    public function testUpdateCartItem()
    {
        // Update the quantity of a cart item
        $cart_item_id = 2;
        $quantity = 2;
        $affected_rows = $this->cart->updateCartItem($cart_item_id, $quantity);
        $this->assertIsInt($affected_rows);
    }

    public function testDeleteCartItem()
    {
        // Delete a cart item
        $cart_item_id = 2;
        $affected_rows = $this->cart->deleteCartItem($cart_item_id);
        $this->assertIsInt($affected_rows);
    }

    public function testRemoveAllCartItem()
    {
        // Remove all cart items
        $cart_id = 3;
        $affected_rows = $this->cart->removeAllCartItem($cart_id);
        $this->assertIsInt($affected_rows);
    }

    public function testAddItem()
    {
        // Add a new item to the cart
        $cart_id = 3;
        $product_id = 10;
        $quantity = 1;
        $affected_rows = $this->cart->addItem($cart_id, $product_id, $quantity);
        $this->assertIsInt($affected_rows);
    }

    public function testGetCartItems()
    {
        // Get cart items for a given cart ID
        $cart_id = 3;
        $cart_items = $this->cart->getCartItems($cart_id);
        $this->assertIsArray($cart_items);
        $this->assertArrayHasKey('cart_items', $cart_items);
        $this->assertArrayHasKey('total_sum_price', $cart_items);
    }

    public function testFindCartItem()
    {
        // Find a cart item for a given cart ID and product ID
        $cart_id = 3;
        $product_id = 10;
        $cart_item = $this->cart->find_cart_item($cart_id, $product_id);
        $this->assertIsArray($cart_item);
    }

    public function testCountItems()
    {
        // Count the number of items in a given cart
        $cart_id = 3;
        $count = $this->cart->countItems($cart_id);
        $this->assertIsInt($count);
    }
}