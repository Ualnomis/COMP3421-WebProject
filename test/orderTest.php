<?php

include('./classes/order.class.php');
include('./classes/product.class.php');
use PHPUnit\Framework\TestCase;

class OrderTest extends TestCase
{
    private $db;

    public function setUp(): void
    {
        $this->db = new mysqli('localhost', 'root', '', 'giftify');
    }

    public function tearDown(): void
    {
        $this->db->close();
    }

    public function testInsertOrder()
    {
        $order = new Order($this->db);
        $insert_id = $order->insert_order(1, 'John', '555-1234', '123 Main St', 2);
        $this->assertGreaterThan(0, $insert_id);
    }

    public function testUpdateOrder()
    {
        $order = new Order($this->db);
        $affected_rows = $order->update_order(1, 'Jane', '555-5678', '456 Oak St', 3);
        $this->assertEquals(1, $affected_rows);
    }

    public function testGetOrdersByUserId()
    {
        $order = new Order($this->db);
        $orders = $order->get_orders_by_user_id(1);
        $this->assertIsArray($orders);
        $this->assertNotEmpty($orders);
    }

    public function testGetOrderById()
    {
        $order = new Order($this->db);
        $order_data = $order->get_order_by_id(1);
        $this->assertIsArray($order_data);
        $this->assertArrayHasKey('id', $order_data);
    }

    public function testGetAllOrderStatus()
    {
        $order = new Order($this->db);
        $order_status = $order->get_all_order_status();
        $this->assertIsArray($order_status);
        $this->assertNotEmpty($order_status);
    }

    public function testGetAllOrders()
    {
        $order = new Order($this->db);
        $orders = $order->get_all_orders();
        $this->assertIsArray($orders);
        $this->assertNotEmpty($orders);
    }

    public function testInsertOrderItem()
    {
        $order = new Order($this->db);
        $insert_id = $order->insert_order_item(1, 1, 2, 9.99);
        $this->assertGreaterThan(0, $insert_id);
    }

    public function testUpdateOrderItem()
    {
        $order = new Order($this->db);
        $affected_rows = $order->update_order_item(1, 1, 2, 3, 12.99);
        $this->assertEquals(1, $affected_rows);
    }

    public function testGetOrderItemById()
    {
        $order = new Order($this->db);
        $order_item = $order->get_order_item_by_id(1);
        $this->assertIsArray($order_item);
        $this->assertArrayHasKey('id', $order_item);
    }
}