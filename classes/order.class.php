<?php
class Order
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function insert_order($buyer_id, $buyer_name, $buyer_phone, $buyer_address, $status_id)
    {
        $query = "INSERT INTO orders (buyer_id, buyer_name, buyer_phone, buyer_address, order_date, status_id)
                  VALUES (?, ?, ?, ?, NOW(), ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("isssi", $buyer_id, $buyer_name, $buyer_phone, $buyer_address, $status_id);
        $stmt->execute();
        $insert_id = $stmt->insert_id;
        $stmt->close();
        return $insert_id;
    }

    public function update_order($id, $buyer_name, $buyer_phone, $buyer_address, $status_id)
    {
        $query = "UPDATE orders
                  SET buyer_name = ?, buyer_phone = ?, buyer_address = ?, status_id = ?
                  WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sssii", $buyer_name, $buyer_phone, $buyer_address, $status_id, $id);
        $stmt->execute();
        $affected_rows = $stmt->affected_rows;
        $stmt->close();
        return $affected_rows;
    }

    public function get_orders_by_user_id($user_id)
    {
        $query = "
        SELECT
        orders.*,
        SUM(order_items.price) AS total
    FROM
        orders
        INNER JOIN order_items ON orders.id = order_items.order_id
    WHERE
        orders.buyer_id = ?
    GROUP BY
        orders.id";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $orders = array();
        while ($order = $result->fetch_assoc()) {
            $orders[] = $order;
        }
        $stmt->close();
        return $orders;
    }

    public function get_order_by_id($id)
    {
        $query = "SELECT * FROM orders WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $order = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $order;
    }

    public function get_all_orders()
    {
        $query = "SELECT * FROM orders";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $orders = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $orders;
    }

    public function insert_order_item($order_id, $product_id, $quantity, $price)
    {
        $query = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("iiid", $order_id, $product_id, $quantity, $price);
        $stmt->execute();
        $insert_id = $stmt->insert_id;
        $stmt->close();
        return $insert_id;
    }

    public function update_order_item($id, $order_id, $product_id, $quantity, $price)
    {
        $query = "UPDATE order_items SET order_id = ?, product_id = ?, quantity = ?, price = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("iiidi", $order_id, $product_id, $quantity, $price, $id);
        $stmt->execute();
        $affected_rows = $stmt->affected_rows;
        $stmt->close();
        return $affected_rows;
    }

    public function get_order_item_by_id($id)
    {
        $query = "
            SELECT
                order_items.*,
                products.name,
                products.image_url
            FROM
                order_items
                INNER JOIN products ON order_items.product_id = products.id
            WHERE
                order_items.id = ?
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $order_item = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $order_item;
    }

    public function get_order_item_by_order_id($order_id)
    {
        $query = "
            SELECT
            order_items.*,
            products.name,
            products.image_url,
            products.price AS product_price
        FROM
            order_items
            INNER JOIN products ON order_items.product_id = products.id
        WHERE
            order_items.order_id = ?
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $order_items = $stmt->get_result();
        $stmt->close();

        // Query to fetch total sum of sum_price
        $stmt2 = $this->conn->prepare("
        SELECT
        SUM(order_items.price) AS total
    FROM
        order_items
    WHERE
        order_items.order_id = ?
    ");
        $stmt2->bind_param('i', $order_id);
        $stmt2->execute();
        $total_price = $stmt2->get_result();
        $total_sum_price = $total_price->fetch_assoc()['total'];
        $stmt2->close();

        return array('order_items' => $order_items, 'total_sum_price' => $total_sum_price);
    }
}

?>