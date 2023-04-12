<?php
class order
{
    private $conn;
    private $table = "orders";

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function select_all()
    {
        $query = "SELECT * FROM " . $this->table . " ORDER BY id DESC";
        $result = $this->conn->query($query);
        return $result;
    }

    function getOrderByUser($user_id){

    }

    function checkoutProduct($cart_product, $user_id, $total){
        
        $stmt = $this->conn->prepare("
        INSERT INTO orders (buyer_id, order_date, total)
        VALUES (?, ?, ?)
        ");
        $stmt->bind_param('iii', $user_id, date("Y-m-d"), $total);
        $stmt->execute();
        $order_id = $stmt->insert_id;
        $stmt->close();

        $stmt = $this->conn->prepare("
        INSERT INTO order_items (order_id, product_id, quantity, price)
        VALUES (?, ?, ?, ?)
        ");
        for ($i = 0; $i < count($cart_product); $i++) {
            $product_id = $cart_product[$i]['product_id'];
            $quantity = $cart_product[$i]['quantity'];
            $price = $cart_product[$i]['price'];
            $stmt->bind_param('iiii', $order_id, $product_id, $quantity, $price);
            $stmt->execute();
        }
        $stmt->close();

    }

    function updateOrder(){


    }

    function deleteOrder(){


    }


}
?>