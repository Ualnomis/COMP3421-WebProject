<?php

class Cart
{

    private $conn;
    private $cart_table = 'shopping_cart';

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    // Select cart data for a given user ID
    public function select($user_id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->cart_table . " WHERE user_id = ?");
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $cart = $result->fetch_assoc();
        $stmt->close();
        return $cart;
    }

    // Insert a new cart for a given user ID
    public function insert($user_id)
    {
        $stmt = $this->conn->prepare("INSERT INTO " . $this->cart_table . " (user_id)VALUES (?)");
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $cart_id = $stmt->insert_id;
        $stmt->close();
        return $cart_id;
    }

    // Update the quantity of a cart item
    public function updateCartItem($cart_item_id, $quantity)
    {
        $stmt = $this->conn->prepare("
            UPDATE shopping_cart_items
            SET quantity = ?
            WHERE id = ?
        ");
        $stmt->bind_param('ii', $quantity, $cart_item_id);
        $stmt->execute();
        $affected_rows = $stmt->affected_rows;
        $stmt->close();
        return $affected_rows;
    }


    // Delete a cart item
    public function deleteCartItem($cart_item_id)
    {
        $stmt = $this->conn->prepare("
            DELETE FROM shopping_cart_items
            WHERE id = ?
        ");
        $stmt->bind_param('i', $cart_item_id);
        $stmt->execute();
        $affected_rows = $stmt->affected_rows;
        $stmt->close();
        return $affected_rows;
    }

    //remove all cart item
    public function removeAllCartItem($cart_id)
    {
        $stmt = $this->conn->prepare("
        DELETE FROM shopping_cart_items
        WHERE cart_id = ?
        ");
        $stmt->bind_param('i', $cart_id);
        $stmt->execute();
        $affected_rows = $stmt->affected_rows;
        $stmt->close();
        return $affected_rows;
    }

    // Add a new item to the cart
    public function addItem($cart_id, $product_id, $quantity)
    {
        $stmt = $this->conn->prepare("
            INSERT INTO shopping_cart_items (cart_id, product_id, quantity)
            VALUES (?, ?, ?)
        ");
        $stmt->bind_param('iii', $cart_id, $product_id, $quantity);
        $stmt->execute();
        $affected_rows = $stmt->affected_rows;
        $stmt->close();
        return $affected_rows;
    }

    // Get cart items for a given cart ID
    public function getCartItems($cart_id)
    {
        // Query to fetch cart items
        $stmt = $this->conn->prepare("
            SELECT
                shopping_cart_items.*,
                products.name,
                products.id AS product_id,
                products.quantity AS remain_quantity,
                products.price * shopping_cart_items.quantity AS sum_price,
                products.price,
                products.image_url
            FROM
                shopping_cart_items
                INNER JOIN products ON shopping_cart_items.product_id = products.id
            WHERE
                shopping_cart_items.cart_id = ?
                AND
                products.status = 'show'
            GROUP BY
                shopping_cart_items.id
        ");
        $stmt->bind_param('i', $cart_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $cart_items = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        // Query to fetch total sum of sum_price
        $stmt2 = $this->conn->prepare("
            SELECT
                SUM(products.price * shopping_cart_items.quantity) AS total
            FROM
                shopping_cart_items
                INNER JOIN products ON shopping_cart_items.product_id = products.id
            WHERE
                shopping_cart_items.cart_id = ?
                AND
                products.status = 'show'
        ");
        $stmt2->bind_param('i', $cart_id);
        $stmt2->execute();
        $result2 = $stmt2->get_result();
        $total_sum_price = $result2->fetch_assoc()['total'];
        $stmt2->close();

        return array('cart_items' => $cart_items, 'total_sum_price' => $total_sum_price);
    }

    public function find_cart_item($cart_id, $product_id)
    {
        $stmt = $this->conn->prepare("
        SELECT
            shopping_cart_items.*,
            products.quantity AS remain_quantity
        FROM shopping_cart_items
        INNER JOIN products ON shopping_cart_items.product_id = products.id
        WHERE cart_id = ? AND product_id = ? AND status = 'show'
    ");
        $stmt->bind_param('ii', $cart_id, $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $cart_item = $result->fetch_assoc();
        $stmt->close();
        return $cart_item;
    }

    // Count the number of items in a given cart
    public function countItems($cart_id)
    {
        $stmt = $this->conn->prepare("
            SELECT COUNT(*)
            FROM shopping_cart_items
            WHERE cart_id = ? AND status = 'show'
        ");
        $stmt->bind_param('i', $cart_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $count = $result->fetch_row()[0];
        $stmt->close();
        return $count;
    }
}