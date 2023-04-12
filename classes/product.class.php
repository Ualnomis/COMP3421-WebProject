<?php
class Product
{
    private $conn;
    private $table = "products";

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

    public function select_one($id)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    }

    public function insert($seller_id, $name, $description, $price, $quantity, $image_url)
    {
        $query = "INSERT INTO " . $this->table . " (seller_id, name, description, price, quantity, image_url) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        if (empty($image_url)) {
            $image_url = "../assets/images/dummy_product_icon.png";
        }
        $stmt->bind_param("issdis", $seller_id, $name, $description, $price, $quantity, $image_url);
        return $stmt->execute();
    }

    public function update($id, $seller_id, $name, $description, $price, $quantity, $image_url)
    {
        $query = "UPDATE " . $this->table . " SET seller_id = ?, name = ?, description = ?, price = ?, quantity = ?, image_url = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        if (empty($image_url)) {
            $image_url = "../assets/images/dummy_product_icon.png";
        }
        $stmt->bind_param("issdisi", $seller_id, $name, $description, $price, $quantity, $image_url, $id);
        return $stmt->execute();
    }

    public function delete($id)
    {
        $query = "DELETE FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function decrease_product_quantity($product_id, $quantity_to_decrease)
    {
        $query = "UPDATE " . $this->table . " SET quantity = quantity - ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $quantity_to_decrease, $product_id);
        $stmt->execute();
        $affected_rows = $stmt->affected_rows;
        $stmt->close();
        return $affected_rows;
    }
}

?>